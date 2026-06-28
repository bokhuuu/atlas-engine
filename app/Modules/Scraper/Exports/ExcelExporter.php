<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Exports;

use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Exports\Contracts\ExporterInterface;
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
             */
            private array $clientColumns = [
                'url',
                'listing_type',
                'property_type',
                'district',
                'address',
                'price',
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
            ];

            public function __construct(private readonly array $data) {}

            public function array(): array
            {
                return array_map(
                    fn ($row) => array_map(fn ($col) => $row[$col] ?? null, $this->clientColumns),
                    $this->data
                );
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
