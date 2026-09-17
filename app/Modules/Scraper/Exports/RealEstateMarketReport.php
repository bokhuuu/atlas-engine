<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Exports;

use App\Modules\Scraper\Browser\ScreenshotService;
use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Exports\Concerns\CalculatesMedian;
use App\Modules\Scraper\Exports\Contracts\ExporterInterface;
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
 *   Sheet 2 - Room Type Analysis   : product mix by room count (apartments/houses/new dev)
 *           - Building Type        : product mix by building type (commercial/offices)
 *           - Land Size Range      : size range breakdown with pricing (land)
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
     * 'building' → commercial, offices
     * null       → land (size range analysis sheet)
     */
    public function export(array $data, ScraperProfileInterface $profile): string
    {
        $path = $this->buildPath($profile->getName());
        $source = $profile->getName();

        $sheet4Type = match (true) {
            str_contains($source, '_apartments_') => 'room',
            str_contains($source, '_houses_') => 'room',
            str_contains($source, '_new_developments_') => 'room',
            str_contains($source, '_commercial_') => 'building',
            str_contains($source, '_offices_') => 'building',
            str_contains($source, '_land_') => null,
            default => 'room',
        };

        Excel::store(new class($data, $sheet4Type) implements WithMultipleSheets
        {
            public function __construct(
                private readonly array $rawData,
                private readonly ?string $sheet4Type,
            ) {}

            public function sheets(): array
            {
                $sheets = [new AllListingsSheet($this->rawData)];

                if ($this->sheet4Type === 'room') {
                    $sheets[] = new RoomTypeAnalysisSheet($this->rawData);
                } elseif ($this->sheet4Type === 'building') {
                    $sheets[] = new BuildingTypeAnalysisSheet($this->rawData);
                } elseif ($this->sheet4Type === null) {
                    $sheets[] = new LandSizeRangeSheet($this->rawData);
                }

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
     * pair counts as one data point, not two. All sheets including
     * AllListingsSheet use this deduplicated data.
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
        // Identity
        'url',
        'listing_type',
        'property_type',
        'district',
        'address',

        // Pricing
        'price',
        'original_price',
        'price_per_sqm',
        'area_sotka',
        'price_per_sotka_usd',
        'currency',

        // Property details
        'area',
        'rooms',
        'bathrooms',
        'floor',
        'total_floors',
        'ceiling_height',
        'building_type',
        'condition',
        'is_new_building',

        // Baku-specific
        'has_bill_of_sale',
        'has_mortgage',

        // Contact
        'phone',
        'agency_name',

        // Content
        'translated_description',

        // Dates & lifecycle
        'listing_date',
        'scraped_at',
        'previous_price',
        'price_change_pct',
        'price_changed_at',
        'last_seen_at',
        'disappeared_at',
        'days_on_market',
        'status',

        // Internal reference
        'duplicate_group_id',
        'screenshot_url',
    ];

    public function __construct(private readonly array $data)
    {
        $isLand = collect($data)->contains(
            fn ($row) => str_contains($row['source_profile_name'] ?? '', '_land_')
        );

        if (! $isLand) {
            $this->clientColumns = array_values(array_filter(
                $this->clientColumns,
                fn ($col) => ! in_array($col, ['area_sotka', 'price_per_sotka_usd'])
            ));
        }
    }

    public function title(): string
    {
        return 'All Listings';
    }

    public function headings(): array
    {
        return array_map(fn ($col) => match ($col) {
            'price' => 'price_usd',
            'price_per_sqm' => 'price_per_sqm_usd',
            default => $col,
        }, $this->clientColumns);
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

            return array_map(function ($col) use ($row) {
                $value = $row[$col] ?? null;

                if ($col === 'price' && $value !== null) {
                    return (int) round((float) $value);
                }

                if ($col === 'price_per_sqm' && $value !== null) {
                    return round((float) $value, 2);
                }

                if ($col === 'original_price') {
                    $azn = $row['extras']['original_price_gel'] ?? $row['extras']['original_price_azn'] ?? null;

                    return $azn !== null ? (int) round((float) $azn) : null;
                }

                if ($col === 'has_bill_of_sale') {
                    return $value === null ? null : ($value ? 'Yes' : 'No');
                }

                if ($col === 'has_mortgage') {
                    return $value === null ? null : ($value ? 'Yes' : 'No');
                }

                if ($col === 'area' && $value !== null) {
                    return round((float) $value, 1);
                }

                if ($col === 'area_sotka') {
                    $area = $row['area'] ?? null;

                    return $area !== null ? round((float) $area / 100, 2) : null;
                }

                if ($col === 'price_per_sotka_usd') {
                    $price = $row['price'] ?? null;
                    $area = $row['area'] ?? null;
                    if ($price && $area && $area > 0) {
                        return (int) round((float) $price / ((float) $area / 100));
                    }

                    return null;
                }

                return $value;
            }, $this->clientColumns);
        }, $this->data);
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
 * Sheet 2 (Land) - Size range breakdown with pricing analysis.
 * Directly answers Colliers' four questions for land categories:
 * 1. Total listing count
 * 2. Min/max area (sqm )
 * 3. Count by size range (4 buckets)
 * 4. Min/max/weighted avg price per sqm (AZN and USD)
 */
class LandSizeRangeSheet implements FromArray, WithColumnWidths, WithStyles, WithTitle
{
    use CalculatesMedian;

    public function __construct(private readonly array $listings) {}

    public function title(): string
    {
        return 'Land Size Analysis';
    }

    public function array(): array
    {
        $prices = array_filter(array_column($this->listings, 'price'));
        $areas = array_filter(array_column($this->listings, 'area'));
        $sqmPrices = array_filter(array_column($this->listings, 'price_per_sqm'));

        $aznPrices = array_filter(array_map(
            fn ($r) => $r['extras']['original_price_gel'] ?? $r['extras']['original_price_azn'] ?? null,
            $this->listings
        ));

        $aznSqmPrices = array_filter(array_map(function ($r) {
            $azn = $r['extras']['original_price_gel'] ?? $r['extras']['original_price_azn'] ?? null;
            $area = $r['area'] ?? null;

            return ($azn && $area && $area > 0) ? (float) $azn / (float) $area : null;
        }, $this->listings));

        $totalArea = array_sum($areas);
        $totalPrice = array_sum($prices);
        $totalAzn = array_sum($aznPrices);

        $weightedSqmUsd = $totalArea > 0 ? round($totalPrice / $totalArea, 2) : null;
        $weightedSqmAzn = $totalArea > 0 && $totalAzn > 0 ? round($totalAzn / $totalArea, 2) : null;

        $rows = [
            // Header row
            [
                'Metric / Size Range',
                'Count',
                'Min Area (sqm)',
                'Max Area (sqm)',
                'Min Price/sqm (AZN)',
                'Max Price/sqm (AZN)',
                'Wtd Avg Price/sqm (AZN)',
                'Min Price/sqm (USD)',
                'Max Price/sqm (USD)',
                'Wtd Avg Price/sqm (USD)',
            ],

            // Overall summary row
            [
                'OVERALL',
                count($this->listings),
                count($areas) ? round(min($areas), 1) : 'N/A',
                count($areas) ? round(max($areas), 1) : 'N/A',
                count($aznSqmPrices) ? round(min($aznSqmPrices), 2) : 'N/A',
                count($aznSqmPrices) ? round(max($aznSqmPrices), 2) : 'N/A',
                $weightedSqmAzn ?? 'N/A',
                count($sqmPrices) ? round(min($sqmPrices), 2) : 'N/A',
                count($sqmPrices) ? round(max($sqmPrices), 2) : 'N/A',
                $weightedSqmUsd ?? 'N/A',
            ],

            ['', '', '', '', '', '', '', '', '', ''],
        ];

        // Size range rows
        $ranges = config('scraper.area_ranges.land', []);

        foreach ($ranges as $range) {
            $inRange = array_filter(
                $this->listings,
                fn ($r) => isset($r['area']) && $r['area'] >= $range['min'] && $r['area'] <= $range['max']
            );

            $rangeAreas = array_filter(array_column($inRange, 'area'));
            $rangeSqmPrices = array_filter(array_column($inRange, 'price_per_sqm'));
            $rangePrices = array_filter(array_column($inRange, 'price'));

            $rangeAzn = array_filter(array_map(
                fn ($r) => $r['extras']['original_price_gel'] ?? $r['extras']['original_price_azn'] ?? null,
                $inRange
            ));

            $rangeAznSqmPrices = array_filter(array_map(function ($r) {
                $azn = $r['extras']['original_price_gel'] ?? $r['extras']['original_price_azn'] ?? null;
                $area = $r['area'] ?? null;

                return ($azn && $area && $area > 0) ? (float) $azn / (float) $area : null;
            }, $inRange));

            $rangeTotalArea = array_sum($rangeAreas);
            $rangeTotalPrice = array_sum($rangePrices);
            $rangeTotalAzn = array_sum($rangeAzn);

            $rangeWeightedUsd = $rangeTotalArea > 0 ? round($rangeTotalPrice / $rangeTotalArea, 2) : null;
            $rangeWeightedAzn = $rangeTotalArea > 0 && $rangeTotalAzn > 0
                ? round($rangeTotalAzn / $rangeTotalArea, 2)
                : null;

            $rows[] = [
                $range['label'],
                count($inRange),
                count($rangeAreas) ? round(min($rangeAreas), 1) : 'N/A',
                count($rangeAreas) ? round(max($rangeAreas), 1) : 'N/A',
                count($rangeAznSqmPrices) ? round(min($rangeAznSqmPrices), 2) : 'N/A',
                count($rangeAznSqmPrices) ? round(max($rangeAznSqmPrices), 2) : 'N/A',
                $rangeWeightedAzn ?? 'N/A',
                count($rangeSqmPrices) ? round(min($rangeSqmPrices), 2) : 'N/A',
                count($rangeSqmPrices) ? round(max($rangeSqmPrices), 2) : 'N/A',
                $rangeWeightedUsd ?? 'N/A',
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
            2 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE2EFDA']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 8,
            'C' => 18,
            'D' => 18,
            'E' => 22,
            'F' => 22,
            'G' => 26,
            'H' => 22,
            'I' => 22,
            'J' => 26,
        ];
    }
}
