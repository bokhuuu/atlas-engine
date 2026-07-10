<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Support\CategoryLabels;
use App\Services\MarketRegistry;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

/**
 * Handles the admin dashboard page.
 *
 * Queries aggregate stats across users and activity logs, plus the
 * per-category scraper configuration and dedup-aware totals the
 * Screen 1 control panel needs.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly ListingRepository $listingRepository,
        private readonly MarketRegistry $marketRegistry,
    ) {}

    /**
     * Renders the dashboard with aggregated user/activity stats and
     * the scraper category list - including dedup-aware totals -
     * the control panel needs.
     */
    public function index(): Response
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'trashed_users' => User::onlyTrashed()->count(),
            'total_activities' => Activity::count(),
            'today_activities' => Activity::whereDate('created_at', today())->count(),
        ];

        $market = app('market') ?? $this->marketRegistry->defaultSlug();
        $marketConfig = $this->marketRegistry->fromSlug($market);
        $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

        $scraperCategories = [];
        $grandTotalListings = 0;

        foreach (config('scraper.profiles', []) as $source => $profileClass) {
            $belongsToMarket = collect($sourcePrefixes)
                ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'));

            if (! $belongsToMarket) {
                continue;
            }

            $totalListings = $this->listingRepository->countDistinctBySource($source);
            $grandTotalListings += $totalListings;

            $scraperCategories[] = [
                'source' => $source,
                'label' => CategoryLabels::forSource($source),
                'default_pages' => config("scraper.profile_config.{$source}.max_pages"),
                'total_listings' => $totalListings,
            ];
        }

        return Inertia::render('admin/dashboard', [
            'stats' => $stats,
            'scraperCategories' => $scraperCategories,
            'grandTotalListings' => $grandTotalListings,
        ]);
    }
}
