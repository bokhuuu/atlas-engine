<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Exports;

use App\Modules\Scraper\Browser\ScreenshotService;
use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Exports\Concerns\CalculatesMedian;
use App\Modules\Scraper\Exports\Contracts\ExporterInterface;
use App\Services\MarketRegistry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Produces a market research workbook from scraped listing data.
 * Every sheet sits on one principle: describe what the source shows,
 * sliced by fields that already exist in the data - no editorial
 * bracketing, no imposed categories, no analytical judgment.
 *
 *   Sheet 1 - All Listings         : full raw dataset for analyst use
 *   Sheet 2 - Market Overview      : aggregated headline statistics
 *   Sheet 3 - District Analysis    : pricing and supply by district
 *   Sheet 4 - Room/Building Type   : product mix by room count (apartments/houses)
 *                                    or building type (commercial); omitted for land
 *   Sheet 5 - Price Distribution   : market segmentation by price bracket
 *
 * Sheet 4 variant is determined from the source profile name pattern -
 * market-portable, works for any prefix (listam_, binaz_, myhome_).
 */
class RealEstateMarketReport implements ExporterInterface
{
    /**
     * Generate the market research report workbook.
     *
     * Sheet 4 variant is determined from the source profile name pattern -
     * market-portable, works for any prefix (listam_, binaz_, myhome_).
     * 'room'     → apartments, houses, new developments
     * 'building' → commercial sale and rent
     * null       → land (4 sheets only, Sheet 4 omitted)
     */
    public function export(array $data, ScraperProfileInterface $profile): string
    {
        $path = $this->buildPath($profile->getName());
        $deduplicated = self::deduplicateForAggregation($data);
        $source = $profile->getName();

        $sheet4Type = match (true) {
            str_contains($source, '_apartments_') => 'room',
            str_contains($source, '_houses_') => 'room',
            str_contains($source, '_new_developments_') => 'room',
            str_contains($source, '_commercial_') => 'building',
            str_contains($source, '_land_') => null,
            default => 'room',
        };

        Excel::store(new class($data, $deduplicated, $sheet4Type) implements WithMultipleSheets
        {
            public function __construct(
                private readonly array $rawData,
                private readonly array $deduplicatedData,
                private readonly ?string $sheet4Type,
            ) {}

            public function sheets(): array
            {
                $sheets = [
                    new AllListingsSheet($this->rawData),
                    new MarketOverviewSheet($this->deduplicatedData),
                    new DistrictAnalysisSheet($this->deduplicatedData),
                ];

                if ($this->sheet4Type === 'room') {
                    $sheets[] = new RoomTypeAnalysisSheet($this->deduplicatedData);
                } elseif ($this->sheet4Type === 'building') {
                    $sheets[] = new BuildingTypeAnalysisSheet($this->deduplicatedData);
                }

                $sheets[] = new PriceDistributionSheet($this->deduplicatedData);

                return $sheets;
            }
        }, $path, 'local');

        return Storage::disk('local')->path($path);
    }

    /**
     * The file extension this exporter produces.
     */
    public function extension(): string
    {
        return 'xlsx';
    }

    /**
     * Collapses duplicate groups into one averaged row before any
     * aggregate sheet computes from this data - a flagged duplicate
     * pair counts as one data point, not two. AllListingsSheet uses
     * the raw $data directly instead, since it's the client-facing
     * full dataset - every real row stays visible there.
     */
    public static function deduplicateForAggregation(array $data): array
    {
        $grouped = [];

        foreach ($data as $row) {
            $key = $row['duplicate_group_id'] ?? $row['id'];
            $grouped[$key][] = $row;
        }

        return array_values(array_map(function ($group) {
            if (count($group) === 1) {
                return $group[0];
            }

            $averaged = $group[0];

            foreach (['price', 'price_per_sqm', 'area'] as $field) {
                $values = array_filter(array_column($group, $field));
                $averaged[$field] = count($values) ? array_sum($values) / count($values) : null;
            }

            return $averaged;
        }, $grouped));
    }

    /**
     * Fixed filename - this is a client deliverable, not a rolling export.
     * Date suffix ensures each run produces a distinct versioned file.
     */
    private function buildPath(string $source = ''): string
    {
        $dir = config('scraper.export_path');
        $date = now()->format('d_m_Y');
        $suffix = $source ? "_{$source}" : '';

        return "{$dir}/real_estate_market_report{$suffix}_{$date}.xlsx";
    }
}

/**
 * Sheet 1 - Client-facing raw dataset.
 * Contains only fields relevant to the client.
 * Ordered for readability: location → price → property details → lifecycle.
 *
 * days_on_market and status are computed on export -
 * they do not exist as DB columns.
 */
class AllListingsSheet implements FromArray, WithHeadings, WithTitle
{
    private array $clientColumns = [
        'url',
        'listing_type',
        'property_type',
        'district',
        'address',
        'price',
        'previous_price',
        'price_change_pct',
        'price_changed_at',
        'currency',
        'price_per_sqm',
        'area',
        'rooms',
        'bathrooms',
        'floor',
        'total_floors',
        'ceiling_height',
        'building_type',
        'condition',
        'is_new_building',
        'has_bill_of_sale',
        'agency_name',
        'listing_date',
        'scraped_at',
        'duplicate_group_id',
        'last_seen_at',
        'disappeared_at',
        'days_on_market',
        'status',
        'screenshot_url',
    ];

    public function __construct(private readonly array $data) {}

    public function title(): string
    {
        return 'All Listings';
    }

    public function headings(): array
    {
        return $this->clientColumns;
    }

    public function array(): array
    {
        $staleDays = (int) config('scraper.stale_listing_days', 90);

        return array_map(function ($row) use ($staleDays) {
            $start = $row['listing_date'] ?? $row['scraped_at'] ?? null;
            $end = $row['disappeared_at'] ?? now()->toDateTimeString();

            $row['days_on_market'] = $start
                ? (int) Carbon::parse((string) $start)->diffInDays(Carbon::parse((string) $end))
                : null;

            if (! empty($row['disappeared_at'])) {
                $row['status'] = 'Disappeared';
            } elseif ($start && Carbon::parse($start)->diffInDays(now()) >= $staleDays) {
                $row['status'] = "Stale ({$staleDays}+ days)";
            } else {
                $row['status'] = 'Active';
            }

            if (! empty($row['screenshot_url'])) {
                $row['screenshot_url'] = ScreenshotService::url(
                    $row['screenshot_url']
                );
            }

            return array_map(fn ($col) => $row[$col] ?? null, $this->clientColumns);
        }, $this->data);
    }
}

/**
 * Sheet 2 - Executive headline statistics.
 * Single-page summary for decision makers.
 * Covers total supply, pricing averages, market composition.
 */
class MarketOverviewSheet implements FromArray, WithHeadings, WithTitle
{
    use CalculatesMedian;

    public function __construct(private readonly array $data) {}

    public function title(): string
    {
        return 'Market Overview';
    }

    public function headings(): array
    {
        return ['Metric', 'Value'];
    }

    public function array(): array
    {
        $prices = array_filter(array_column($this->data, 'price'));
        $sqmPrices = array_filter(array_column($this->data, 'price_per_sqm'));
        $areas = array_filter(array_column($this->data, 'area'));
        $districts = array_filter(array_column($this->data, 'district'));
        $agencies = array_filter(array_column($this->data, 'agency_name'));

        $avgPrice = count($prices) ? round(array_sum($prices) / count($prices), 2) : 'N/A';
        $avgSqm = count($sqmPrices) ? round(array_sum($sqmPrices) / count($sqmPrices), 2) : 'N/A';
        $avgArea = count($areas) ? round(array_sum($areas) / count($areas), 2) : 'N/A';
        $medianPrice = count($prices) ? $this->median(array_values($prices)) : 'N/A';
        $medianSqm = count($sqmPrices) ? $this->median(array_values($sqmPrices)) : 'N/A';

        $newBuildingCount = count(array_filter(
            array_column($this->data, 'is_new_building'),
            fn ($v) => $v === true || $v === 1
        ));

        return [
            ['Report Date',                now()->format('d/m/Y H:i')],
            ['Data Source', $this->resolveDataSource().($this->data[0]['property_type'] ?? 'Real Estate')],
            ['', ''],
            ['--- SUPPLY ---',             ''],
            ['Total Active Listings',      count($this->data)],
            ['Districts Covered',          count(array_unique($districts))],
            ['', ''],
            ['--- PRICING ---',            ''],
            ['Average Price (USD)',        $avgPrice],
            ['Median Price (USD)',         $medianPrice],
            ['Average Price/sqm (USD)',    $avgSqm],
            ['Median Price/sqm (USD)',     $medianSqm],
            ['Average Area (sqm)',         $avgArea],
            ['Min Price (USD)',            count($prices) ? min($prices) : 'N/A'],
            ['Max Price (USD)',            count($prices) ? max($prices) : 'N/A'],
            ['', ''],
            ['--- MARKET COMPOSITION ---', ''],
            ['New Construction Listings',  $newBuildingCount],
            ['New Construction %',         count($this->data) > 0 ? round($newBuildingCount / count($this->data) * 100, 1).'%' : 'N/A'],
            ['Agency Listings',            count($agencies)],
            ['Owner Listings',             count($this->data) - count($agencies)],
            ['', ''],
            ['--- DOCUMENT STATUS ---',    ''],
            ['With Bill of Sale',          count(array_filter(array_column($this->data, 'has_bill_of_sale')))],
            ['Without Bill of Sale',       count(array_filter(array_column($this->data, 'has_bill_of_sale'), fn ($v) => $v === false))],
            ['Bill of Sale Unknown',       count(array_filter(array_column($this->data, 'has_bill_of_sale'), fn ($v) => $v === null))],
        ];
    }

    /**
     * Derives the data source label from the first listing's source profile name.
     * Returns a market-aware string like "listam - Yerevan " or "binaz - Baku ".
     */
    private function resolveDataSource(): string
    {
        $source = $this->data[0]['source_profile_name'] ?? '';
        $prefix = explode('_', $source)[0];
        $registry = app(MarketRegistry::class);
        $marketSlug = $registry->fromSourcePrefix($prefix);
        $marketConfig = $marketSlug ? $registry->fromSlug($marketSlug) : null;
        $label = $marketConfig['label'] ?? ucfirst($prefix);

        return "{$prefix} - {$label} ";
    }
}

/**
 * Sheet 3 - District-level supply and pricing analysis.
 * Sorted by listing count descending - most active districts first.
 * Includes new construction split per district for development potential assessment.
 */
class DistrictAnalysisSheet implements FromArray, WithHeadings, WithTitle
{
    use CalculatesMedian;

    public function __construct(private readonly array $data) {}

    public function title(): string
    {
        return 'District Analysis';
    }

    public function headings(): array
    {
        return [
            'District',
            'Listings',
            '% of Market',
            'Avg Price (USD)',
            'Median Price (USD)',
            'Avg Price/sqm (USD)',
            'Median Price/sqm (USD)',
            'Avg Area (sqm)',
            'New Construction',
            'New Construction %',
        ];
    }

    public function array(): array
    {
        $grouped = [];
        $total = count($this->data);

        foreach ($this->data as $row) {
            $district = $row['district'] ?? 'Unknown';
            $grouped[$district][] = $row;
        }

        $rows = [];

        foreach ($grouped as $district => $listings) {
            $prices = array_filter(array_column($listings, 'price'));
            $sqmPrices = array_filter(array_column($listings, 'price_per_sqm'));
            $areas = array_filter(array_column($listings, 'area'));
            $newCount = count(array_filter(
                array_column($listings, 'is_new_building'),
                fn ($v) => $v === true || $v === 1
            ));

            $median = count($prices) ? $this->median($prices) : null;
            $medianSqm = count($sqmPrices) ? $this->median($sqmPrices) : null;

            $count = count($listings);

            $rows[] = [
                $district,
                $count,
                round($count / $total * 100, 1).'%',
                count($prices) ? round(array_sum($prices) / count($prices), 2) : 'N/A',
                $median ?? 'N/A',
                count($sqmPrices) ? round(array_sum($sqmPrices) / count($sqmPrices), 2) : 'N/A',
                $medianSqm ?? 'N/A',
                count($areas) ? round(array_sum($areas) / count($areas), 2) : 'N/A',
                $newCount,
                $count > 0 ? round($newCount / $count * 100, 1).'%' : 'N/A',
            ];
        }

        usort($rows, fn ($a, $b) => $b[1] <=> $a[1]);

        return $rows;
    }
}

/**
 * Sheet 4 - Product mix and pricing by bedroom count.
 * Answers: what is the dominant product type and what does each command?
 * Critical for understanding market demand segments.
 */
class RoomTypeAnalysisSheet implements FromArray, WithHeadings, WithTitle
{
    use CalculatesMedian;

    public function __construct(private readonly array $data) {}

    public function title(): string
    {
        return 'Room Type Analysis';
    }

    public function headings(): array
    {
        return [
            'Room Type',
            'Listings',
            '% of Market',
            'Avg Price (USD)',
            'Avg Price/sqm (USD)',
            'Median Price/sqm (USD)',
            'Avg Area (sqm)',
            'New Construction %',
        ];
    }

    public function array(): array
    {
        $grouped = [];
        $total = count($this->data);

        foreach ($this->data as $row) {
            $rooms = $row['rooms'] ?? null;
            $label = $rooms !== null ? "{$rooms} Room".($rooms > 1 ? 's' : '') : 'Unknown';
            $grouped[$label][] = $row;
        }

        ksort($grouped);

        $rows = [];

        foreach ($grouped as $label => $listings) {
            $prices = array_filter(array_column($listings, 'price'));
            $sqmPrices = array_filter(array_column($listings, 'price_per_sqm'));
            $medianSqm = count($sqmPrices) ? $this->median($sqmPrices) : null;
            $areas = array_filter(array_column($listings, 'area'));
            $newCount = count(array_filter(
                array_column($listings, 'is_new_building'),
                fn ($v) => $v === true || $v === 1
            ));
            $count = count($listings);

            $rows[] = [
                $label,
                $count,
                round($count / $total * 100, 1).'%',
                count($prices) ? round(array_sum($prices) / count($prices), 2) : 'N/A',
                count($sqmPrices) ? round(array_sum($sqmPrices) / count($sqmPrices), 2) : 'N/A',
                $medianSqm ?? 'N/A',
                count($areas) ? round(array_sum($areas) / count($areas), 2) : 'N/A',
                $count > 0 ? round($newCount / $count * 100, 1).'%' : 'N/A',
            ];
        }

        return $rows;
    }
}

/**
 * Groups listings by building type (monolith, panels, stone, etc.)
 * and shows count, average and median price/sqm per type.
 *
 * Used for commercial and other non-residential categories where
 * room count is not meaningful. Language-agnostic - groups by
 * whatever value is stored in building_type, works for any market.
 */
class BuildingTypeAnalysisSheet implements FromArray, WithColumnWidths, WithStyles, WithTitle
{
    use CalculatesMedian;

    public function __construct(private readonly array $listings) {}

    public function title(): string
    {
        return 'Building Type Analysis';
    }

    public function array(): array
    {
        $header = [
            'Building Type',
            'Listings',
            '% of Market',
            'Avg Price (USD)',
            'Avg Price/sqm (USD)',
            'Median Price/sqm (USD)',
            'Avg Area (sqm)',
        ];

        $active = array_filter(
            $this->listings,
            fn ($r) => ($r['status'] ?? '') !== 'Disappeared',
        );

        $total = count($active);

        $grouped = [];
        foreach ($active as $row) {
            $type = $row['building_type'] ?? 'Unknown';
            $grouped[$type][] = $row;
        }

        ksort($grouped);

        $rows = [$header];
        foreach ($grouped as $type => $items) {
            $count = count($items);
            $prices = array_filter(array_column($items, 'price'));
            $psqm = array_filter(array_column($items, 'price_per_sqm'));

            $rows[] = [
                ucfirst($type),
                $count,
                $total > 0 ? round(($count / $total) * 100, 1).'%' : '0%',
                count($prices) ? '$'.number_format(array_sum($prices) / count($prices), 0) : '-',
                count($psqm) ? '$'.number_format(array_sum($psqm) / count($psqm), 0) : '-',
                count($psqm) ? '$'.number_format($this->median($psqm), 0) : '-',
                count($items) ? round(array_sum(array_filter(array_column($items, 'area'))) / count($items), 1) : '-',
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F3864']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 22, 'B' => 12, 'C' => 16, 'D' => 18, 'E' => 22, 'F' => 24, 'G' => 16];
    }
}

/**
 * Sheet 5 - Price distribution by bracket.
 * Shows market segmentation - affordable, mid-market, premium, luxury.
 * USD brackets calibrated to Yerevan market range.
 */
class PriceDistributionSheet implements FromArray, WithHeadings, WithTitle
{
    use CalculatesMedian;

    public function __construct(private readonly array $data) {}

    public function title(): string
    {
        return 'Price Distribution';
    }

    public function headings(): array
    {
        return ['Price Bracket (USD)', 'Listings', '% of Market', 'Avg Area (sqm)', 'Avg Price/sqm (USD)', 'Median Price/sqm (USD)'];
    }

    public function array(): array
    {
        $dealType = $this->data[0]['listing_type'] ?? 'sale';
        $brackets = $dealType === 'rent' ? $this->rentBrackets() : $this->saleBrackets();
        $buckets = array_fill_keys(array_keys($brackets), []);

        foreach ($this->data as $row) {
            $price = $row['price'] ?? null;
            if (! $price) {
                continue;
            }

            foreach ($brackets as $label => [$min, $max]) {
                if ($price >= $min && $price < $max) {
                    $buckets[$label][] = $row;
                    break;
                }
            }
        }

        $total = count($this->data);
        $rows = [];

        foreach ($buckets as $label => $listings) {
            $count = count($listings);
            $areas = array_filter(array_column($listings, 'area'));
            $sqmPrices = array_filter(array_column($listings, 'price_per_sqm'));
            $medianSqm = count($sqmPrices) ? $this->median($sqmPrices) : null;

            $rows[] = [
                $label,
                $count,
                $total > 0 ? round($count / $total * 100, 1).'%' : '0%',
                count($areas) ? round(array_sum($areas) / count($areas), 2) : 'N/A',
                count($sqmPrices) ? round(array_sum($sqmPrices) / count($sqmPrices), 2) : 'N/A',
                $medianSqm ?? 'N/A',
            ];
        }

        return $rows;
    }

    private function saleBrackets(): array
    {
        return [
            'Under $50,000' => [0, 50000],
            '$50,000 – $100,000' => [50000, 100000],
            '$100,000 – $150,000' => [100000, 150000],
            '$150,000 – $200,000' => [150000, 200000],
            '$200,000 – $300,000' => [200000, 300000],
            '$300,000 – $500,000' => [300000, 500000],
            'Above $500,000' => [500000, PHP_INT_MAX],
        ];
    }

    private function rentBrackets(): array
    {
        return [
            'Under $300' => [0, 300],
            '$300 – $500' => [300, 500],
            '$500 – $800' => [500, 800],
            '$800 – $1,200' => [800, 1200],
            '$1,200 – $2,000' => [1200, 2000],
            '$2,000 – $3,000' => [2000, 3000],
            'Above $3,000' => [3000, PHP_INT_MAX],
        ];
    }
}
