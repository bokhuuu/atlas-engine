<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Browser;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Throwable;

/**
 * Captures a screenshot of the current browser page and uploads it to
 * Backblaze B2. Called by CaptureScreenshotsCommand for listings without
 * a screenshot.
 *
 * Uses Chrome DevTools Protocol (CDP) directly via Selenium Grid's HTTP
 * endpoint — bypasses WebDriver's takeScreenshot() which hangs in Grid 4.
 *
 * Stores the B2 path (not a URL) in screenshot_url. Signed URLs are
 * generated on demand from the path - never stored as permanent links.
 */
class ScreenshotService
{
    private const DISK = 'b2_screenshots';

    /**
     * Capture the current browser page via CDP and upload to B2.
     * Returns the B2 path on success, null on any failure.
     * Failures are logged but never throw - a missing screenshot
     * must never fail a scrape run.
     */
    public function capture(Browser $browser, string $externalId, string $source): ?string
    {
        if (! config('scraper.screenshots_enabled', false)) {
            return null;
        }

        try {
            $sessionId = $browser->driver->getSessionID();
            $imageData = $this->captureViaCdp($sessionId);

            if ($imageData === null) {
                return null;
            }

            $b2Path = $this->buildB2Path($source, $externalId);

            Storage::disk(self::DISK)->put($b2Path, $imageData);

            return $b2Path;
        } catch (Throwable $e) {
            Log::warning('Screenshot capture failed', [
                'external_id' => $externalId,
                'source' => $source,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Generate a time-limited signed URL for viewing a screenshot.
     * Returns null if no screenshot path is stored.
     */
    public function signedUrl(string $b2Path, int $minutes = 30): ?string
    {
        try {
            return Storage::disk(self::DISK)->temporaryUrl(
                $b2Path,
                now()->addMinutes($minutes),
            );
        } catch (Throwable $e) {
            Log::warning('Screenshot signed URL generation failed', [
                'path' => $b2Path,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Capture screenshot via Chrome DevTools Protocol (CDP).
     *
     * Uses Selenium Grid's /session/{id}/chromium/send_command_and_get_result
     * endpoint which proxies CDP commands directly to Chrome. This bypasses
     * WebDriver's takeScreenshot() which hangs in Selenium Grid 4.
     *
     * Note: CDP endpoint is at /session/, not /wd/hub/session/ — the
     * chromedriver_url config includes /wd/hub which is for WebDriver only.
     *
     * Returns raw PNG bytes on success, null on failure.
     */
    private function captureViaCdp(string $sessionId): ?string
    {
        $client = new Client(['timeout' => 30]);

        $response = $client->post(
            'http://chrome:4444/session/'.$sessionId.'/chromium/send_command_and_get_result',
            ['json' => ['cmd' => 'Page.captureScreenshot', 'params' => ['format' => 'png']]]
        );

        $body = json_decode($response->getBody()->getContents(), true);
        $data = $body['value']['data'] ?? null;

        if (empty($data)) {
            return null;
        }

        return base64_decode($data);
    }

    /**
     * Build the B2 storage path for a screenshot.
     * Format: screenshots/{source}/{external_id}.png
     */
    private function buildB2Path(string $source, string $externalId): string
    {
        return "screenshots/{$source}/{$externalId}.png";
    }
}
