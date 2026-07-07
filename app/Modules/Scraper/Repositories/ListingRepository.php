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
     * Find an existing listing by its external ID and source.
     *
     * Used by DetectPriceChangeStage to retrieve the current price
     * before a re-scrape overwrites it. Returns null for new listings.
     */
    public function findByExternalId(string $externalId, string $source): ?Listing
    {
        return Listing::where('external_id', $externalId)
            ->where('source_profile_name', $source)
            ->first();
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

        if (! empty($filters['rooms'])) {
            $query->where('rooms', (int) $filters['rooms']);
        }

        if (! empty($filters['min_area'])) {
            $query->where('area', '>=', (float) $filters['min_area']);
        }

        if (! empty($filters['max_area'])) {
            $query->where('area', '<=', (float) $filters['max_area']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Filter listings by multiple criteria for the Data Hub page.
     * Returns paginated results with all lifecycle fields included.
     */
    public function filterListings(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $staleDays = (int) config('scraper.stale_listing_days', 90);
        $query = Listing::query();

        if (! empty($filters['source'])) {
            $query->where('source_profile_name', $filters['source']);
        }

        if (! empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }

        if (! empty($filters['rooms'])) {
            $query->whereIn('rooms', (array) $filters['rooms']);
        }

        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        if (! empty($filters['min_area'])) {
            $query->where('area', '>=', (float) $filters['min_area']);
        }

        if (! empty($filters['max_area'])) {
            $query->where('area', '<=', (float) $filters['max_area']);
        }

        if (! empty($filters['agency'])) {
            if ($filters['agency'] === 'agency') {
                $query->whereNotNull('agency_name');
            } elseif ($filters['agency'] === 'owner') {
                $query->whereNull('agency_name');
            }
        }

        if (! empty($filters['scraped_from'])) {
            $query->where('scraped_at', '>=', $filters['scraped_from']);
        }

        if (! empty($filters['scraped_to'])) {
            $query->where('scraped_at', '<=', $filters['scraped_to'].' 23:59:59');
        }

        if (! empty($filters['status'])) {
            $statuses = (array) $filters['status'];
            $query->where(function ($q) use ($statuses, $staleDays) {
                foreach ($statuses as $i => $status) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    if ($status === 'active') {
                        $q->$method(function ($sub) use ($staleDays) {
                            $sub->whereNull('disappeared_at')
                                ->where('scraped_at', '>', now()->subDays($staleDays));
                        });
                    } elseif ($status === 'stale') {
                        $q->$method(function ($sub) use ($staleDays) {
                            $sub->whereNull('disappeared_at')
                                ->where('scraped_at', '<=', now()->subDays($staleDays));
                        });
                    } elseif ($status === 'disappeared') {
                        $q->$method(function ($sub) {
                            $sub->whereNotNull('disappeared_at');
                        });
                    }
                }
            });
        }

        return $query->orderByDesc('scraped_at')->paginate($perPage);
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
     * Uses PostgreSQL's native PERCENTILE_CONT(0.5) ordered-set aggregate,
     * which computes a true interpolated median in a single pass —
     * cleaner and faster than the window-function workaround required on MySQL.
     * Cached for 1 hour, same pattern as getDistrictPriceStats().
     */
    public function getMedianPricePerSqm(string $source): ?float
    {
        $key = "scraper:stats:median_price:{$source}";

        $value = Cache::remember($key, now()->addHour(), function () use ($source) {
            $sql = <<<'SQL'
                WITH deduplicated AS (
                    SELECT
                        COALESCE(duplicate_group_id, id) AS effective_group,
                        AVG(price_per_sqm) AS group_price_per_sqm
                    FROM listings
                    WHERE source_profile_name = ?
                        AND price_per_sqm IS NOT NULL
                    GROUP BY effective_group
                )
                SELECT PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY group_price_per_sqm) AS median
                FROM deduplicated
            SQL;

            $result = DB::select($sql, [$source]);

            return $result[0]->median !== null
                ? round((float) $result[0]->median, 2)
                : null;
        });

        return $value !== null ? (float) $value : null;
    }

    /**
     * Median price per sqm grouped by district for a given source.
     *
     * Companion to getDistrictPriceStats() (which provides AVG) — uses
     * PostgreSQL's native PERCENTILE_CONT(0.5) ordered-set aggregate
     * partitioned by district. Average alone is fragile against
     * real-but-extreme listings; with smaller per-district sample
     * sizes, that risk is even larger than the citywide case.
     *
     * CAST to numeric required because ROUND() in PostgreSQL only
     * accepts numeric, not double precision (which PERCENTILE_CONT returns).
     */
    public function getMedianPricePerSqmByDistrict(string $source): Collection
    {
        $key = "scraper:stats:median_district_prices:{$source}";

        return Cache::remember($key, now()->addHour(), function () use ($source) {
            $sql = <<<'SQL'
                WITH deduplicated AS (
                    SELECT
                        district,
                        COALESCE(duplicate_group_id, id) AS effective_group,
                        AVG(price_per_sqm) AS group_price_per_sqm
                    FROM listings
                    WHERE source_profile_name = ?
                        AND district IS NOT NULL
                        AND price_per_sqm IS NOT NULL
                    GROUP BY district, effective_group
                )
                SELECT
                    district,
                    ROUND(CAST(PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY group_price_per_sqm) AS numeric), 2) AS median_price_per_sqm
                FROM deduplicated
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
                ->selectRaw('district, ROUND(AVG(group_avg_price_per_sqm)::numeric, 2) as avg_price_per_sqm, COUNT(*) as listing_count')
                ->groupBy('district')
                ->orderByDesc('avg_price_per_sqm')
                ->get();
        });
    }

    /**
     * Count listings with a price reduction per district for a given source.
     * Only counts listings where price_change_pct is negative (price dropped)
     * and price_changed_at is within the last 7 days.
     */
    public function getDistrictPriceReductions(string $source): Collection
    {
        $days = (int) config('scraper.price_change_window_days', 7);

        return collect(
            DB::table('listings')
                ->where('source_profile_name', $source)
                ->whereNotNull('price_change_pct')
                ->where('price_change_pct', '<', 0)
                ->where('price_changed_at', '>=', now()->subDays($days))
                ->whereNotNull('district')
                ->select('district', DB::raw('COUNT(*) as reduction_count'))
                ->groupBy('district')
                ->get()
        )->keyBy('district');
    }

    /**
     * Count disappeared listings per district for a given source.
     * Only counts listings where disappeared_at is set.
     * Keyed by district for easy merging with other district stats.
     */
    public function getDistrictDisappearedCounts(string $source): Collection
    {
        return collect(
            DB::table('listings')
                ->where('source_profile_name', $source)
                ->whereNotNull('disappeared_at')
                ->whereNotNull('district')
                ->select('district', DB::raw('COUNT(*) as disappeared_count'))
                ->groupBy('district')
                ->get()
        )->keyBy('district');
    }

    /**
     * Count stale listings per district for a given source.
     * Stale = still active (no disappeared_at) but scraped more than
     * stale_listing_days ago. Threshold from config/scraper.php.
     * Keyed by district for easy merging with other district stats.
     */
    public function getDistrictStaleCounts(string $source): Collection
    {
        $staleDays = (int) config('scraper.stale_listing_days', 90);

        return collect(
            DB::table('listings')
                ->where('source_profile_name', $source)
                ->whereNull('disappeared_at')
                ->where('scraped_at', '<=', now()->subDays($staleDays))
                ->whereNotNull('district')
                ->select('district', DB::raw('COUNT(*) as stale_count'))
                ->groupBy('district')
                ->get()
        )->keyBy('district');
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

    /**
     * Clear cached district/citywide price stats for a source, after a
     * scrape run finishes. Called once, by
     * ScrapeCompletedJob, right after the whole batch settles.
     */
    public function clearStatsCache(string $source): void
    {
        Cache::forget("scraper:stats:district_prices:{$source}");
        Cache::forget("scraper:stats:median_price:{$source}");
        Cache::forget("scraper:stats:median_district_prices:{$source}");
        Cache::forget("ai:market_summary:{$source}");
    }
}
