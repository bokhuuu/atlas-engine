<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Jobs;

use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Jobs\Middleware\RateLimitedMiddleware;
use App\Modules\Scraper\Jobs\Middleware\ThrottledRetryMiddleware;
use App\Modules\Scraper\Pipeline\ScraperPipeline;
use App\Modules\Scraper\Repositories\ListingRepository;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

/**
 * Crawls one listing detail page, runs it through the pipeline,
 * and persists the result to the database.
 *
 * Dispatched by CrawlIndexPageJob - one job per URL found on a grid page.
 * These are the most numerous jobs: 50 pages × 20 listings = ~1000 jobs per run.
 *
 * Screenshots are captured by atlas:capture-screenshots command
 * which runs on off-scrape nights.
 */
class CrawlDetailPageJob implements ShouldQueue
{
    use Batchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries;

    public int $timeout;

    private int $concurrency;

    public function __construct(
        private readonly ScraperProfileInterface $profile,
        private readonly string $url,
        private readonly int $scraperRunId,
    ) {
        $config = $profile->getQueueConfig();
        $this->tries = $config['retry_times'] ?? config('scraper.default_retry_times');
        $this->timeout = $config['timeout'] ?? config('scraper.default_timeout_s');
        $this->concurrency = $config['concurrency'] ?? config('scraper.default_concurrency');
    }

    public function middleware(): array
    {
        return [
            new RateLimitedMiddleware(
                source: $this->profile->getName(),
                maxConcurrent: $this->concurrency,
            ),
            new ThrottledRetryMiddleware,
        ];
    }

    /**
     * For session-reuse profiles (myhome.ge): reconnects to the existing
     * Chrome session saved by CrawlIndexPageJob — session cookies intact,
     * phone reveal works. Falls back to cookie injection if session expired.
     * For all other profiles: current behavior unchanged.
     */
    public function handle(ListingRepository $repository): void
    {
        $scraper = app($this->profile->getScraperClass(), [
            'profile' => $this->profile,
        ]);

        if ($this->profile->supportsSessionReuse()) {
            $sessionId = Redis::get(
                'scraper:session:'.$this->profile->getName()
            );

            if ($sessionId !== null) {
                $scraper->connectToSession($sessionId);
            } else {
                $authStrategy = $this->profile->getAuthStrategy($scraper->getBrowser());
                if ($authStrategy !== null) {
                    $scraper->setAuthStrategy($authStrategy);
                    $authStrategy->restoreCookiesOnly();
                }
            }
        } else {
            $authStrategy = $this->profile->getAuthStrategy($scraper->getBrowser());
            if ($authStrategy !== null) {
                $scraper->setAuthStrategy($authStrategy);
                $authStrategy->restoreCookiesOnly();
            }
        }

        try {
            $raw = $scraper->fetchDetailPage($this->url);

            $dto = ListingDTO::fromArray($raw);
            $dto->scraperRunId = $this->scraperRunId;

            $pipeline = app(ScraperPipeline::class, ['stages' => $this->profile->getPipelineStages()]);

            $processed = $pipeline->process($dto);

            if ($processed === null) {
                return;
            }

            $repository->updateOrCreate($processed->toArray());
        } catch (Throwable $e) {
            Log::warning('Listing skipped - unexpected error processing this one listing', [
                'source' => $this->profile->getName(),
                'url' => $this->url,
                'run_id' => $this->scraperRunId,
                'error' => $e->getMessage(),
            ]);
        } finally {
            $scraper->closeBrowser();
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error('CrawlDetailPageJob failed', [
            'source' => $this->profile->getName(),
            'url' => $this->url,
            'run_id' => $this->scraperRunId,
            'error' => $exception->getMessage(),
        ]);
    }
}
