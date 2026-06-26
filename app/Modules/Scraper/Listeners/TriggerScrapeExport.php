<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Listeners;

use App\Modules\Scraper\Events\ScrapeCompleted;
use App\Modules\Scraper\Exports\ExportManager;
use Illuminate\Support\Facades\Log;

/**
 * Delegates entirely to ExportManager - this listener's only job is
 * to bridge the event system and the export layer.
 * Which formats run is declared in the profile's getExports().
 */
class TriggerScrapeExport
{
    /** Takes the export manager used to actually generate the export files. */
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
