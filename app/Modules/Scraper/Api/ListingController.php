<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Api;

use App\Http\Controllers\Controller;
use App\Modules\AI\Services\MarketSummaryService;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Services\MarketRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles HTTP access to scraped listing data.
 *
 * Delegates all data retrieval to ListingRepository.
 * This controller only receives, delegates and responds.
 */
class ListingController extends Controller
{
    /**
     * Takes the repository used for all listing data access.
     */
    public function __construct(
        private readonly ListingRepository $listingRepository,
        private readonly MarketRegistry $marketRegistry,
    ) {}

    /**
     * Return a paginated list of listings for a given source.
     */
    public function index(Request $request): JsonResponse
    {
        $source = $request->query('source', 'listam_apartments_sale');

        $listings = $this->listingRepository->paginateBySource($source, [
            'district' => $request->query('district'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
        ]);

        return response()->json($listings);
    }

    /**
     * Return aggregated price statistics grouped by district.
     *
     * Results are cached for 1 hour in Redis - see ListingRepository.
     */
    public function stats(Request $request): JsonResponse
    {
        $source = $request->query('source', 'listam_apartments_sale');

        $avgStats = $this->listingRepository->getDistrictPriceStats($source);
        $medianByDistrict = $this->listingRepository->getMedianPricePerSqmByDistrict($source);
        $reductions = $this->listingRepository->getDistrictPriceReductions($source);
        $disappeared = $this->listingRepository->getDistrictDisappearedCounts($source);
        $stale = $this->listingRepository->getDistrictStaleCounts($source);

        $merged = $avgStats->map(function ($row) use ($medianByDistrict, $reductions, $disappeared, $stale) {
            $row->median_price_per_sqm = $medianByDistrict->get($row->district)?->median_price_per_sqm;
            $row->price_reductions = (int) ($reductions->get($row->district)?->reduction_count ?? 0);
            $row->disappeared_count = (int) ($disappeared->get($row->district)?->disappeared_count ?? 0);
            $row->stale_count = (int) ($stale->get($row->district)?->stale_count ?? 0);

            return $row;
        });

        return response()->json([
            'source' => $source,
            'data' => $merged,
        ]);
    }

    /**
     * Generate an AI market summary for a given source.
     * Cached per source - repeated calls return the cached paragraph.
     */
    public function summary(Request $request): JsonResponse
    {
        $source = $request->query('source', '');

        $summary = app(MarketSummaryService::class)->summarize($source);

        return response()->json([
            'source' => $source,
            'summary' => $summary,
        ]);
    }

    /**
     * Return estimated rental yield per district for a property pair.
     *
     * Pair options: 'residential' (apartments sale vs rent)
     *               'commercial' (commercial sale vs rent)
     *
     * Yield = (rent_median * 12) / sale_median * 100
     * Only shown for districts where both sale and rent data exist.
     * Returns null yield for districts missing either side.
     */
    public function yield(Request $request): JsonResponse
    {
        $pair = $request->query('pair', 'residential');
        $market = $request->query('market', config('scraper.default_market', 'yerevan'));
        $marketConfig = $this->marketRegistry->fromSlug($market);
        $prefix = $marketConfig['source_prefixes'][0] ?? 'listam';

        [$saleSource, $rentSource] = match ($pair) {
            'commercial' => ["{$prefix}_commercial_sale", "{$prefix}_commercial_rent"],
            default => ["{$prefix}_apartments_sale", "{$prefix}_apartments_rent"],
        };

        $minListings = (int) config('scraper.yield_min_listings', 5);

        $saleMedians = $this->listingRepository->getMedianPricePerSqmByDistrict($saleSource);
        $rentMedians = $this->listingRepository->getMedianPricePerSqmByDistrict($rentSource);
        $saleCounts = $this->listingRepository->getDistrictPriceStats($saleSource)->keyBy('district');
        $rentCounts = $this->listingRepository->getDistrictPriceStats($rentSource)->keyBy('district');

        $districts = $saleMedians->keys()->merge($rentMedians->keys())->unique();

        $rows = $districts->map(function (string $district) use (
            $saleMedians,
            $rentMedians,
            $saleCounts,
            $rentCounts,
            $minListings
        ) {
            $sale = $saleMedians->get($district)?->median_price_per_sqm;
            $rent = $rentMedians->get($district)?->median_price_per_sqm;
            $saleCount = (int) ($saleCounts->get($district)?->listing_count ?? 0);
            $rentCount = (int) ($rentCounts->get($district)?->listing_count ?? 0);

            $yield = ($sale && $rent && (float) $sale > 0
                && $saleCount >= $minListings && $rentCount >= $minListings)
                ? round(((float) $rent * 12) / (float) $sale * 100, 2)
                : null;

            return [
                'district' => $district,
                'sale_median' => $sale ? round((float) $sale, 2) : null,
                'rent_median' => $rent ? round((float) $rent, 2) : null,
                'yield_pct' => $yield,
            ];
        })->values();

        return response()->json([
            'pair' => $pair,
            'rows' => $rows,
        ]);
    }

    /**
     * Filter listings by multiple criteria for the Data Hub page.
     * Supports source, district, rooms, price, area, agency, date range, status.
     */
    public function filter(Request $request): JsonResponse
    {
        $filters = $request->only([
            'source',
            'district',
            'rooms',
            'min_price',
            'max_price',
            'min_area',
            'max_area',
            'agency',
            'scraped_from',
            'scraped_to',
            'status',
        ]);

        $listings = $this->listingRepository->filterListings($filters, 20);

        return response()->json($listings);
    }
}
