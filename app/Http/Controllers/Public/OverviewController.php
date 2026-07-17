<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Services\MarketRegistry;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Serves the combined public overview page at /.
 *
 * Shows all configured markets side by side - total listings per market,
 * active categories, and a link to each market's dedicated snapshot page.
 * No price/sqm shown - sample size per market is too small for a
 * methodologically defensible headline figure on a public page.
 *
 * This is the platform entry point. Per-market pages (/yerevan etc.)
 * remain accessible for market-specific views.
 */
class OverviewController extends Controller
{
    public function __construct(
        private readonly MarketRegistry $marketRegistry,
        private readonly ListingRepository $listingRepository,
    ) {}

    /**
     * Returns aggregated stats per market for the combined public overview.
     */
    public function __invoke(): Response
    {
        $markets = [];
        $totalListingsAllMarkets = 0;

        foreach ($this->marketRegistry->allSlugs() as $slug) {
            $marketConfig = $this->marketRegistry->fromSlug($slug);
            $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

            $totalListings = 0;
            $activeCategories = 0;
            $totalCategories = 0;

            foreach (config('scraper.profiles', []) as $source => $profileClass) {
                $belongsToMarket = collect($sourcePrefixes)
                    ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'));

                if (! $belongsToMarket) {
                    continue;
                }

                $totalCategories++;
                $count = $this->listingRepository->countDistinctBySource($source);
                $totalListings += $count;

                if ($count > 0) {
                    $activeCategories++;
                }
            }

            $totalListingsAllMarkets += $totalListings;

            $markets[] = [
                'slug' => $slug,
                'label' => $marketConfig['label'] ?? ucfirst($slug),
                'flag' => $marketConfig['flag'] ?? '',
                'total_listings' => $totalListings,
                'active_categories' => $activeCategories,
                'total_categories' => $totalCategories,
                'has_data' => $totalListings > 0,
            ];
        }

        return Inertia::render('public/overview', [
            'markets' => $markets,
            'totalListingsAllMarkets' => $totalListingsAllMarkets,
        ]);
    }
}
