<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Support\CategoryLabels;
use App\Services\MarketRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

/**
 * Serves the public, no-login Market Snapshot (Screen 0).
 *
 * Reads already-deduplicated median price/sqm and total listing
 * count for all 7 categories via ListingRepository - no district
 * breakdown (that stays behind login on Screen 1), no trend (not
 * enough scheduled-scrape history yet, per ATLAS.md Section 15).
 *
 * Uses median, not mean, for price/sqm - confirmed necessary after
 * Commercial-for-Sale's mean ($4,348/sqm) was found to sit well
 * above real Yerevan market reports, traced to a handful of
 * genuinely real but extreme listings (e.g. a 5 sqm kiosk at
 * $1.8M), not a scraping bug. See getMedianPricePerSqm() for the
 * full reasoning. Applied uniformly to all 7 categories, not just
 * the one category where a problem was found - picking which
 * categories "need" the fix would be a judgment call about the
 * data, which isn't ours to make on a snapshot screen.
 */
class SnapshotController extends Controller
{
    public function __construct(
        private readonly ListingRepository $listingRepository,
        private readonly MarketRegistry $marketRegistry,
    ) {}

    /**
     * Return headline stats for all 7 categories: median price/sqm,
     * deduplicated listing count, and deal type (sale/rent) so the
     * frontend can label price correctly.
     */
    public function __invoke(): JsonResponse
    {
        $marketSlug = request()->query('market') ?? $this->marketRegistry->defaultSlug();
        $marketConfig = $this->marketRegistry->fromSlug($marketSlug);
        $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

        $counts = $this->listingRepository->countDistinctAllSources();

        $allLabels = CategoryLabels::all();
        $sources = collect($allLabels)
            ->filter(fn ($label, $source) => collect($sourcePrefixes)
                ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_')))
            ->map(fn ($label, $source) => $this->buildCategorySnapshot($source, $label, $counts))
            ->values();

        return response()->json([
            'categories' => $sources,
        ]);
    }

    private function buildCategorySnapshot(string $source, string $label, array $counts): array
    {
        return [
            'source' => $source,
            'label' => $label,
            'deal_type' => Str::endsWith($source, '_rent') ? 'rent' : 'sale',
            'median_price_per_sqm' => $this->listingRepository->getMedianPricePerSqm($source),
            'total_listings' => $counts[$source] ?? 0,
        ];
    }
}
