<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Repositories;

use App\Modules\Scraper\Events\ListingSaved;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Handles all database interactions for the Listing model.
 *
 * All layers (pipeline, jobs, export) interact with listings through this class only.
 * Direct Eloquent calls outside this repository are not permitted.
 */
class ListingRepository
{
    /**
     * Create a brand new listing record.
     *
     * Rarely used directly - updateOrCreate() is the normal save path
     * since it handles re-scrapes without creating duplicates.
     */
    public function save(array $data): Listing
    {
        return Listing::create($data);
    }

    /**
     * Update an existing listing or create a new one if not found.
     *
     * Matches on external_id + source compound key.
     * Used by the pipeline as the primary save method to handle re-scrapes cleanly.
     *
     * @param array $data Expects output of ListingDTO::toArray()
     */
    public function updateOrCreate(array $data): Listing
    {
        if (isset($data['extras']['duplicate_group_id'])) {
            $data['duplicate_group_id'] = $data['extras']['duplicate_group_id'];
            unset($data['extras']['duplicate_group_id']);
        }

        $listing = Listing::updateOrCreate(
            ['external_id' => $data['external_id'], 'source_profile_name' => $data['source_profile_name']],
            $data
        );

        event(new ListingSaved($listing));

        return $listing;
    }

    /**
     * Check if a listing already exists in the database.
     *
     * Used by the deduplication pipeline stage to skip already-scraped listings.
     */
    public function existsByExternalId(string $externalId, string $source): bool
    {
        return Listing::where('external_id', $externalId)
            ->where('source_profile_name', $source)
            ->exists();
    }

    /**
     * Retrieve all active listings for a given source.
     */
    public function findBySource(string $source): Collection
    {
        return Listing::forSource($source)->get();
    }

    /**
     * Paginated listings for a source, optionally filtered by
     * district and/or a price range.
     */
    public function paginateBySource(string $source, array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        $query = Listing::where('source_profile_name', $source);

        if (! empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }

        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Count total listings for a given source.
     *
     * Used by ScraperManager to record saved_listings count on run completion.
     */
    public function countBySource(string $source): int
    {
        return Listing::where('source_profile_name', $source)->count();
    }

    /**
     * Count distinct properties for a given source, collapsing
     * probable cross-poster duplicates into one.
     *
     * Same dedup logic as getDistrictPriceStats() (COALESCE on
     * duplicate_group_id), but returns a single total instead of
     * a per-district breakdown. Used by the public Screen snapshot,
     * where a raw row count would visibly disagree with the
     * already-deduplicated district figures.
     */
    public function countDistinctBySource(string $source): int
    {
        return DB::table('listings')
            ->where('source_profile_name', $source)
            ->selectRaw('COUNT(DISTINCT COALESCE(duplicate_group_id, id)) as total')
            ->value('total');
    }

    /**
     * Median price per sqm for a given source, across all its
     * deduplicated listings citywide.
     *
     * Unlike AVG(), median is resistant to a handful of genuinely
     * real but extreme listings (e.g. a tiny premium-location kiosk
     * priced in the millions) skewing the headline number - confirmed
     * necessary after Commercial-for-Sale's average ($4,348/sqm) was
     * found to sit well above real Yerevan market reports, traced to
     * exactly this kind of extreme-but-real listing, not a scraping bug.
     *
     * MySQL 8.0 has no MEDIAN() function, so this is computed manually:
     * dedup groups are ranked by price, then the middle-ranked row (or
     * the average of the two middle rows, if the count is even) is
     * selected. Cached for 1 hour, same pattern as getDistrictPriceStats().
     */
    public function getMedianPricePerSqm(string $source): ?float
    {
        $key = "scraper:stats:median_price:{$source}";

        return Cache::remember($key, now()->addHour(), function () use ($source) {
            $sql = <<<'SQL'
            WITH deduplicated AS (
                SELECT
                    COALESCE(duplicate_group_id, id) AS effective_group,
                    AVG(price_per_sqm) AS group_price_per_sqm
                FROM listings
                WHERE source_profile_name = ?
                    AND price_per_sqm IS NOT NULL
                GROUP BY effective_group
            ),
            ranked AS (
                SELECT
                    group_price_per_sqm,
                    ROW_NUMBER() OVER (ORDER BY group_price_per_sqm) AS rn,
                    COUNT(*) OVER () AS total
                FROM deduplicated
            )
            SELECT AVG(group_price_per_sqm) AS median
            FROM ranked
            WHERE rn IN (FLOOR((total + 1) / 2), FLOOR((total + 2) / 2))
        SQL;

            $result = DB::select($sql, [$source]);

            return $result[0]->median !== null
                ? round((float) $result[0]->median, 2)
                : null;
        });
    }

    /**
     * Median price per sqm grouped by district for a given source.
     *
     * Companion to getDistrictPriceStats() (which provides AVG) - same
     * window-function approach as getMedianPricePerSqm(), partitioned
     * by district instead of computed citywide. Average alone is fragile
     * against real-but-extreme listings; with smaller per-district sample
     * sizes, that risk is even larger than the citywide case.
     */
    public function getMedianPricePerSqmByDistrict(string $source): Collection
    {
        $key = "scraper:stats:median_district_prices:{$source}";

        return Cache::remember($key, now()->addHour(), function () use ($source) {
            $sql = <<<'SQL'
        WITH deduplicated AS (
            SELECT district, COALESCE(duplicate_group_id, id) AS effective_group,
                   AVG(price_per_sqm) AS group_price_per_sqm
            FROM listings
            WHERE source_profile_name = ? AND district IS NOT NULL AND price_per_sqm IS NOT NULL
            GROUP BY district, effective_group
        ),
        ranked AS (
            SELECT district, group_price_per_sqm,
                   ROW_NUMBER() OVER (PARTITION BY district ORDER BY group_price_per_sqm) AS rn,
                   COUNT(*) OVER (PARTITION BY district) AS total
            FROM deduplicated
        )
        SELECT district, ROUND(AVG(group_price_per_sqm), 2) AS median_price_per_sqm
        FROM ranked
        WHERE rn IN (FLOOR((total + 1) / 2), FLOOR((total + 2) / 2))
        GROUP BY district
        SQL;

            return collect(DB::select($sql, [$source]))->keyBy('district');
        });
    }

    /**
     * Count listings saved by one specific scrape run.
     *
     * Used by ScrapeCompletedJob to report accurate per-run results,
     * instead of the all-time total for the source.
     */
    public function countByRun(int $scraperRunId): int
    {
        return Listing::where('scraper_run_id', $scraperRunId)->count();
    }

    /**
     * Retrieve all listings saved by one specific scrape run.
     *
     * Used by ExportManager so each export reflects only what
     * this run collected, not the entire historical dataset.
     */
    public function findByRun(int $scraperRunId): Collection
    {
        return Listing::where('scraper_run_id', $scraperRunId)->get();
    }

    /**
     * Average price per sqm grouped by district for a given source.
     *
     * Result is cached for 1 hour - expensive aggregation across
     * potentially thousands of rows. Cache invalidates automatically
     * so next API call recalculates fresh.
     *
     * Used by GET /api/listings/stats.
     */
    public function getDistrictPriceStats(string $source): Collection
    {
        $key = "scraper:stats:district_prices:{$source}";

        return Cache::remember($key, now()->addHour(), function () use ($source) {

            $deduplicated = DB::table('listings')
                ->where('source_profile_name', $source)
                ->whereNotNull('district')
                ->whereNotNull('price_per_sqm')
                ->selectRaw('district, COALESCE(duplicate_group_id, id) as effective_group, AVG(price_per_sqm) as group_avg_price_per_sqm')
                ->groupBy('district', 'effective_group');

            return DB::query()
                ->fromSub($deduplicated, 'deduplicated')
                ->selectRaw('district, ROUND(AVG(group_avg_price_per_sqm), 2) as avg_price_per_sqm, COUNT(*) as listing_count')
                ->groupBy('district')
                ->orderByDesc('avg_price_per_sqm')
                ->get();
        });
    }

    /**
     * Count listings where a specific field is null for a given source.
     *
     * Used by DriftDetector to calculate null rates on monitored fields.
     */
    public function countNullField(string $source, string $field): int
    {
        return Listing::where('source_profile_name', $source)
            ->whereNull($field)
            ->count();
    }

    /**
     * Find listings sharing the same district/floor/rooms as a candidate,
     * excluding the listing itself - used by FlagProbableDuplicatesStage
     * to find probable cross-poster duplicates.
     */
    public function findPotentialDuplicates(string $source, string $district, string $excludeExternalId): Collection
    {
        return Listing::where('source_profile_name', $source)
            ->where('district', $district)
            ->where('external_id', '!=', $excludeExternalId)
            ->get();
    }

    /**
     * Assign a duplicate group ID to an existing listing that doesn't
     * have one yet.
     */
    public function assignDuplicateGroupId(int $listingId, int $groupId): void
    {
        Listing::where('id', $listingId)->update(['duplicate_group_id' => $groupId]);
    }
}
