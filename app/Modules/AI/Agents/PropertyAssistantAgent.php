<?php

declare(strict_types=1);

namespace App\Modules\AI\Agents;

use App\Modules\AI\Models\Document;
use App\Modules\AI\Services\EmbeddingService;
use App\Modules\AI\Services\PromptService;
use App\Modules\Scraper\Browser\ScreenshotService;
use App\Modules\Scraper\Exports\ExportManager;
use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Support\CategoryLabels;
use App\Services\MarketRegistry;
use Illuminate\Support\Collection;
use LarAgent\Agent;
use LarAgent\Attributes\Tool;

class PropertyAssistantAgent extends Agent
{
    protected $name = 'PropertyAssistantAgent';

    protected $history = 'database';

    protected $tools = [];

    protected $mcpServers = [];

    protected ?int $lastPromptTokens = null;

    protected ?int $lastCompletionTokens = null;

    protected string $market = 'yerevan';

    public function getLastPromptTokens(): ?int
    {
        return $this->lastPromptTokens;
    }

    public function getLastCompletionTokens(): ?int
    {
        return $this->lastCompletionTokens;
    }

    protected function afterResponse(mixed $message): void
    {
        $storage = $this->usageStorage();
        if ($storage === null) {
            return;
        }
        $last = $storage->getLastUsage();
        if ($last !== null) {
            $this->lastPromptTokens = $last->promptTokens;
            $this->lastCompletionTokens = $last->completionTokens;
        }
    }

    /**
     * Returns true if the given source profile belongs to the current market.
     * In global context all sources are valid - the agent can query any market.
     */
    private function sourceBelongsToMarket(string $source): bool
    {
        if ($this->market === 'global') {
            return true;
        }

        $registry = app(MarketRegistry::class);
        $marketConfig = $registry->fromSlug($this->market);
        $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

        return collect($sourcePrefixes)
            ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'));
    }

    public function __construct(string $sessionId, $market = 'yerevan')
    {
        $this->market = $market;
        $this->provider = config('ai.providers.default', 'openrouter');
        parent::__construct($sessionId);
    }

    public function instructions(): string
    {
        return app(PromptService::class)->get(
            'property_assistant',
            $this->defaultSystemPrompt()
        );
    }

    public function model(): string
    {
        return config('ai.models.agent', 'anthropic/claude-haiku-4-5');
    }

    public function prompt(string $message): string
    {
        return $message;
    }

    #[Tool('Get total listing count and citywide median price per sqm for a real estate category. Use this for overview questions like how many listings exist or what the typical price is across the whole city.')]
    public function getCategoryStats(string $source): string
    {
        $labels = CategoryLabels::all();

        if (! $this->sourceBelongsToMarket($source)) {
            $registry = app(MarketRegistry::class);
            $marketConfig = $registry->fromSlug($this->market);
            $marketLabel = $marketConfig['label'] ?? ucfirst($this->market);

            return json_encode(['error' => "Source '{$source}' does not belong to the {$marketLabel} market. Use getCategoryList to see available categories."]);
        }

        if (! array_key_exists($source, $labels)) {
            return json_encode(['error' => 'Unknown category. Available: '.implode(', ', array_keys($labels))]);
        }
        $repo = app(ListingRepository::class);

        return json_encode([
            'category' => $labels[$source],
            'source' => $source,
            'total_listings' => $repo->countDistinctBySource($source),
            'median_price_per_sqm' => $repo->getMedianPricePerSqm($source),
            'currency' => 'USD',
        ]);
    }

    #[Tool('Get a breakdown of listing counts and prices per district for a real estate category. Use this for questions about which district is cheapest or most expensive, or what prices look like in a specific area.')]
    public function getDistrictBreakdown(string $source): string
    {
        $labels = CategoryLabels::all();

        if (! array_key_exists($source, $labels)) {
            return json_encode(['error' => 'Unknown category. Available: '.implode(', ', array_keys($labels))]);
        }

        if (! $this->sourceBelongsToMarket($source)) {
            $registry = app(MarketRegistry::class);
            $marketConfig = $registry->fromSlug($this->market);
            $marketLabel = $marketConfig['label'] ?? ucfirst($this->market);

            return json_encode(['error' => "Source '{$source}' does not belong to the {$marketLabel} market. Use getCategoryList to see available categories."]);
        }

        $repo = app(ListingRepository::class);
        $avg = $repo->getDistrictPriceStats($source)->keyBy('district');
        $median = $repo->getMedianPricePerSqmByDistrict($source)->keyBy('district');
        $districts = $avg->map(function ($row) use ($median) {
            return [
                'district' => $row->district,
                'listing_count' => $row->listing_count,
                'avg_price_per_sqm' => round((float) $row->avg_price_per_sqm, 2),
                'median_price_per_sqm' => round((float) ($median->get($row->district)?->median_price_per_sqm ?? 0), 2),
            ];
        })->sortByDesc('median_price_per_sqm')->values();

        return json_encode(['category' => $labels[$source], 'currency' => 'USD', 'districts' => $districts]);
    }

    #[Tool('Find real listings matching given criteria. All parameters except source are optional - call immediately with whatever the user provided, never ask for missing ones. Returns up to 10 real listings with prices, district, area, floor and links. For each listing: if screenshot_url is present show it as "📸 Screenshot" link; if url is present show it as "🔗 View listing" link. For disappeared listings (no active url), the screenshot_url is the only way to see the property - always show it prominently.')]
    public function findComparables(
        string $source,
        ?string $district = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?int $rooms = null,
    ): string {
        $labels = CategoryLabels::all();

        if (! array_key_exists($source, $labels)) {
            return json_encode(['error' => 'Unknown category. Available: '.implode(', ', array_keys($labels))]);
        }

        if (! $this->sourceBelongsToMarket($source)) {
            $registry = app(MarketRegistry::class);
            $marketConfig = $registry->fromSlug($this->market);
            $marketLabel = $marketConfig['label'] ?? ucfirst($this->market);

            return json_encode(['error' => "Source '{$source}' does not belong to the {$marketLabel} market. Use getCategoryList to see available categories."]);
        }

        $filters = array_filter([
            'district' => $district,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'rooms' => $rooms,
        ], fn ($v) => $v !== null);
        $repo = app(ListingRepository::class);
        $results = $repo->paginateBySource($source, $filters, 10);
        $listings = collect($results->items())->map(fn ($l) => [
            'title' => $l->title,
            'district' => $l->district,
            'price' => $l->price,
            'currency' => $l->currency,
            'price_per_sqm' => $l->price_per_sqm,
            'area' => $l->area,
            'rooms' => $l->rooms,
            'floor' => $l->floor,
            'url' => $l->url,
            'screenshot_url' => $l->screenshot_url
                ? ScreenshotService::url($l->screenshot_url)
                : null,
        ]);

        return json_encode([
            'category' => $labels[$source],
            'filters_applied' => $filters,
            'total_matches' => $results->total(),
            'showing' => $listings->count(),
            'listings' => $listings,
        ]);
    }

    #[Tool('List all real estate categories available in Atlas. Use this when the user asks what data is available or which source key to use for another tool.')]
    public function getCategoryList(): string
    {
        $registry = app(MarketRegistry::class);
        $marketConfig = null;

        if ($this->market === 'global') {
            $sourcePrefixes = collect($registry->all())
                ->flatMap(fn ($m) => $m['source_prefixes'])
                ->unique()
                ->values()
                ->toArray();
        } else {
            $marketConfig = $registry->fromSlug($this->market);
            $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];
        }

        $allLabels = CategoryLabels::all();
        $categories = collect($allLabels)
            ->filter(fn ($label, $source) => collect($sourcePrefixes)
                ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_')))
            ->map(fn ($label, $source) => [
                'source' => $source,
                'label' => $label,
                'deal_type' => str_contains($source, '_rent') ? 'rent' : 'sale',
            ])->values();

        $marketLabel = $this->market === 'global'
            ? 'Caucasus Region (all markets)'
            : ($marketConfig['label'] ?? ucfirst($this->market));

        if ($categories->isEmpty()) {
            return json_encode([
                'message' => "No data is available for the {$marketLabel} market yet. Data collection for this market has not started.",
                'categories' => [],
            ]);
        }

        return json_encode(['categories' => $categories]);
    }

    #[Tool('Export all listings for a category to an Excel file and return a download link. Use only when the user explicitly asks for an Excel export or file download.')]
    public function exportToExcel(string $source): string
    {
        $labels = CategoryLabels::all();

        if (! array_key_exists($source, $labels)) {
            return json_encode(['error' => 'Unknown category. Available: '.implode(', ', array_keys($labels))]);
        }

        if (! $this->sourceBelongsToMarket($source)) {
            $registry = app(MarketRegistry::class);
            $marketConfig = $registry->fromSlug($this->market);
            $marketLabel = $marketConfig['label'] ?? ucfirst($this->market);

            return json_encode(['error' => "Source '{$source}' does not belong to the {$marketLabel} market. Use getCategoryList to see available categories."]);
        }

        try {
            $paths = app(ExportManager::class)->runForSource($source, onlySpreadsheets: true);
            if (empty($paths)) {
                return json_encode(['error' => 'No listings found for this category.']);
            }

            return json_encode([
                'message' => 'Export ready.',
                'download_url' => url("/{$this->market}/admin/exports/{$source}"),
                'category' => $labels[$source],
            ]);
        } catch (\Throwable $e) {
            return json_encode(['error' => 'Export failed: '.$e->getMessage()]);
        }
    }

    #[Tool('Get districts with recent price reductions for a category. Use when the user asks which areas have sellers dropping prices, or where prices are falling. Returns districts with reduction counts in the last 7 days.')]
    public function getPriceChanges(string $source): string
    {
        $labels = CategoryLabels::all();

        if (! array_key_exists($source, $labels)) {
            return json_encode(['error' => 'Unknown category. Available: '.implode(', ', array_keys($labels))]);
        }

        if (! $this->sourceBelongsToMarket($source)) {
            $registry = app(MarketRegistry::class);
            $marketConfig = $registry->fromSlug($this->market);
            $marketLabel = $marketConfig['label'] ?? ucfirst($this->market);

            return json_encode(['error' => "Source '{$source}' does not belong to the {$marketLabel} market. Use getCategoryList to see available categories."]);
        }

        $repo = app(ListingRepository::class);
        $reductions = $repo->getDistrictPriceReductions($source);

        if ($reductions->isEmpty()) {
            return json_encode([
                'category' => $labels[$source],
                'message' => 'No price reductions detected in the last 7 days for this category.',
            ]);
        }

        $rows = $reductions->map(fn ($r) => [
            'district' => $r->district,
            'reduction_count' => $r->reduction_count,
        ])->sortByDesc('reduction_count')->values();

        return json_encode([
            'category' => $labels[$source],
            'currency' => 'USD',
            'window' => '7 days',
            'districts' => $rows,
        ]);
    }

    #[Tool('Get estimated rental yield per district for residential or commercial property. Use when the user asks about return on investment, rental yield, or income potential. Only available for apartments (residential) and commercial categories. In global context, suggests using compareMarkets instead.')]
    public function getYieldEstimate(string $pair = 'residential'): string
    {
        $repo = app(ListingRepository::class);

        $registry = app(MarketRegistry::class);

        if ($this->market === 'global') {
            return json_encode([
                'message' => 'Yield estimates are market-specific. Please specify a market (e.g. "show Yerevan yield") or use the compareMarkets tool for cross-market comparison.',
            ]);
        }

        $marketConfig = $registry->fromSlug($this->market);
        $prefix = $marketConfig['source_prefixes'][0] ?? 'listam';

        [$saleSource, $rentSource] = match ($pair) {
            'commercial' => ["{$prefix}_commercial_sale", "{$prefix}_commercial_rent"],
            default => ["{$prefix}_apartments_sale", "{$prefix}_apartments_rent"],
        };

        $minListings = (int) config('scraper.yield_min_listings', 5);
        $saleMedians = $repo->getMedianPricePerSqmByDistrict($saleSource);
        $rentMedians = $repo->getMedianPricePerSqmByDistrict($rentSource);
        $saleCounts = $repo->getDistrictPriceStats($saleSource)->keyBy('district');
        $rentCounts = $repo->getDistrictPriceStats($rentSource)->keyBy('district');

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
        })->filter(fn ($r) => $r['yield_pct'] !== null)
            ->sortByDesc('yield_pct')
            ->values();

        if ($rows->isEmpty()) {
            return json_encode([
                'pair' => $pair,
                'message' => 'Not enough data in both sale and rent categories to calculate yield. Need at least '.$minListings.' listings per district on each side.',
            ]);
        }

        return json_encode([
            'pair' => $pair,
            'currency' => 'USD',
            'note' => 'Gross yield estimate based on median prices. Professional adjustment required for vacancy, maintenance and taxes.',
            'districts' => $rows,
        ]);
    }

    #[Tool('Compare key real estate metrics across all available markets. Use when the user asks to compare cities, markets, or wants to know which market has more listings, higher prices, or better yield. Returns side-by-side data for all configured markets.')]
    public function compareMarkets(?string $category = null): string
    {
        $registry = app(MarketRegistry::class);
        $repo = app(ListingRepository::class);
        $results = [];

        foreach ($registry->allSlugs() as $marketSlug) {
            $marketConfig = $registry->fromSlug($marketSlug);
            $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];
            $prefix = $sourcePrefixes[0] ?? null;

            if (! $prefix) {
                continue;
            }

            $saleSource = "{$prefix}_apartments_sale";
            $rentSource = "{$prefix}_apartments_rent";

            $totalListings = 0;
            foreach (array_keys(config('scraper.profiles', [])) as $source) {
                if (collect($sourcePrefixes)->contains(fn ($p) => str_starts_with($source, $p.'_'))) {
                    $totalListings += $repo->countDistinctBySource($source);
                }
            }

            $medianSalePrice = $repo->getMedianPricePerSqm($saleSource);
            $medianRentPrice = $repo->getMedianPricePerSqm($rentSource);

            $results[] = [
                'market' => $marketConfig['label'] ?? ucfirst($marketSlug),
                'slug' => $marketSlug,
                'currency' => 'USD',
                'total_listings' => $totalListings,
                'median_sale_price_per_sqm' => $medianSalePrice,
                'median_rent_price_per_sqm' => $medianRentPrice,
                'has_data' => $totalListings > 0,
            ];
        }

        $marketsWithData = collect($results)->where('has_data', true)->count();

        if ($marketsWithData === 0) {
            return json_encode([
                'message' => 'No market data available yet for any configured market.',
                'markets' => $results,
            ]);
        }

        return json_encode([
            'note' => 'All prices in USD. Markets with no data show null values - data collection has not started for those markets yet.',
            'markets' => $results,
        ]);
    }

    #[Tool('Search listings by meaning rather than exact keywords. Use when the user describes a property in natural language - e.g. "find apartments that mention renovation" or "listings with mountain view". Returns null if no text data has been indexed for this market yet.')]
    public function semanticSearch(string $query, ?int $limit = null): string
    {
        $service = app(EmbeddingService::class);

        if (! $service->hasDocuments()) {
            return json_encode([
                'message' => 'No listing text has been indexed for semantic search yet. Use findComparables for structured search by price, district, and rooms.',
            ]);
        }

        $results = $service->search($query, $limit ?? 5);

        if ($results->isEmpty()) {
            return json_encode(['message' => 'No listings found matching that description.']);
        }

        return json_encode([
            'query' => $query,
            'results' => $results->map(fn (Document $doc) => [
                'content' => $doc->content,
                'source_type' => $doc->source_type,
                'source_id' => $doc->source_id,
            ])->values(),
        ]);
    }

    #[Tool('Get pricing statistics for a named location or development — e.g. "Sea Breeze", "White City", "Badamdar". For known developments uses exact scraped data; for others searches listing descriptions. Always presents AZN prices (primary) with USD in parentheses. Includes size range breakdown. source parameter is optional.')]
    public function getLocationStats(string $location_name, ?string $source = null): string
    {
        // Check if we have an exact profile for this location
        $exactProfile = config("scraper.binaz.location_profiles.{$location_name}");

        if ($exactProfile !== null) {
            $listings = Listing::where('source_profile_name', $exactProfile)
                ->whereNotNull('price')
                ->get();

            if ($listings->isEmpty()) {
                return json_encode([
                    'message' => "No listings found for '{$location_name}'. The scraper profile exists but no data has been collected yet.",
                ]);
            }

            $propertyType = $listings->groupBy('property_type')
                ->sortByDesc(fn ($g) => $g->count())
                ->keys()
                ->first() ?? 'land';
            $districts = $listings->groupBy('district')
                ->map(fn ($group) => $group->count())
                ->sortDesc()
                ->toArray();

            return json_encode([
                'location' => $location_name,
                'data_source' => 'exact — scraped directly from bina.az location filter',
                'source_profile' => $exactProfile,
                'currency' => 'AZN (USD)',
                'districts' => $districts,
                'stats' => $this->calculateListingStats($listings, $propertyType),
            ]);
        }

        // Fall back to keyword search in descriptions
        $query = Listing::whereNotNull('price')
            ->where('translated_description', 'ilike', '%'.$location_name.'%');

        if ($source !== null) {
            $query->where('source_profile_name', $source);
        }

        $listings = $query->get();

        if ($listings->isEmpty()) {
            return json_encode([
                'message' => "No listings explicitly mention '{$location_name}' in their description. This location may have data in other categories not yet scraped (e.g. offices, apartments).",
            ]);
        }

        if ($listings->count() < 5) {
            return json_encode([
                'message' => "Only {$listings->count()} listing(s) explicitly mention '{$location_name}'. Insufficient data for reliable statistics. This location likely has more data in other categories not yet scraped.",
            ]);
        }

        $propertyType = $listings->groupBy('property_type')
            ->sortByDesc(fn ($g) => $g->count())
            ->keys()
            ->first() ?? 'land';
        $districts = $listings->groupBy('district')
            ->map(fn ($group) => $group->count())
            ->sortDesc()
            ->toArray();

        return json_encode([
            'location' => $location_name,
            'data_source' => 'keyword search — listings mentioning this location in description, typically nearby properties',
            'source_filter' => $source,
            'currency' => 'AZN (USD)',
            'districts' => $districts,
            'stats' => $this->calculateListingStats($listings, $propertyType),
        ]);
    }

    #[Tool('Get pricing statistics for an entire district by its English name — e.g. "Khazar", "Sabail", "Narimanov". Queries the database directly by district column. source parameter is optional — omit to search across all categories.')]
    public function getDistrictStats(string $district, ?string $source = null): string
    {
        $query = Listing::where('district', $district)->whereNotNull('price');

        if ($source !== null) {
            $query->where('source_profile_name', $source);
        }

        $listings = $query->get();

        if ($listings->isEmpty()) {
            return json_encode(['message' => "No listings found for district '{$district}'."]);
        }

        $propertyType = $source !== null
            ? ($listings->first()?->property_type ?? 'land')
            : ($listings->groupBy('property_type')->sortByDesc(fn ($g) => $g->count())->keys()->first() ?? 'land');

        return json_encode([
            'district' => $district,
            'source_filter' => $source,
            'currency' => 'AZN (USD)',
            'stats' => $this->calculateListingStats($listings, $propertyType),
        ]);
    }

    private function calculateListingStats(Collection $listings, string $propertyType = 'land'): array
    {
        $prices = $listings->pluck('price')->filter()->values();
        $sqmPrices = $listings->pluck('price_per_sqm')->filter()->values();
        $areas = $listings->pluck('area')->filter()->values();
        $billOfSale = $listings->whereNotNull('has_bill_of_sale');

        // AZN prices from original scraped value
        $aznPrices = $listings->map(fn ($l) => $l->extras['original_price_azn'] ?? null)
            ->filter()
            ->values();

        // Median price USD
        $sortedPrices = $prices->sort()->values();
        $count = $sortedPrices->count();
        $median = 0;
        if ($count > 0) {
            $mid = (int) floor($count / 2);
            $median = $count % 2 === 0
                ? ($sortedPrices[$mid - 1] + $sortedPrices[$mid]) / 2
                : $sortedPrices[$mid];
        }

        // Median price/sqm USD
        $sortedSqmPrices = $sqmPrices->sort()->values();
        $sqmCount = $sortedSqmPrices->count();
        $medianSqm = 0;
        if ($sqmCount > 0) {
            $sqmMid = (int) floor($sqmCount / 2);
            $medianSqm = $sqmCount % 2 === 0
                ? ($sortedSqmPrices[$sqmMid - 1] + $sortedSqmPrices[$sqmMid]) / 2
                : $sortedSqmPrices[$sqmMid];
        }

        // Weighted avg price/sqm USD
        $withBothUsd = $listings->filter(fn ($l) => $l->price && $l->area);
        $totalPrice = $withBothUsd->sum('price');
        $totalArea = $withBothUsd->sum('area');
        $weightedAvgPriceSqm = ($totalArea > 0) ? round($totalPrice / $totalArea, 2) : null;

        $withBothAzn = $listings->filter(fn ($l) => ($l->extras['original_price_azn'] ?? null) && $l->area);
        $totalAzn = $withBothAzn->sum(fn ($l) => $l->extras['original_price_azn']);
        $totalAreaAzn = $withBothAzn->sum('area');
        $weightedAvgPriceSqmAzn = ($totalAreaAzn > 0) ? round($totalAzn / $totalAreaAzn, 2) : null;

        // Bill of sale
        $billTotal = $billOfSale->count();
        $billWithSale = $billOfSale->where('has_bill_of_sale', true)->count();
        $billOfSalePct = $billTotal > 0 ? round($billWithSale / $billTotal * 100, 1) : null;

        // Area ranges from config
        $ranges = config("scraper.area_ranges.{$propertyType}", []);
        $isLand = $propertyType === 'land';

        $areaRanges = collect($ranges)->map(function ($range) use ($listings) {
            $inRange = $listings->filter(
                fn ($l) => $l->area !== null
                    && $l->area >= $range['min']
                    && $l->area <= $range['max']
            );

            $rangePrices = $inRange->pluck('price')->filter()->values();
            $rangeAzn = $inRange->map(fn ($l) => $l->extras['original_price_azn'] ?? null)->filter()->values();
            $rangeAreas = $inRange->pluck('area')->filter()->values();
            $rangeSqmPrices = $inRange->pluck('price_per_sqm')->filter()->values();

            $rangeTotalPrice = $rangePrices->sum();
            $rangeTotalArea = $rangeAreas->sum();
            $rangeWeightedSqm = ($rangeTotalArea > 0) ? round($rangeTotalPrice / $rangeTotalArea, 2) : null;
            $rangeWeightedSqmAzn = ($rangeTotalArea > 0 && $rangeAzn->sum() > 0)
                ? round($rangeAzn->sum() / $rangeTotalArea, 2)
                : null;

            $rangeAznSqmPrices = $inRange->map(function ($l) {
                $azn = $l->extras['original_price_azn'] ?? null;
                $area = $l->area;

                return ($azn && $area && $area > 0) ? round((float) $azn / (float) $area, 2) : null;
            })->filter()->values();

            $result = [
                'range' => $range['label'],
                'count' => $inRange->count(),
                'min_price_per_sqm_usd' => $rangeSqmPrices->count() ? round($rangeSqmPrices->min(), 2) : null,
                'max_price_per_sqm_usd' => $rangeSqmPrices->count() ? round($rangeSqmPrices->max(), 2) : null,
                'weighted_avg_price_per_sqm_usd' => $rangeWeightedSqm,
                'min_price_per_sqm_azn' => $rangeAznSqmPrices->count() ? round($rangeAznSqmPrices->min(), 2) : null,
                'max_price_per_sqm_azn' => $rangeAznSqmPrices->count() ? round($rangeAznSqmPrices->max(), 2) : null,
                'weighted_avg_price_per_sqm_azn' => $rangeWeightedSqmAzn,
            ];

            return $result;
        })->values()->toArray();

        $aznSqmPrices = $listings->map(function ($l) {
            $azn = $l->extras['original_price_azn'] ?? null;
            $area = $l->area;

            return ($azn && $area && $area > 0) ? (float) $azn / (float) $area : null;
        })->filter()->values();

        // Median price AZN
        $sortedAznPrices = $aznPrices->sort()->values();
        $aznCount = $sortedAznPrices->count();
        $medianAzn = 0;
        if ($aznCount > 0) {
            $aznMid = (int) floor($aznCount / 2);
            $medianAzn = $aznCount % 2 === 0
                ? ($sortedAznPrices[$aznMid - 1] + $sortedAznPrices[$aznMid]) / 2
                : $sortedAznPrices[$aznMid];
        }

        // Median price/sqm AZN
        $sortedAznSqmPrices = $aznSqmPrices->sort()->values();
        $aznSqmCount = $sortedAznSqmPrices->count();
        $medianAznSqm = 0;
        if ($aznSqmCount > 0) {
            $aznSqmMid = (int) floor($aznSqmCount / 2);
            $medianAznSqm = $aznSqmCount % 2 === 0
                ? ($sortedAznSqmPrices[$aznSqmMid - 1] + $sortedAznSqmPrices[$aznSqmMid]) / 2
                : $sortedAznSqmPrices[$aznSqmMid];
        }

        $stats = [
            'count' => $listings->count(),
            'min_price_usd' => $prices->count() ? (int) round($prices->min()) : null,
            'max_price_usd' => $prices->count() ? (int) round($prices->max()) : null,
            'avg_price_usd' => $prices->count() ? (int) round($prices->avg()) : null,
            'median_price_usd' => $count > 0 ? (int) round($median) : null,
            'min_price_azn' => $aznPrices->count() ? (int) round($aznPrices->min()) : null,
            'max_price_azn' => $aznPrices->count() ? (int) round($aznPrices->max()) : null,
            'avg_price_azn' => $aznPrices->count() ? (int) round($aznPrices->avg()) : null,
            'median_price_azn' => $aznCount > 0 ? (int) round($medianAzn) : null,
            'weighted_avg_price_per_sqm_usd' => $weightedAvgPriceSqm,
            'weighted_avg_price_per_sqm_azn' => $weightedAvgPriceSqmAzn,
            'min_price_per_sqm_usd' => $sqmCount > 0 ? round($sortedSqmPrices->min(), 2) : null,
            'max_price_per_sqm_usd' => $sqmCount > 0 ? round($sortedSqmPrices->max(), 2) : null,
            'median_price_per_sqm_usd' => $sqmCount > 0 ? round($medianSqm, 2) : null,
            'min_price_per_sqm_azn' => $aznSqmPrices->count() ? round($aznSqmPrices->min(), 2) : null,
            'max_price_per_sqm_azn' => $aznSqmPrices->count() ? round($aznSqmPrices->max(), 2) : null,
            'median_price_per_sqm_azn' => $aznSqmCount > 0 ? round($medianAznSqm, 2) : null,
            'min_area_sqm' => $areas->count() ? round($areas->min(), 1) : null,
            'max_area_sqm' => $areas->count() ? round($areas->max(), 1) : null,
            'avg_area_sqm' => $areas->count() ? round($areas->avg(), 1) : null,
            'bill_of_sale_pct' => $billOfSalePct,
            'area_ranges' => $areaRanges,
        ];

        $notes = [];

        if ($weightedAvgPriceSqmAzn && $aznSqmPrices->count() > 0) {
            $minAznSqm = $aznSqmPrices->min();
            if ($minAznSqm < $weightedAvgPriceSqmAzn / 3) {
                $notes[] = 'Min price/sqm is significantly below weighted average — likely caused by large bulk plots, seller data entry errors (per-unit price instead of total), or listings from adjacent areas included in the location filter. Weighted average is the more reliable benchmark.';
            }
        }

        if ($sqmCount > 0 && $weightedAvgPriceSqm) {
            $minUsdSqm = $sortedSqmPrices->min();
            if ($minUsdSqm < $weightedAvgPriceSqm / 3) {
                $notes[] = 'Wide price/sqm spread detected. Min/max figures should be treated with caution — use weighted average as the primary reference point.';
            }
        }

        if (count($notes) > 0) {
            $stats['data_quality_notes'] = $notes;
        }

        return $stats;
    }

    private function defaultSystemPrompt(): string
    {
        $registry = app(MarketRegistry::class);
        $marketConfig = $registry->fromSlug($this->market);
        $marketLabel = $this->market === 'global'
            ? 'Caucasus Region (all markets)'
            : ($marketConfig['label'] ?? ucfirst($this->market));
        $sourcePrefixes = $marketConfig ? ($marketConfig['source_prefixes'] ?? ['listam']) : ['listam'];
        $primarySource = $this->market === 'global' ? 'all configured sources' : ($sourcePrefixes[0] ?? 'listam');

        return <<<PROMPT
You are a real estate market intelligence assistant for the {$marketLabel} real estate market.

You have access to tools that query a live database of real property listings scraped from {$primarySource}. Every figure you provide must come from calling one of these tools - never from your general knowledge.

Rules you must follow without exception:
- Never invent, estimate, or guess a price, count, or market figure.
- If a question cannot be answered by calling one of your tools, say so clearly and explain what data you do have access to.
- Never compare {$marketLabel} data to other cities unless the user provides those figures themselves.
- Always state which category and district a figure applies to.
- When calling findComparables, never ask the user for parameters they did not provide. Only use the parameters the user gave you - all parameters except source are optional and can be omitted.
- When a user asks about a property type, always call getCategoryList first if you are unsure which source key to use, then call the appropriate tool with exactly one source key. Never call the same tool multiple times with different source keys in one response.
- If getCategoryList returns an empty categories list or a "no data available" message, immediately inform the user that no data is available for this market yet and stop - do not call any other tools.
- When providing download links, always format them as markdown links: [Download File Name](url) - never as bare URLs.
- When the user asks about price reductions or falling prices, call getPriceChanges with the relevant source key.
- When the user asks about rental yield or return on investment, call getYieldEstimate with pair='residential' for apartments or pair='commercial' for commercial property.
- When the user searches by description or natural language property features, call semanticSearch with their query. If it returns no indexed data, fall back to findComparables and explain the limitation.
- When getLocationStats returns results with data_source containing 'exact', present the data as precise development-specific data scraped directly from bina.az. When data_source contains 'keyword search', clarify these are nearby properties whose descriptions mention the location. Always state which districts appear in the data. If count is below 5, warn that data is insufficient for reliable conclusions.
- Always present prices in AZN as primary with USD in parentheses — e.g. "125,000 AZN ($73,500 USD)". Apply this format consistently across all tools when responding about Baku market data.
- Never calculate or estimate AZN prices by converting from USD. AZN prices must only come from tool calls that read original_price_azn directly from the database. If asked about specific listing prices in AZN, call getLocationStats or findComparables to get fresh data — never derive AZN from USD figures in the conversation context.
- When presenting price per sqm statistics, always emphasise the weighted average as the most reliable metric. Explicitly warn that min and max prices can be misleading due to outliers — large plots, data entry errors by sellers, or listings from adjacent areas that bina.az includes under the same location filter.
- When getLocationStats returns data for a named development (Sea Breeze etc), always note that bina.az location filters cover a broader zone — some listings may be nearby but not inside the development. Recommend verifying specific listings directly on bina.az using the listing URL.
- When the difference between min price/sqm and weighted average price/sqm is more than 3x, flag this as a sign of outliers and explain the likely causes without inventing specifics — use findComparables to investigate suspicious listings if the user wants details.

When you find relevant data via a tool, present it clearly and concisely. Always cite the listing count so the user knows how much data the figure is based on.

You are a data reporting tool. The professional judgment about what the data means belongs to the analyst you are assisting.
PROMPT;
    }
}
