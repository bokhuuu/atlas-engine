<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Api;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Enums\ScraperState;
use App\Modules\Scraper\Models\ScraperRun;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Repositories\ScraperRunRepository;
use App\Modules\Scraper\Requests\CancelScrapeRequest;
use App\Modules\Scraper\Requests\StartAllScrapeRequest;
use App\Modules\Scraper\Requests\StartScrapeRequest;
use App\Modules\Scraper\ScraperManager;
use App\Services\MarketRegistry;
use Illuminate\Http\JsonResponse;

/**
 * Handles HTTP control of scrape runs.
 *
 * Delegates all business logic to ScraperManager, ScraperRunRepository
 * and ListingRepository. This controller only receives, delegates and responds.
 */
class ScrapeController extends Controller
{
    public function __construct(
        private readonly ScraperManager $manager,
        private readonly ScraperRunRepository $runRepository,
        private readonly ListingRepository $listingRepository,
        private readonly MarketRegistry $marketRegistry,
    ) {}

    /**
     * Returns source profile keys for the market specified in the request query param.
     * Falls back to the default market if not provided.
     */
    private function currentMarketSources(): array
    {
        $market = request()->query('market', $this->marketRegistry->defaultSlug());
        $marketConfig = $this->marketRegistry->fromSlug($market);
        $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

        return array_values(array_filter(
            array_keys(config('scraper.profiles', [])),
            fn ($source) => collect($sourcePrefixes)
                ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'))
        ));
    }

    /**
     * Start a new scrape run for the given source.
     */
    public function start(StartScrapeRequest $request): JsonResponse
    {
        $source = $request->validated('source');
        $pages = $request->validated('pages');

        $profileClass = config("scraper.profiles.{$source}");
        $profile = app($profileClass);

        $this->manager->run($profile, $pages);

        $run = $this->runRepository->findLatest($source);

        return response()->json([
            'message' => 'Scrape started.',
            'run_id' => $run->id,
            'source' => $source,
            'pages' => $pages ?? $profile->getMaxPages(),
        ], 202);
    }

    /**
     * Start a scrape run for every registered category profile at once.
     *
     * Uses the same page count for all categories. Categories that are
     * already Running are skipped, not double-started - starting a second
     * concurrent run for the same source would waste workers and load
     * list.am twice for no benefit. For per-category page counts, start
     * each category individually via start() instead.
     */
    public function startAll(StartAllScrapeRequest $request): JsonResponse
    {
        $pages = $request->validated('pages');
        $sources = $this->currentMarketSources();
        $started = [];
        $skipped = [];

        foreach ($sources as $source) {
            $existingRun = $this->runRepository->findLatest($source);

            if ($existingRun && $existingRun->state === ScraperState::Running) {
                $skipped[] = $source;

                continue;
            }

            $profileClass = config("scraper.profiles.{$source}");
            $profile = app($profileClass);

            $this->manager->run($profile, $pages);

            $run = $this->runRepository->findLatest($source);

            $started[] = [
                'run_id' => $run->id,
                'source' => $source,
                'pages' => $pages ?? $profile->getMaxPages(),
            ];
        }

        return response()->json([
            'message' => count($skipped) > 0
                ? 'Scrape started for available categories; categories already running were skipped.'
                : 'Scrape started for all categories.',
            'runs' => $started,
            'skipped' => $skipped,
        ], 202);
    }

    /**
     * Return the latest scrape run state for a given source.
     */
    public function status(): JsonResponse
    {
        $source = request()->query('source');
        $run = $this->runRepository->findLatest($source);

        if (! $run) {
            return response()->json(['message' => 'No runs found for this source.'], 404);
        }

        return response()->json([
            'run_id' => $run->id,
            'source' => $run->source,
            'state' => $run->state,
            'started_at' => $run->started_at,
            'finished_at' => $run->finished_at,
            'scraped_pages' => $run->scraped_pages,
            'saved_listings' => $run->saved_listings,
        ]);
    }

    /**
     * Return the latest scrape run state for every registered category,
     * plus the current dedup-aware total listing count for each.
     *
     * Uses batch queries - one query for all runs, one for all counts -
     * instead of N queries per source. Fixes N+1 flagged by Sentry.
     *
     * Categories with no run yet appear with state "never_run" instead
     * of causing the whole response to fail.
     */
    public function statusAll(): JsonResponse
    {
        $sources = $this->currentMarketSources();

        // Batch fetch - one query each instead of N queries per source
        $latestRuns = $this->runRepository->findLatestPerSource($sources);
        $listingCounts = $this->listingRepository->countDistinctAllSources();

        $statuses = array_map(function (string $source) use ($latestRuns, $listingCounts): array {
            $run = $latestRuns[$source] ?? null;
            $totalListings = $listingCounts[$source] ?? 0;

            if (! $run) {
                return [
                    'source' => $source,
                    'state' => 'never_run',
                    'total_listings' => $totalListings,
                ];
            }

            return [
                'run_id' => $run->id,
                'source' => $run->source,
                'state' => $run->state,
                'started_at' => $run->started_at,
                'finished_at' => $run->finished_at,
                'scraped_pages' => $run->scraped_pages,
                'saved_listings' => $run->saved_listings,
                'total_listings' => $totalListings,
            ];
        }, $sources);

        return response()->json(['statuses' => $statuses]);
    }

    /**
     * Return a time-series of completed scrape runs for a given source.
     *
     * Each data point carries the run's own saved_listings count (new listings
     * that run added after dedup) plus a cumulative_listings total (running sum
     * up to and including that run), so the frontend can plot either without
     * computing it client-side.
     *
     * Returns an empty series - not a 404 - when no completed runs exist yet,
     * so the frontend can render an empty-state chart without special-casing
     * the HTTP status.
     */
    public function trend(): JsonResponse
    {
        $source = request()->query('source', '');

        $runs = $this->runRepository->getTrend($source);

        $cumulative = 0;
        $series = $runs
            ->groupBy(fn (ScraperRun $run) => $run->started_at->toDateString())
            ->map(function ($dayRuns, $date) use (&$cumulative): array {
                $newListings = $dayRuns->sum('saved_listings');
                $cumulative += $newListings;

                return [
                    'date' => $date,
                    'new_listings' => $newListings,
                    'cumulative_listings' => $cumulative,
                    'encountered' => $dayRuns->sum('encountered_listings'),
                ];
            })
            ->values();

        return response()->json([
            'source' => $source,
            'series' => $series,
        ]);
    }

    /**
     * Cancel an active scrape run by ID.
     */
    public function cancel(CancelScrapeRequest $request): JsonResponse
    {
        $runId = $request->validated('run_id');
        $run = $this->runRepository->findById($runId);

        $this->manager->cancel($runId);

        return response()->json([
            'message' => 'Scrape run cancelled.',
            'run_id' => $runId,
            'source' => $run->source,
        ]);
    }

    /**
     * Cancel every scrape run that is currently in progress.
     *
     * Categories that are idle, already finished, or never started
     * are left untouched - this only acts on state Running.
     */
    public function cancelAll(): JsonResponse
    {
        $sources = $this->currentMarketSources();
        $cancelled = [];

        foreach ($sources as $source) {
            $run = $this->runRepository->findLatest($source);

            if ($run && $run->state === ScraperState::Running) {
                $this->manager->cancel($run->id);

                $cancelled[] = [
                    'run_id' => $run->id,
                    'source' => $source,
                ];
            }
        }

        return response()->json([
            'message' => count($cancelled) > 0
                ? 'Cancelled all running scrapes.'
                : 'No running scrapes to cancel.',
            'cancelled' => $cancelled,
        ]);
    }
}
