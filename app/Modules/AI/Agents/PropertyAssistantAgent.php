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

    #[Tool('Get pricing statistics for a named location or development — e.g. "Sea Breeze", "White City", "Badamdar". Uses semantic search to find listings mentioning the location. source parameter is optional — omit to search across all categories.')]
    public function getLocationStats(string $location_name, ?string $source = null): string
    {
        $service = app(EmbeddingService::class);

        if (! $service->hasDocuments()) {
            return json_encode([
                'message' => 'No listing text has been indexed for semantic search yet.',
            ]);
        }

        $results = $service->search($location_name, 50);

        if ($results->isEmpty()) {
            return json_encode(['message' => "No listings found mentioning '{$location_name}'."]);
        }

        $ids = $results->pluck('source_id')->filter()->values()->toArray();

        $query = Listing::whereIn('id', $ids)->whereNotNull('price');

        if ($source !== null) {
            $query->where('source_profile_name', $source);
        }

        $listings = $query->get();

        if ($listings->isEmpty()) {
            return json_encode(['message' => "No listings with price data found for '{$location_name}'."]);
        }

        return json_encode([
            'location' => $location_name,
            'source_filter' => $source,
            'currency' => 'USD',
            'stats' => $this->calculateListingStats($listings),
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

        return json_encode([
            'district' => $district,
            'source_filter' => $source,
            'currency' => 'USD',
            'stats' => $this->calculateListingStats($listings),
        ]);
    }

    private function calculateListingStats(Collection $listings): array
    {
        $prices = $listings->pluck('price')->filter()->values();
        $areas = $listings->pluck('area')->filter()->values();
        $billOfSale = $listings->whereNotNull('has_bill_of_sale');

        $sortedPrices = $prices->sort()->values();
        $count = $sortedPrices->count();
        $median = 0;
        if ($count > 0) {
            $mid = (int) floor($count / 2);
            $median = $count % 2 === 0
                ? ($sortedPrices[$mid - 1] + $sortedPrices[$mid]) / 2
                : $sortedPrices[$mid];
        }

        $totalPrice = $prices->sum();
        $totalArea = $areas->sum();
        $weightedAvgPriceSqm = ($totalArea > 0) ? round($totalPrice / $totalArea, 2) : null;

        $billTotal = $billOfSale->count();
        $billWithSale = $billOfSale->where('has_bill_of_sale', true)->count();
        $billOfSalePct = $billTotal > 0 ? round($billWithSale / $billTotal * 100, 1) : null;

        return [
            'count' => $listings->count(),
            'min_price_usd' => $prices->count() ? (int) round($prices->min()) : null,
            'max_price_usd' => $prices->count() ? (int) round($prices->max()) : null,
            'avg_price_usd' => $prices->count() ? (int) round($prices->avg()) : null,
            'median_price_usd' => $count > 0 ? (int) round($median) : null,
            'weighted_avg_price_per_sqm_usd' => $weightedAvgPriceSqm,
            'min_area_sqm' => $areas->count() ? round($areas->min(), 1) : null,
            'max_area_sqm' => $areas->count() ? round($areas->max(), 1) : null,
            'avg_area_sqm' => $areas->count() ? round($areas->avg(), 1) : null,
            'bill_of_sale_pct' => $billOfSalePct,
        ];
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

When you find relevant data via a tool, present it clearly and concisely. Always cite the listing count so the user knows how much data the figure is based on.

You are a data reporting tool. The professional judgment about what the data means belongs to the analyst you are assisting.
PROMPT;
    }
}
