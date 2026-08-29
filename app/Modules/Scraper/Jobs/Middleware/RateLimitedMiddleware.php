<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Jobs\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

/**
 * Limits how many scraper jobs run concurrently per site.
 *
 * Uses Redis to track active job count per source.
 * If the limit is reached, the job is released back to the queue
 * and tried again after a short delay.
 */
class RateLimitedMiddleware
{
    public function __construct(
        private readonly string $source,
        private readonly int $maxConcurrent,
    ) {}

    /**
     * Lets the job through if under the concurrency limit for this source,
     * otherwise releases it back to the queue to try again shortly.
     *
     * Two strategies depending on maxConcurrent:
     *
     * concurrency = 1 (Chrome-based scrapers: list.am, myhome.ge):
     *   Uses Redis::funnel() — a mutex lock that guarantees true one-at-a-time
     *   execution. While a job holds the lock, all other jobs for the same
     *   source are released back to the queue. This prevents multiple Chrome
     *   sessions from opening simultaneously and saturating the Selenium Grid.
     *
     * concurrency > 1 (HTTP-based scrapers: bina.az):
     *   Uses Redis::throttle() — a rate limiter that allows N jobs per window.
     *   Suitable for stateless Guzzle requests where parallel execution is safe.
     */
    public function handle(mixed $job, Closure $next): void
    {
        if ($this->maxConcurrent === 1) {
            Redis::funnel("scraper:{$this->source}")
                ->limit(1)
                ->releaseAfter(config('scraper.rate_limit_release_s'))
                ->then(
                    fn () => $next($job),
                    function () use ($job) {
                        $job->release(config('scraper.rate_limit_release_s'));
                    }
                );
        } else {
            Redis::throttle("scraper:{$this->source}")
                ->allow($this->maxConcurrent)
                ->every(config('scraper.rate_limit_window_s'))
                ->then(
                    fn () => $next($job),
                    function () use ($job) {
                        $job->release(config('scraper.rate_limit_release_s'));
                    }
                );
        }
    }
}
