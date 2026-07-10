<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Support\CategoryLabels;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Renders the Market Stats page - a read-only district breakdown
 * view, separate from the scraper control panel. The actual stats
 * are fetched client-side from the existing /v1/listings/stats
 * endpoint per selected category; this controller only supplies
 * the category list for the dropdown.
 */
class MarketStatsController extends Controller
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

        return Inertia::render('admin/market-stats', [
            'categories' => $categories,
        ]);
    }
}
