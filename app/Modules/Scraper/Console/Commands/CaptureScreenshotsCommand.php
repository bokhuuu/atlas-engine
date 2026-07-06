<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Console\Commands;

use App\Modules\Scraper\Browser\ScreenshotService;
use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Models\Listing;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Console\Command;
use Throwable;

/**
 * Captures screenshots of listings that don't have one yet.
 *
 * Opens a fresh Chrome session per listing using pageLoadStrategy=none,
 * navigates to the URL, waits for initial render, captures via CDP,
 * uploads to Backblaze B2, and stores the path in screenshot_url.
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
            if (empty($listing->url)) {
                $bar->advance();

                continue;
            }

            $driver = null;

            try {
                $options = new ChromeOptions;
                $options->addArguments([
                    '--headless',
                    '--no-sandbox',
                    '--disable-gpu',
                    '--disable-dev-shm-usage',
                    '--window-size=1920,1080',
                ]);

                $capabilities = DesiredCapabilities::chrome();
                $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
                $capabilities->setCapability('pageLoadStrategy', 'none');

                $driver = RemoteWebDriver::create(
                    'http://chrome:4444/wd/hub',
                    $capabilities,
                    30000,
                    30000,
                );

                $driver->get($listing->url);
                usleep(5_000_000);

                $sessionId = $driver->getSessionID();

                $path = $screenshotService->captureBySessionId(
                    $sessionId,
                    $listing->external_id,
                    $listing->source_profile_name,
                );

                $driver->quit();
                $driver = null;

                if ($path !== null) {
                    $listing->update(['screenshot_url' => $path]);
                    $captured++;
                } else {
                    $failed++;
                    $this->warn("Null capture for listing {$listing->id}: {$listing->url}");
                }
            } catch (Throwable $e) {
                $failed++;
                $this->error("Failed listing {$listing->id}: ".$e->getMessage());

                try {
                    $driver?->quit();
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
