<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Api;

use App\Http\Controllers\Controller;
use App\Services\MarketRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Serves time-series trend data for the Market Stats page.
 *
 * Two endpoints: listing volume over time and median price/sqm over time.
 * Both are scoped to the requested market via source_profile_name prefix
 * matching - consistent with every other API endpoint in the platform.
 *
 * Uses listing_date (when the seller posted) rather than scraped_at
 * (when we first saw it) - listing_date reflects actual market activity.
 * Grouped by month or quarter via ?groupBy= parameter; year support
 * intentionally deferred until sufficient historical data exists.
 */
class TrendController extends Controller
{
    public function __construct(
        private readonly MarketRegistry $marketRegistry,
    ) {}

    /**
     * Returns monthly or quarterly listing volume for a market.
     *
     * Counts listings grouped by listing_date truncated to the requested
     * period. Split by listing_type (sale/rent) so the frontend can plot
     * separate lines without a second request.
     *
     * Query param ?market= - market slug, falls back to default.
     * Query param ?groupBy= - 'month' (default) or 'quarter'.
     */
    public function volume(Request $request): JsonResponse
    {
        $groupBy = $this->resolveGroupBy($request);
        $sourcePrefixes = $this->resolveSourcePrefixes($request);

        if (empty($sourcePrefixes)) {
            return response()->json(['series' => []]);
        }

        $prefixConditions = $this->buildPrefixConditions($sourcePrefixes);

        $rows = DB::table('listings')
            ->selectRaw("
                date_trunc('{$groupBy}', listing_date) as period,
                listing_type,
                count(*) as total
            ")
            ->whereRaw($prefixConditions['sql'], $prefixConditions['bindings'])
            ->whereNotNull('listing_date')
            ->where('listing_date', '>=', now()->subMonths(24))
            ->groupByRaw("date_trunc('{$groupBy}', listing_date), listing_type")
            ->orderBy('period')
            ->get();

        $series = $rows->groupBy('period')->map(function ($periodRows, $period) {
            $entry = ['period' => substr($period, 0, 7)];

            foreach ($periodRows as $row) {
                $entry[$row->listing_type ?? 'unknown'] = (int) $row->total;
            }

            return $entry;
        })->values();

        return response()->json(['series' => $series]);
    }

    /**
     * Returns monthly or quarterly median price/sqm for sale listings.
     *
     * Uses PostgreSQL percentile_cont(0.5) for true median - not average -
     * consistent with the existing district stats endpoint. Sale listings
     * only; rent price/sqm on the same axis as sale is misleading.
     *
     * Query param ?market= - market slug, falls back to default.
     * Query param ?groupBy= - 'month' (default) or 'quarter'.
     */
    public function price(Request $request): JsonResponse
    {
        $groupBy = $this->resolveGroupBy($request);
        $sourcePrefixes = $this->resolveSourcePrefixes($request);

        if (empty($sourcePrefixes)) {
            return response()->json(['series' => []]);
        }

        $prefixConditions = $this->buildPrefixConditions($sourcePrefixes);

        $rows = DB::table('listings')
            ->selectRaw("
                date_trunc('{$groupBy}', listing_date) as period,
                round(
                    percentile_cont(0.5) within group (order by price_per_sqm)::numeric,
                    0
                ) as median_price_per_sqm
            ")
            ->whereRaw($prefixConditions['sql'], $prefixConditions['bindings'])
            ->whereNotNull('listing_date')
            ->whereNotNull('price_per_sqm')
            ->where('listing_type', 'sale')
            ->where('listing_date', '>=', now()->subMonths(24))
            ->groupByRaw("date_trunc('{$groupBy}', listing_date)")
            ->orderBy('period')
            ->get()
            ->map(fn ($row) => [
                'period' => substr($row->period, 0, 7),
                'median_price_per_sqm' => (int) $row->median_price_per_sqm,
            ]);

        return response()->json(['series' => $rows->values()]);
    }

    /**
     * Resolves the groupBy parameter to a PostgreSQL date_trunc unit.
     * Defaults to 'month'. 'year' intentionally not supported yet -
     * insufficient historical data to make it meaningful.
     */
    private function resolveGroupBy(Request $request): string
    {
        return match ($request->query('groupBy', 'month')) {
            'quarter' => 'quarter',
            default => 'month',
        };
    }

    /**
     * Resolves source prefixes for the requested market.
     * Falls back to the default market slug if ?market= is absent.
     */
    private function resolveSourcePrefixes(Request $request): array
    {
        $market = $request->query('market', $this->marketRegistry->defaultSlug());
        $marketConfig = $this->marketRegistry->fromSlug($market);

        return $marketConfig['source_prefixes'] ?? [];
    }

    /**
     * Builds a WHERE clause that matches source_profile_name against
     * any of the market's source prefixes - consistent with how every
     * other scoped query works across the platform.
     */
    private function buildPrefixConditions(array $sourcePrefixes): array
    {
        $clauses = array_map(fn ($p) => 'source_profile_name LIKE ?', $sourcePrefixes);
        $bindings = array_map(fn ($p) => $p.'_%', $sourcePrefixes);

        return [
            'sql' => '('.implode(' OR ', $clauses).')',
            'bindings' => $bindings,
        ];
    }
}
