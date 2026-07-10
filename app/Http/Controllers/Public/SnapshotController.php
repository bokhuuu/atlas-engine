<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Repositories\ListingRepository;
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
    /**
     * Real source_profile_name values, confirmed directly against
     * the database, paired with a human-readable label for the
     * frontend.
     */
    private const CATEGORIES = [
        'listam_apartments_sale' => 'Apartments for Sale',
        'listam_apartments_rent' => 'Apartments for Rent',
        'listam_houses_sale' => 'Houses for Sale',
        'listam_commercial_sale' => 'Commercial for Sale',
        'listam_commercial_rent' => 'Commercial for Rent',
        'listam_land_sale' => 'Land for Sale',
        'listam_new_developments_sale' => 'New Development Apartments',
    ];

    public function __construct(
        private readonly ListingRepository $listingRepository,
    ) {}

    /**
     * Return headline stats for all 7 categories: median price/sqm,
     * deduplicated listing count, and deal type (sale/rent) so the
     * frontend can label price correctly.
     */
    public function __invoke(): JsonResponse
    {
        $categories = collect(self::CATEGORIES)->map(
            fn (string $label, string $source) => $this->buildCategorySnapshot($source, $label)
        )->values();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Build one category's snapshot: city-wide median price/sqm,
     * deduplicated total count, and deal type parsed from the
     * source name itself (every real source name ends in _sale or
     * _rent - no separate hardcoded map needed for that).
     */
    private function buildCategorySnapshot(string $source, string $label): array
    {
        return [
            'source' => $source,
            'label' => $label,
            'deal_type' => Str::endsWith($source, '_rent') ? 'rent' : 'sale',
            'median_price_per_sqm' => $this->listingRepository->getMedianPricePerSqm($source),
            'total_listings' => $this->listingRepository->countDistinctBySource($source),
        ];
    }
}
