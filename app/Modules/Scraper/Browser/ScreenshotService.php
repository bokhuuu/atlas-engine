<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Browser;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Throwable;

/**
 * Captures a screenshot of the current browser page and uploads it to
 * Backblaze B2. Called by CrawlDetailPageJob for new listings only.
 *
 * Stores the B2 path (not a URL) in screenshot_url. Signed URLs are
 * generated on demand from the path - never stored as permanent links.
 *
 * Uses WebDriver's native takeScreenshot() directly instead of Dusk's
 * screenshot() method - avoids Dusk's file management and path config
 * which are not available outside of a test context.
 */
class ScreenshotService
{
    private const DISK = 'b2_screenshots';

    /**
     * Capture the current browser page and upload to B2.
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
            $tmpPath = $this->takeScreenshot($browser, $externalId);

            if ($tmpPath === null) {
                return null;
            }

            $b2Path = $this->buildB2Path($source, $externalId);

            Storage::disk(self::DISK)->put(
                $b2Path,
                file_get_contents($tmpPath),
            );

            @unlink($tmpPath);

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
     * Capture screenshot using WebDriver's native takeScreenshot().
     * Returns raw PNG bytes directly - no Dusk file management needed.
     * Writes to a temp file and returns the path.
     */
    private function takeScreenshot(Browser $browser, string $externalId): ?string
    {
        $imageData = $browser->driver->takeScreenshot();

        if (empty($imageData)) {
            return null;
        }

        $tmpPath = sys_get_temp_dir().'/atlas_screenshot_'.$externalId.'_'.time().'.png';

        file_put_contents($tmpPath, $imageData);

        if (! file_exists($tmpPath) || filesize($tmpPath) === 0) {
            return null;
        }

        return $tmpPath;
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
