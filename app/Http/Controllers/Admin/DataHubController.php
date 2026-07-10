<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Support\CategoryLabels;
use App\Services\MarketRegistry;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Renders the Data Hub page - a filterable listing explorer
 * with Excel export. Supplies category and district lists;
 * actual filtering is done client-side via the API.
 */
class DataHubController extends Controller
{
    public function __construct(private readonly MarketRegistry $marketRegistry) {}

    public function index(): Response
    {
        $market = app('market') ?? $this->marketRegistry->defaultSlug();
        $marketConfig = $this->marketRegistry->fromSlug($market);
        $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

        $categories = [];

        foreach (config('scraper.profiles', []) as $source => $profileClass) {
            $belongsToMarket = collect($sourcePrefixes)
                ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'));

            if (! $belongsToMarket) {
                continue;
            }

            $categories[] = [
                'source' => $source,
                'label' => CategoryLabels::forSource($source),
            ];
        }

        $districts = Listing::query()
            ->where(function ($query) use ($sourcePrefixes) {
                foreach ($sourcePrefixes as $prefix) {
                    $query->orWhere('source_profile_name', 'like', $prefix.'_%');
                }
            })
            ->whereNotNull('district')
            ->distinct()
            ->orderBy('district')
            ->pluck('district')
            ->toArray();

        return Inertia::render('admin/data-hub', [
            'categories' => $categories,
            'districts' => $districts,
            'stale_days' => (int) config('scraper.stale_listing_days', 90),
        ]);
    }
}
