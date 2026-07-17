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

        $response = Http::timeout(45)->get(self::API_URL, [
            'access_key' => $accessKey,
            'url' => $url,
            'format' => 'jpg',
            'image_quality' => 80,
            'block_ads' => 'true',
            'block_cookie_banners' => 'true',
            'block_trackers' => 'true',
            'block_chats' => 'true',
            'full_page' => 'false',
            'viewport_width' => 1280,
            'viewport_height' => 1200,
            'timeout' => 30,
            'delay' => 1,
        ]);

        if (! $response->successful()) {
            Log::warning('ScreenshotService: API request failed', [
                'status' => $response->status(),
                'url' => $url,
            ]);

            return null;
        }

        $body = $response->body();

        if (strlen($body) < 3 || substr($body, 0, 2) !== "\xFF\xD8") {
            Log::warning('ScreenshotService: response is not a valid JPEG', [
                'url' => $url,
                'body_preview' => substr($body, 0, 200),
            ]);

            return null;
        }

        return $body;
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

    /**
     * Returns the public URL for a screenshot.
     * Requires the b2_screenshots bucket to be set to public.
     * Once public, URLs are permanent — no expiry.
     */
    public static function publicUrl(string $b2Path): string
    {
        $endpoint = config(
            'filesystems.disks.b2_screenshots.endpoint',
            'https://s3.eu-central-003.backblazeb2.com'
        );
        $bucket = config(
            'filesystems.disks.b2_screenshots.bucket',
            'bokhuuu-atlas-screenshots'
        );

        return "{$endpoint}/{$bucket}/{$b2Path}";
    }

    /**
     * Returns the best available URL for a screenshot.
     * Uses public URL if bucket is public, signed URL otherwise.
     */
    public static function url(string $b2Path): ?string
    {
        if (config('scraper.screenshots_public', false)) {
            return self::publicUrl($b2Path);
        }

        return (new self)->signedUrl($b2Path, 60 * 24 * 30);
    }
}
