<?php

declare(strict_types=1);

namespace App\Modules\AI\Agents;

use App\Modules\AI\Services\PromptService;
use App\Modules\Scraper\Exports\ExportManager;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Support\CategoryLabels;
use LarAgent\Agent;
use LarAgent\Attributes\Tool;

class PropertyAssistantAgent extends Agent
{
    protected $name = 'PropertyAssistantAgent';

    protected $history = 'database';

    protected $tools = [];

    protected $mcpServers = [];

    public function __construct(string $sessionId)
    {
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

    #[Tool('Find real listings matching given criteria. All parameters except source are optional — call immediately with whatever the user provided, never ask for missing ones. Returns up to 10 real listings with prices.')]
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
        $labels = CategoryLabels::all();
        $categories = collect($labels)->map(fn ($label, $source) => [
            'source' => $source,
            'label' => $label,
            'deal_type' => str_contains($source, '_rent') ? 'rent' : 'sale',
        ])->values();

        return json_encode(['categories' => $categories]);
    }

    #[Tool('Export all listings for a category to an Excel file and return a download link. Use only when the user explicitly asks for an Excel export or file download.')]
    public function exportToExcel(string $source): string
    {
        $labels = CategoryLabels::all();
        if (! array_key_exists($source, $labels)) {
            return json_encode(['error' => 'Unknown category. Available: '.implode(', ', array_keys($labels))]);
        }
        try {
            $paths = app(ExportManager::class)->runForSource($source, onlySpreadsheets: true);
            if (empty($paths)) {
                return json_encode(['error' => 'No listings found for this category.']);
            }

            return json_encode([
                'message' => 'Export ready.',
                'download_url' => url('/admin/exports/'.$source),
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

    #[Tool('Get estimated rental yield per district for residential or commercial property. Use when the user asks about return on investment, rental yield, or income potential. Only available for apartments (residential) and commercial categories.')]
    public function getYieldEstimate(string $pair = 'residential'): string
    {
        $repo = app(ListingRepository::class);

        [$saleSource, $rentSource] = match ($pair) {
            'commercial' => ['listam_commercial_sale', 'listam_commercial_rent'],
            default => ['listam_apartments_sale', 'listam_apartments_rent'],
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

    private function defaultSystemPrompt(): string
    {
        return <<<'PROMPT'
You are a real estate market intelligence assistant for the Yerevan real estate market.

You have access to tools that query a live database of real property listings scraped from list.am. Every figure you provide must come from calling one of these tools — never from your general knowledge.

Rules you must follow without exception:
- Never invent, estimate, or guess a price, count, or market figure.
- If a question cannot be answered by calling one of your tools, say so clearly and explain what data you do have access to.
- Never compare Yerevan data to other cities unless the user provides those figures themselves.
- Always state which category and district a figure applies to.
- When calling findComparables, never ask the user for parameters they did not provide. Only use the parameters the user gave you — all parameters except source are optional and can be omitted.
- When a user asks about a property type, always call getCategoryList first if you are unsure which source key to use, then call the appropriate tool with exactly one source key. Never call the same tool multiple times with different source keys in one response.
- When providing download links, always format them as markdown links: [Download File Name](url) — never as bare URLs.
- When the user asks about price reductions or falling prices, call getPriceChanges with the relevant source key.
- When the user asks about rental yield or return on investment, call getYieldEstimate with pair='residential' for apartments or pair='commercial' for commercial property.

When you find relevant data via a tool, present it clearly and concisely. Always cite the listing count so the user knows how much data the figure is based on.

You are a data reporting tool. The professional judgment about what the data means belongs to the analyst you are assisting.
PROMPT;
    }
}
