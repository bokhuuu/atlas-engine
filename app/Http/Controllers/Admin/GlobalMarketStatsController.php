<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Support\CategoryLabels;
use App\Services\MarketRegistry;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Renders the Global Market Comparison page.
 *
 * Shows all configured markets side by side broken down by category -
 * median sale and rent price/sqm per category per market. Markets with
 * no data show null values; the frontend renders an appropriate empty state.
 *
 * Reuses ListingRepository::getMedianPricePerSqm() - no new queries needed.
 * When a new market goes live, it appears automatically with zero code changes.
 */
class GlobalMarketStatsController extends Controller
{
    public function __construct(
        private readonly MarketRegistry $marketRegistry,
        private readonly ListingRepository $listingRepository,
    ) {}

    /**
     * Returns per-category median prices for all configured markets,
     * structured for side-by-side comparison in the frontend table.
     */
    public function index(): Response
    {
        $allLabels = CategoryLabels::all();
        $marketSlugs = $this->marketRegistry->allSlugs();
        $markets = [];

        foreach ($marketSlugs as $slug) {
            $marketConfig = $this->marketRegistry->fromSlug($slug);
            $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

            $markets[$slug] = [
                'slug' => $slug,
                'label' => $marketConfig['label'] ?? ucfirst($slug),
                'flag' => $marketConfig['flag'] ?? '',
            ];

            foreach ($allLabels as $source => $label) {
                $belongsToMarket = collect($sourcePrefixes)
                    ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'));

                if (! $belongsToMarket) {
                    continue;
                }

                $markets[$slug]['categories'][$source] = [
                    'label' => $label,
                    'source' => $source,
                    'median_price_per_sqm' => $this->listingRepository->getMedianPricePerSqm($source),
                ];
            }
        }

        $allCategories = collect($allLabels)->map(fn ($label, $source) => [
            'source' => $source,
            'label' => $label,
            'listing_type' => str_contains($source, '_rent') ? 'rent' : 'sale',
        ])->values();

        return Inertia::render('admin/global-market-stats', [
            'marketStats' => array_values($markets),
            'allCategories' => $allCategories,
        ]);
    }
}
