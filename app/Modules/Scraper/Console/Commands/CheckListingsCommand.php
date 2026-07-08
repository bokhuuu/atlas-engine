<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Console\Commands;

use App\Modules\Scraper\Browser\ProxyResolver;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Checks whether listings in the DB are still live on list.am.
 *
 * Makes lightweight HTTP HEAD requests - no Chrome, no Dusk.
 * Runs daily on off-scrape days, processing a percentage of total
 * listings per run. Oldest-checked listings get priority.
 *
 * On 200 → updates last_seen_at + last_checked_at.
 * On 404/gone → sets disappeared_at + last_checked_at.
 *
 * Usage:
 *   php artisan atlas:check-listings
 *   php artisan atlas:check-listings --dry-run
 */
class CheckListingsCommand extends Command
{
    protected $signature = 'atlas:check-listings
                            {--dry-run : Log what would be checked without making requests or updating DB}';

    protected $description = 'Check whether listings are still live on list.am via HTTP HEAD requests';

    public function handle(): int
    {
        $percent = (float) config('scraper.listing_check_daily_percent', 5.0);
        $delayMs = (int) config('scraper.listing_check_delay_ms', 2000);
        $dryRun = (bool) $this->option('dry-run');

        $total = Listing::whereNull('disappeared_at')->count();

        if ($total === 0) {
            $this->info('No active listings to check.');

            return self::SUCCESS;
        }

        $chunk = max(10, (int) ceil($total * $percent / 100));

        $this->info("Total active listings: {$total}");
        $this->info("Checking {$chunk} listings ({$percent}%) this run.");

        if ($dryRun) {
            $this->warn('Dry run - no requests will be made.');
        }

        $listings = Listing::whereNull('disappeared_at')
            ->orderByRaw('last_checked_at ASC NULLS FIRST')
            ->limit($chunk)
            ->get();

        $bar = $this->output->createProgressBar($listings->count());
        $bar->start();

        $stillLive = 0;
        $disappeared = 0;
        $errors = 0;

        foreach ($listings as $listing) {
            if ($dryRun) {
                $bar->advance();

                continue;
            }

            try {
                $proxy = app(ProxyResolver::class)->resolve();

                $options = ['stream' => true];

                if ($proxy !== null) {
                    $options['proxy'] = $proxy;
                }

                $response = Http::timeout(10)
                    ->withHeaders(['User-Agent' => config('scraper.user_agent')])
                    ->withOptions($options)
                    ->get($listing->url);

                $now = now();

                if ($response->successful()) {
                    $listing->update([
                        'last_seen_at' => $now,
                        'last_checked_at' => $now,
                    ]);
                    $stillLive++;
                } else {
                    $listing->update([
                        'disappeared_at' => $now,
                        'last_checked_at' => $now,
                    ]);
                    $disappeared++;

                    Log::info('Listing disappeared', [
                        'id' => $listing->id,
                        'external_id' => $listing->external_id,
                        'source' => $listing->source_profile_name,
                        'url' => $listing->url,
                        'status' => $response->status(),
                    ]);
                }
            } catch (Throwable $e) {
                $errors++;
                Log::warning('Listing check failed', [
                    'id' => $listing->id,
                    'url' => $listing->url,
                    'error' => $e->getMessage(),
                ]);

                Listing::where('id', $listing->id)->update([
                    'last_checked_at' => now(),
                ]);
            }

            $bar->advance();
            usleep($delayMs * 1000);
        }

        $bar->finish();
        $this->newLine();

        $this->info("Still live: {$stillLive}");
        $this->info("Disappeared: {$disappeared}");

        if ($errors > 0) {
            $this->warn("Errors (checked but inconclusive): {$errors}");
        }

        return self::SUCCESS;
    }
}
