<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Support\CategoryLabels;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Renders the Data Hub page - a filterable listing explorer
 * with Excel export. Supplies category and district lists;
 * actual filtering is done client-side via the API.
 */
class DataHubController extends Controller
{
    public function index(): Response
    {
        $categories = [];

        foreach (config('scraper.profiles', []) as $source => $profileClass) {
            $categories[] = [
                'source' => $source,
                'label' => CategoryLabels::forSource($source),
            ];
        }

        $districts = Listing::query()
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
