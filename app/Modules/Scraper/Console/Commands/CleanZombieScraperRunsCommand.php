<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Marks stale scraper runs as failed.
 *
 * A scraper run is considered zombie if it has been in 'running' state
 * for longer than the configured threshold (default 6 hours).
 * This happens when horizon:clear is called mid-run, preventing
 * ScrapeCompletedJob from ever firing.
 *
 * Schedule: daily at 03:00, runs automatically to keep scraper_runs table clean.
 */
class CleanZombieScraperRunsCommand extends Command
{
    protected $signature = 'scraper:clean-zombies
                            {--hours=6 : Mark runs older than this many hours as failed}';

    protected $description = 'Mark stale scraper runs as failed';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');

        $count = DB::table('scraper_runs')
            ->where('state', 'running')
            ->where('started_at', '<', now()->subHours($hours))
            ->update([
                'state' => 'failed',
                'finished_at' => now(),
                'error' => "Marked as failed by scraper:clean-zombies — run exceeded {$hours}h without completing.",
            ]);

        $this->info("Marked {$count} zombie scraper run(s) as failed.");

        return self::SUCCESS;
    }
}
