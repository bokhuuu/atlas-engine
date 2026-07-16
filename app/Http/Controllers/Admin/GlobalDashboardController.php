<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Models\ScraperRun;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Services\MarketRegistry;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Renders the Global Dashboard - a cross-market overview for
 * Colliers management showing all configured markets side by side.
 *
 * No market context is needed here - this controller reads all
 * markets from MarketRegistry and aggregates stats for each.
 * Scraper controls are intentionally absent - those stay per-market.
 */
class GlobalDashboardController extends Controller
{
    public function __construct(
        private readonly MarketRegistry $marketRegistry,
        private readonly ListingRepository $listingRepository,
    ) {}

    /**
     * Returns aggregated stats for all configured markets.
     * Markets with no data show zero counts and null prices -
     * the frontend renders an appropriate empty state.
     */
    public function index(): Response
    {
        $markets = [];

        foreach ($this->marketRegistry->allSlugs() as $slug) {
            $marketConfig = $this->marketRegistry->fromSlug($slug);
            $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

            $totalListings = 0;
            $activeCategories = 0;
            $lastScrapedAt = null;

            foreach (config('scraper.profiles', []) as $source => $profileClass) {
                $belongsToMarket = collect($sourcePrefixes)
                    ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'));

                if (! $belongsToMarket) {
                    continue;
                }

                $count = $this->listingRepository->countDistinctBySource($source);
                $totalListings += $count;

                if ($count > 0) {
                    $activeCategories++;
                }

                $latestRun = ScraperRun::where('source', $source)
                    ->where('state', 'completed')
                    ->latest('finished_at')
                    ->first();

                if ($latestRun && (! $lastScrapedAt || $latestRun->finished_at > $lastScrapedAt)) {
                    $lastScrapedAt = $latestRun->finished_at;
                }
            }

            $saleSource = ($sourcePrefixes[0] ?? 'listam').'_apartments_sale';
            $medianPricePerSqm = $this->listingRepository->getMedianPricePerSqm($saleSource);

            $markets[] = [
                'slug' => $slug,
                'label' => $marketConfig['label'] ?? ucfirst($slug),
                'flag' => $marketConfig['flag'] ?? '',
                'currency' => $marketConfig['currency'] ?? 'USD',
                'total_listings' => $totalListings,
                'active_categories' => $activeCategories,
                'median_sale_price_per_sqm' => $medianPricePerSqm,
                'last_scraped_at' => $lastScrapedAt?->toDateTimeString(),
                'has_data' => $totalListings > 0,
            ];
        }

        $totalListingsAllMarkets = array_sum(array_column($markets, 'total_listings'));

        return Inertia::render('admin/global-dashboard', [
            'markets' => $markets,
            'totalListingsAllMarkets' => $totalListingsAllMarkets,
        ]);
    }
}
