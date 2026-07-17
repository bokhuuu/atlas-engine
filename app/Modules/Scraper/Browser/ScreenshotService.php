<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Browser;

use App\Modules\Scraper\Models\Listing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Captures screenshots of listing detail pages via Screenshotone API
 * and stores them in Backblaze B2.
 *
 * Replaces the previous CDP/Selenium approach which hung in Horizon
 * queue worker contexts. Screenshotone is a simple HTTP API - no
 * browser session management, no Grid slot exhaustion risk.
 *
 * Screenshots are captured once per listing - never re-captured on
 * re-scrape. The screenshot_url column stores the B2 path.
 *
 * Requires SCREENSHOT_ACCESS_KEY in .env and
 * SCRAPER_SCREENSHOTS_ENABLED=true to activate.
 */
class ScreenshotService
{
    private const DISK = 'b2_screenshots';

    private const API_URL = 'https://api.screenshotone.com/take';

    /**
     * Capture a screenshot of the listing URL via Screenshotone,
     * upload to B2, and update the listing's screenshot_url column.
     *
     * Failures are logged but never throw - a missing screenshot
     * must never fail a scrape run or queue job.
     */
    public function captureAndUpload(Listing $listing): void
    {
        if (! config('scraper.screenshots_enabled', false)) {
            return;
        }

        if (empty($listing->url)) {
            return;
        }

        try {
            $imageData = $this->captureViaScreenshotone($listing->url);

            if ($imageData === null) {
                return;
            }

            $b2Path = $this->buildB2Path(
                $listing->source_profile_name,
                $listing->external_id,
            );

            Storage::disk(self::DISK)->put($b2Path, $imageData);

            $listing->update(['screenshot_url' => $b2Path]);

            Log::info('Screenshot captured', [
                'external_id' => $listing->external_id,
                'source' => $listing->source_profile_name,
                'path' => $b2Path,
            ]);
        } catch (Throwable $e) {
            Log::warning('Screenshot capture failed', [
                'external_id' => $listing->external_id,
                'source' => $listing->source_profile_name,
                'url' => $listing->url,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Capture a screenshot via Screenshotone HTTP API.
     *
     * Returns raw image bytes on success, null on failure.
     * Blocks ads and cookie banners for cleaner screenshots.
     */
    private function captureViaScreenshotone(string $url): ?string
    {
        $accessKey = config('scraper.screenshot_access_key');

        if (empty($accessKey)) {
            Log::warning('ScreenshotService: SCREENSHOT_ACCESS_KEY not configured');

            return null;
        }

        $response = Http::timeout(30)->get(self::API_URL, [
            'access_key' => $accessKey,
            'url' => $url,
            'format' => 'jpg',
            'block_ads' => 'true',
            'block_cookie_banners' => 'true',
            'block_trackers' => 'true',
            'full_page' => 'false',
            'viewport_width' => 1280,
            'viewport_height' => 800,
        ]);

        if (! $response->successful()) {
            Log::warning('ScreenshotService: API request failed', [
                'status' => $response->status(),
                'url' => $url,
            ]);

            return null;
        }

        return $response->body();
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
     * Build the B2 storage path for a screenshot.
     * Format: screenshots/{source}/{external_id}.jpg
     */
    private function buildB2Path(string $source, string $externalId): string
    {
        return config('scraper.screenshots.storage_path', 'screenshots')
            ."/{$source}/{$externalId}.jpg";
    }
}
