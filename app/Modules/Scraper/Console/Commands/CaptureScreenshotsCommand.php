<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Console\Commands;

use App\Modules\Scraper\Browser\ScreenshotService;
use App\Modules\Scraper\Models\Listing;
use App\Services\MarketRegistry;
use Illuminate\Console\Command;
use Throwable;

/**
 * Captures screenshots of listings that don't have one yet.
 *
 * Uses Screenshotone API — no Chrome session needed. Sends listing URL,
 * receives JPG image, uploads to Backblaze B2, stores path in screenshot_url.
 *
 * Runs on off-scrape nights (Tue/Thu/Sat at 05:00 Tbilisi) so it
 * never interferes with active scraping. Config-driven chunk size
 * limits how many screenshots are taken per run.
 */
class CaptureScreenshotsCommand extends Command
{
    protected $signature = 'atlas:capture-screenshots
                            {--dry-run : Log what would be captured without opening Chrome or uploading}
                            {--market= : Limit captures to a specific market slug (e.g. yerevan, baku)}';

    protected $description = 'Capture screenshots of new listings and upload to Backblaze B2';

    /**
     * Find listings without screenshots and capture them via Screenshotone API.
     * Processes up to screenshot_daily_chunk listings per run to stay within
     * API rate limits and avoid long-running processes.
     *
     * Optional --market= flag scopes captures to a specific market,
     * useful for prioritizing a new market when it goes live.
     */
    public function handle(ScreenshotService $screenshotService): int
    {
        $chunk = (int) config('scraper.screenshot_daily_chunk', 200);
        $marketSlug = $this->option('market');

        $query = Listing::whereNull('screenshot_url')
            ->whereNotNull('url')
            ->whereNull('disappeared_at');

        if ($marketSlug) {
            $marketConfig = app(MarketRegistry::class)->fromSlug($marketSlug);
            $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

            if (empty($sourcePrefixes)) {
                $this->error("Unknown market: {$marketSlug}");

                return self::FAILURE;
            }

            $query->where(function ($q) use ($sourcePrefixes) {
                foreach ($sourcePrefixes as $prefix) {
                    $q->orWhere('source_profile_name', 'like', $prefix.'_%');
                }
            });
        }

        $listings = $query->limit($chunk)->get();
        $total = $listings->count();

        if ($total === 0) {
            $this->info('No listings need screenshots.');

            return self::SUCCESS;
        }

        $this->info("Listings needing screenshots: {$total}");
        $this->info("Capturing {$total} this run (chunk limit: {$chunk}).");

        if ($this->option('dry-run')) {
            $this->info('Dry run - no screenshots will be captured.');

            return self::SUCCESS;
        }

        $captured = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar($total);

        foreach ($listings as $listing) {
            try {
                $screenshotService->captureAndUpload($listing);

                if ($listing->fresh()->screenshot_url) {
                    $captured++;
                } else {
                    $failed++;
                    $this->warn("No screenshot for listing {$listing->id}: {$listing->url}");
                }
            } catch (Throwable $e) {
                $failed++;
                $this->error("Failed listing {$listing->id}: ".$e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Captured: {$captured}");
        $this->info("Failed: {$failed}");

        return self::SUCCESS;
    }
}
