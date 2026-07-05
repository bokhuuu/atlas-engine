<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Exports;

use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Exports\Contracts\ExporterInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Uses Maatwebsite Excel which wraps PHPSpreadsheet internally.
 * Outputs a fixed set of client-facing columns only - internal DB
 * fields (id, scraper_run_id, source_profile_name, etc.) are
 * deliberately excluded since they have no meaning outside the app.
 *
 * Intended for human consumers: clients, analysts, reports.
 * For machine consumption (pipelines, databases) use CsvExporter.
 */
class ExcelExporter implements ExporterInterface
{
    /**
     * An anonymous class is passed to Excel::store() implementing the
     * two Maatwebsite contracts it needs:
     *   FromArray - provides the data rows
     *   WithHeadings - provides the header row
     */
    public function export(array $data, ScraperProfileInterface $profile): string
    {
        $path = $this->buildPath($profile->getName());

        Excel::store(new class($data) implements FromArray, WithHeadings
        {
            /**
             * Client-facing columns only - excludes internal DB fields
             * (id, scraper_run_id, external_id, source_profile_name,
             * created_at, updated_at, images, extras) that have no
             * meaning outside the application.
             *
             * days_on_market and status are computed on export —
             * they do not exist as DB columns.
             */
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
                'agency_name',
                'listing_date',
                'duplicate_group_id',
                'last_seen_at',
                'disappeared_at',
                'days_on_market',
                'status',
                'screenshot_url',
            ];

            public function __construct(private readonly array $data) {}

            public function array(): array
            {
                $staleDays = (int) config('scraper.stale_listing_days', 90);

                return array_map(function ($row) use ($staleDays) {
                    $start = $row['scraped_at'] ?? null;
                    $end = $row['disappeared_at'] ?? now()->toDateTimeString();

                    $row['days_on_market'] = $start
                        ? (int) Carbon::parse($start)->diffInDays(Carbon::parse($end))
                        : null;

                    if (! empty($row['disappeared_at'])) {
                        $row['status'] = 'Disappeared';
                    } elseif ($start && Carbon::parse($start)->diffInDays(now()) >= $staleDays) {
                        $row['status'] = "Stale ({$staleDays}+ days)";
                    } else {
                        $row['status'] = 'Active';
                    }

                    return array_map(fn ($col) => $row[$col] ?? null, $this->clientColumns);
                }, $this->data);
            }

            public function headings(): array
            {
                return $this->clientColumns;
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
     * Build the relative file path for Excel::store().
     * Relative to storage/app/
     * Path root configured via SCRAPER_EXPORT_PATH in .env.
     */
    private function buildPath(string $source): string
    {
        $dir = config('scraper.export_path');
        $date = now()->format('d_m_Y');

        return "{$dir}/{$source}_{$date}.{$this->extension()}";
    }
}
