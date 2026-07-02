<?php

declare(strict_types=1);

namespace App\Modules\AI\Services;

use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Support\CategoryLabels;
use Illuminate\Support\Facades\Cache;

/**
 * Generates a one-paragraph market summary for a given scraper source.
 *
 * Pulls real stats from ListingRepository, builds a data-rich prompt,
 * and calls TextGenerationService. Cached per source for 6 hours so
 * repeated page loads don't hit the AI provider.
 */
class MarketSummaryService
{
    public function __construct(
        private readonly ListingRepository $listingRepository,
        private readonly TextGenerationService $textGenerator,
    ) {}

    /**
     * Generate and cache a market summary paragraph for the given source.
     * Returns null if no data exists yet for this source.
     */
    public function summarize(string $source): ?string
    {
        $cacheKey = "ai:market_summary:{$source}";
        $ttl = (int) config('ai.summary_cache_ttl', 21600);

        return Cache::remember($cacheKey, $ttl, function () use ($source) {
            return $this->generate($source);
        });
    }

    private function generate(string $source): ?string
    {
        $label = CategoryLabels::forSource($source);
        $totalListings = $this->listingRepository->countDistinctBySource($source);

        if ($totalListings === 0) {
            return null;
        }

        $medianCitywide = $this->listingRepository->getMedianPricePerSqm($source);
        $districtStats = $this->listingRepository->getDistrictPriceStats($source);
        $districtMedians = $this->listingRepository->getMedianPricePerSqmByDistrict($source);

        $topDistrict = $districtStats->first();
        $bottomDistrict = $districtStats->last();

        $topDistrictName = $topDistrict?->district ?? 'N/A';
        $topDistrictAvg = $topDistrict ? '$'.number_format((float) $topDistrict->avg_price_per_sqm) : 'N/A';
        $bottomDistrictName = $bottomDistrict?->district ?? 'N/A';
        $bottomDistrictAvg = $bottomDistrict ? '$'.number_format((float) $bottomDistrict->avg_price_per_sqm) : 'N/A';
        $medianStr = $medianCitywide ? '$'.number_format($medianCitywide) : 'N/A';

        $districtLines = $districtStats->take(5)->map(function ($row) use ($districtMedians) {
            $median = $districtMedians->get($row->district)?->median_price_per_sqm;
            $medianFormatted = $median ? '$'.number_format((float) $median).' median' : 'no median';

            return "- {$row->district}: {$row->listing_count} listings, "
                .'$'.number_format((float) $row->avg_price_per_sqm).' avg/sqm, '
                .$medianFormatted.'/sqm';
        })->implode("\n");

        $prompt = <<<PROMPT
You are a real estate market analyst writing a concise market briefing for the Yerevan market.

Write exactly one paragraph (4-6 sentences) summarizing the current state of the {$label} market in Yerevan, Armenia based on the following scraped data from list.am:

- Total listings: {$totalListings}
- Citywide median price/sqm: {$medianStr}/sqm
- Highest-priced district: {$topDistrictName} at {$topDistrictAvg}/sqm average
- Lowest-priced district: {$bottomDistrictName} at {$bottomDistrictAvg}/sqm average
- Top 5 districts by listing count:
{$districtLines}

Rules:
- Be factual and concise. Do not invent figures not in the data above.
- Write for a professional real estate audience.
- Do not use bullet points. One paragraph only.
- Do not start with "As of" or "Based on".
PROMPT;

        $systemPrompt = 'You are a professional real estate market analyst. '
            .'Write factual, concise market summaries based only on provided data. '
            .'Never invent statistics. Always write in one paragraph.';

        return $this->textGenerator->generate(
            prompt: $prompt,
            systemPrompt: $systemPrompt,
            userId: 'system',
        );
    }
}
