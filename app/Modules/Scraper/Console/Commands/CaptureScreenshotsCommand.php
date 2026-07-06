<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Console\Commands;

use App\Modules\Scraper\Browser\ScreenshotService;
use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Captures screenshots of listings that don't have one yet.
 *
 * Opens a fresh Chrome session per listing, navigates to the URL,
 * captures the full page, uploads to Backblaze B2, and stores the
 * path in screenshot_url.
 *
 * Runs on off-scrape nights (Tue/Thu/Sat at 05:00 Tbilisi) so it
 * never interferes with active scraping. Config-driven chunk size
 * limits how many screenshots are taken per run.
 *
 * Usage:
 *   php artisan atlas:capture-screenshots
 *   php artisan atlas:capture-screenshots --dry-run
 */
class CaptureScreenshotsCommand extends Command
{
    protected $signature = 'atlas:capture-screenshots
                            {--dry-run : Log what would be captured without opening Chrome or uploading}';

    protected $description = 'Capture screenshots of new listings and upload to Backblaze B2';

    public function handle(ScreenshotService $screenshotService): int
    {
        $chunk = (int) config('scraper.screenshot_daily_chunk', 200);
        $dryRun = (bool) $this->option('dry-run');

        $total = Listing::whereNull('screenshot_url')
            ->whereNull('disappeared_at')
            ->count();

        if ($total === 0) {
            $this->info('No listings need screenshots.');

            return self::SUCCESS;
        }

        $toProcess = min($total, $chunk);

        $this->info("Listings needing screenshots: {$total}");
        $this->info("Capturing {$toProcess} this run (chunk limit: {$chunk}).");

        if ($dryRun) {
            $this->warn('Dry run - no Chrome sessions will be opened.');

            return self::SUCCESS;
        }

        $listings = Listing::whereNull('screenshot_url')
            ->whereNull('disappeared_at')
            ->orderBy('scraped_at')
            ->limit($toProcess)
            ->get();

        $bar = $this->output->createProgressBar($listings->count());
        $bar->start();

        $captured = 0;
        $failed = 0;

        foreach ($listings as $listing) {
            Log::info('Processing listing', ['id' => $listing->id, 'url' => $listing->url]);

            if (empty($listing->url)) {
                $bar->advance();

                continue;
            }

            try {
                $profile = $this->resolveProfile($listing->source_profile_name);

                if ($profile === null) {
                    $bar->advance();

                    continue;
                }
                $scraper = app($profile->getScraperClass(), ['profile' => $profile]);

                $browser = $scraper->getBrowser();
                $browser->driver->manage()->timeouts()->pageLoadTimeout(0);
                $browser->driver->get($listing->url);

                usleep(5_000_000);

                $path = $screenshotService->capture(
                    $browser,
                    $listing->external_id,
                    $listing->source_profile_name,
                );

                $scraper->closeBrowser();

                if ($path !== null) {
                    $listing->update(['screenshot_url' => $path]);
                    $captured++;
                } else {
                    $failed++;
                    Log::warning('Screenshot capture returned null', [
                        'id' => $listing->id,
                        'url' => $listing->url,
                    ]);
                }
            } catch (Throwable $e) {
                $failed++;
                Log::warning('Screenshot capture failed', [
                    'id' => $listing->id,
                    'url' => $listing->url,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                try {
                    $scraper->closeBrowser();
                } catch (Throwable) {
                    // already closed
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Captured: {$captured}");

        if ($failed > 0) {
            $this->warn("Failed: {$failed}");
        }

        return self::SUCCESS;
    }

    /**
     * Resolve the correct scraper profile for a given source profile name.
     * Returns null if the profile is not registered in config.
     */
    private function resolveProfile(string $profileName): ?ScraperProfileInterface
    {
        $profiles = config('scraper.profiles', []);

        if (! isset($profiles[$profileName])) {
            return null;
        }

        return app($profiles[$profileName]);
    }
}
