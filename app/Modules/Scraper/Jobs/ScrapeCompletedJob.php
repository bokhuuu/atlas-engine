<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Jobs;

use App\Modules\Scraper\DriftDetector;
use App\Modules\Scraper\Events\ScrapeCompleted;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Repositories\ScraperRunRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Fires when all CrawlDetailPageJobs for a run have completed.
 *
 * Marks the ScraperRun as finished and logs a summary.
 * Triggered by ScraperManager via Bus::batch()->then() callback.
 * Will also fire export and notification events from here.
 */
class ScrapeCompletedJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $scraperRunId,
        private readonly string $source,
        private readonly int $scrapedPages,
    ) {
        $this->onQueue('completed');
    }

    public function handle(ScraperRunRepository $runRepository, ListingRepository $listingRepository, DriftDetector $driftDetector): void
    {
        $savedCount = $listingRepository->countByRun($this->scraperRunId);
        $run = $runRepository->findById($this->scraperRunId);
        $encounteredCount = $run->encountered_listings ?? 0;

        $runRepository->markAsCompleted(id: $this->scraperRunId, savedListings: $savedCount, scrapedPages: $this->scrapedPages);

        $listingRepository->clearStatsCache($this->source);

        $driftDetector->check($this->scraperRunId, $this->source, $encounteredCount);

        event(new ScrapeCompleted(scraperRunId: $this->scraperRunId, source: $this->source, listingCount: $savedCount));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('ScrapeCompletedJob failed', [
            'run_id' => $this->scraperRunId,
            'source' => $this->source,
            'error' => $exception->getMessage(),
        ]);
    }
}
