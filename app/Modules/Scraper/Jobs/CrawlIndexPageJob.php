<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Jobs;

use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Jobs\Middleware\RateLimitedMiddleware;
use App\Modules\Scraper\Jobs\Middleware\ThrottledRetryMiddleware;
use App\Modules\Scraper\Repositories\ScraperRunRepository;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

/**
 * Crawls one index page (listing grid) and dispatches
 * one CrawlDetailPageJob per URL found on that page.
 *
 * Dispatched by ScraperManager - one job per page number.
 * 50 pages = 50 of these jobs, all processed in parallel.
 */
class CrawlIndexPageJob implements ShouldQueue
{
    use Batchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries;

    public int $timeout;

    private int $concurrency;

    /**
     * Sets up the job with the profile, page number, and run ID,
     * pulling retry/timeout/concurrency settings from the profile's
     * queue config (falling back to the global defaults).
     */
    public function __construct(
        private readonly ScraperProfileInterface $profile,
        private readonly int $page,
        private readonly int $scraperRunId,
    ) {
        $config = $profile->getQueueConfig();
        $this->tries = $config['retry_times'] ?? config('scraper.default_retry_times');
        $this->timeout = $config['timeout'] ?? config('scraper.default_timeout_s');
        $this->concurrency = $config['concurrency'] ?? config('scraper.default_concurrency');
    }

    /**
     * Rate-limits how many of this job can run at once per source,
     * and applies backoff between retries.
     */
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
     * Crawls the index page for listing URLs, queues one detail job
     * per URL into the same batch. For profiles that support session reuse,
     * saves the Chrome session ID to Redis so detail jobs can reconnect.
     * Closes the browser only for profiles that do not support session reuse.
     */
    public function handle(ScraperRunRepository $runRepository): void
    {
        $scraper = app($this->profile->getScraperClass(), ['profile' => $this->profile]);

        $authStrategy = $this->profile->getAuthStrategy($scraper->getBrowser());
        if ($authStrategy !== null) {
            $scraper->setAuthStrategy($authStrategy);
            $authStrategy->restoreCookiesOnly();
        }

        try {
            $urls = $scraper->crawlIndexPage($this->page);

            $runRepository->incrementEncountered($this->scraperRunId, count($urls));

            $jobs = [];
            foreach ($urls as $url) {
                $jobs[] = (new CrawlDetailPageJob(profile: $this->profile, url: $url, scraperRunId: $this->scraperRunId))
                    ->onQueue($this->profile->getQueue());
            }

            if (! empty($jobs) && $this->batch()) {
                $this->batch()->add($jobs);
            }

            if ($this->profile->supportsSessionReuse()) {
                $sessionId = $scraper->getSessionId();
                if ($sessionId !== null) {
                    Redis::setex(
                        'scraper:session:'.$this->profile->getName(),
                        300,
                        $sessionId,
                    );
                }
            }
        } finally {
            if (! $this->profile->supportsSessionReuse()) {
                $scraper->closeBrowser();
            }
        }
    }

    /**
     * Logs the failure with enough context to know which page and
     * run it belongs to.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('CrawlIndexPageJob failed', [
            'source' => $this->profile->getName(),
            'page' => $this->page,
            'run_id' => $this->scraperRunId,
            'error' => $exception->getMessage(),
        ]);
    }
}
