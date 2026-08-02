<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Listeners;

use App\Modules\Scraper\Events\ScrapeCompleted;
use App\Modules\Scraper\Exports\ExportManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Generates exports after a scrape completes.
 *
 * Implements ShouldQueue so export generation runs as a background job -
 * ScrapeCompletedJob dispatches this listener and completes immediately
 * without waiting for potentially slow Excel generation (4,000+ listings
 * can take 2+ minutes, exceeding the 120s Horizon worker timeout).
 *
 * Which formats run is declared in the profile's getExports().
 */
class TriggerScrapeExport implements ShouldQueue
{
    use InteractsWithQueue;

    public int $timeout = 600;

    public function __construct(
        private readonly ExportManager $exportManager,
    ) {}

    /**
     * Trigger full-accumulation exports after a scrape completes.
     * Exports everything currently in the DB for this source, not
     * just the listings saved in this one run - so every auto-generated
     * file always reflects the complete accumulated dataset.
     * Logs all generated file paths on success.
     */
    public function handle(ScrapeCompleted $event): void
    {
        $paths = $this->exportManager->runForSource($event->source, onlySpreadsheets: false);

        Log::info('TriggerScrapeExport: exports generated.', [
            'source' => $event->source,
            'run_id' => $event->scraperRunId,
            'files' => $paths,
        ]);
    }
}
