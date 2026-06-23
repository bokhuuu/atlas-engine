<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Exports\Concerns;

/**
 * Shared median calculation for market report sheets.
 *
 * Extracted after the same sort-and-take-the-middle logic was found
 * written out separately in MarketOverviewSheet and DistrictAnalysisSheet,
 * used only for overall Price - never for Price/sqm, and never in the
 * other 4 sheets, despite those having the exact same averaging
 * vulnerability (a handful of genuinely real but extreme listings
 * skewing a simple mean). Confirmed necessary on Screen 0's Commercial
 * Sale category, where mean ($4,348/sqm) sat well above real market
 * reports (~$2,000-3,000/sqm) due to a few extreme-but-real listings,
 * not a scraping bug - same fix now being applied here uniformly.
 *
 * Caller is expected to filter out nulls/empty values and check for
 * an empty array before calling - this method assumes a non-empty
 * array of numeric values, matching the existing call pattern already
 * used throughout these sheet classes (e.g. count($prices) ? $this->median(...) : 'N/A').
 */
trait CalculatesMedian
{
    protected function median(array $values): float
    {
        $values = array_values($values);
        sort($values);

        $count = count($values);
        $mid = (int) floor($count / 2);

        return $count % 2 === 0
            ? round(($values[$mid - 1] + $values[$mid]) / 2, 2)
            : round($values[$mid], 2);
    }
}
