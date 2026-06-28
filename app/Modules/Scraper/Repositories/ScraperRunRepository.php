<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Repositories;

use App\Modules\Scraper\Enums\ScraperState;
use App\Modules\Scraper\Models\ScraperRun;
use Illuminate\Support\Collection;

/**
 * Handles all database interactions for the ScraperRun model.
 *
 * Used by ScraperManager to track scrape execution lifecycle from start to finish.
 */
class ScraperRunRepository
{
    /**
     * Create a new scraper run record.
     *
     * Called at the start of each scrape. Initial state is always pending.
     */
    public function save(array $data): ScraperRun
    {
        return ScraperRun::create($data);
    }

    /**
     * Update the state of an existing scraper run.
     *
     * Called at each lifecycle transition: pending → running → completed / failed.
     */
    public function updateState(int $id, ScraperState $state): void
    {
        ScraperRun::where('id', $id)->update(['state' => $state]);
    }

    /**
     * Mark a scraper run as successfully completed.
     *
     * Convenience wrapper called by ScrapeCompletedJob.
     */
    public function markAsCompleted(int $id, int $savedListings = 0, int $scrapedPages = 0): void
    {
        ScraperRun::where('id', $id)->update([
            'state' => ScraperState::Completed,
            'finished_at' => now(),
            'saved_listings' => $savedListings,
            'scraped_pages' => $scrapedPages,
        ]);
    }

    /**
     * Mark a scraper run as failed.
     *
     * Convenience wrapper called by ScraperManager batch catch() callback.
     */
    public function markAsFailed(int $id, ?string $error = null): void
    {
        ScraperRun::where('id', $id)->update([
            'state' => ScraperState::Failed,
            'finished_at' => now(),
            'error' => $error,
        ]);
    }

    /**
     * Mark a scraper run as cancelled.
     *
     * Called by the scraper:cancel command.
     */
    public function markAsCancelled(int $id): void
    {
        $this->updateState($id, ScraperState::Cancelled);
    }

    /**
     * Find a single scraper run by its primary key.
     *
     * Returns null if no record exists with that ID.
     */
    public function findById(int $id): ?ScraperRun
    {
        return ScraperRun::find($id);
    }

    /**
     * Retrieve all scraper runs for a given source.
     */
    public function findBySource(string $source): Collection
    {
        return ScraperRun::forSource($source)->get();
    }

    /**
     * Retrieve the most recent scraper run for a given source.
     *
     * Returns null if no runs exist for that source yet.
     */
    public function findLatest(string $source): ?ScraperRun
    {
        return ScraperRun::where('source', $source)->latest()->first();
    }

    /**
     * Atomically increment the encountered-listings counter for a run.
     *
     * Called once per index page crawled, with the count of listing URLs
     * found on that page - before dedup, unlike saved_listings. Used by
     * DriftDetector to check scraper health independent of how many
     * listings turned out to be already-known re-scrapes.
     */
    public function incrementEncountered(int $id, int $count): void
    {
        if ($count > 0) {
            ScraperRun::where('id', $id)->increment('encountered_listings', $count);
        }
    }

    /**
     * Retrieve all completed scraper runs for a given source, oldest first.
     *
     * Used by the trend endpoint to build a time-series of listing volume
     * per category. Only completed runs are included - failed, cancelled
     * and still-running runs carry no reliable saved_listings count.
     */
    public function getTrend(string $source): Collection
    {
        return ScraperRun::forSource($source)
            ->forState(ScraperState::Completed)
            ->orderBy('started_at')
            ->get(['id', 'source', 'started_at', 'saved_listings', 'encountered_listings']);
    }
}
