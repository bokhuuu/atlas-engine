<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Api;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Exports\ExportManager;
use App\Modules\Scraper\Jobs\GenerateFilteredExportJob;
use App\Modules\Scraper\Models\ExportJob;
use App\Services\MarketRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

/**
 * Generates on-demand exports from current DB data, independent of
 * any scrape run - lets an admin get a fresh file without re-scraping.
 */
class ExportController extends Controller
{
    public function __construct(
        private readonly ExportManager $exportManager,
        private readonly MarketRegistry $marketRegistry,
    ) {}

    /**
     * Returns source profile keys for the market specified in the request query param.
     * Falls back to the default market if not provided.
     */
    private function currentMarketSources(): array
    {
        $market = request()->query('market', $this->marketRegistry->defaultSlug());
        $marketConfig = $this->marketRegistry->fromSlug($market);
        $sourcePrefixes = $marketConfig['source_prefixes'] ?? [];

        return array_values(array_filter(
            array_keys(config('scraper.profiles', [])),
            fn ($source) => collect($sourcePrefixes)
                ->contains(fn ($prefix) => str_starts_with($source, $prefix.'_'))
        ));
    }

    /**
     * Bundle pre-generated exports for every category into a single ZIP.
     *
     * Uses today's already-generated Excel files when available - avoids
     * regenerating all listings into memory on every click, which causes
     * OOM errors as dataset grows (4,500+ listings × multiple categories).
     *
     * Falls back to regenerating if no file exists for today (first download
     * of the day before TriggerScrapeExport has run).
     *
     * Filtered exports (Data Hub) always regenerate - they must reflect
     * current filter state and cannot use pre-generated files.
     */
    public function download(string $source): BinaryFileResponse|Response
    {
        if (! array_key_exists($source, config('scraper.profiles', []))) {
            abort(404, 'Unknown category.');
        }

        $paths = $this->exportManager->runForSource($source, onlySpreadsheets: true);

        if (empty($paths)) {
            return response('No data available to export for this category yet.', 404);
        }

        return response()->download($paths[0]);
    }

    /**
     * Generate a fresh export for every category and bundle the
     * results into a single zip for one-click download.
     */
    public function downloadAll(): BinaryFileResponse|Response
    {
        $sources = $this->currentMarketSources();
        $allPaths = [];
        $exportPath = config('scraper.export_path');

        foreach ($sources as $source) {
            $todayFile = storage_path(
                'app/private/'.$exportPath.'/real_estate_market_report_'.$source.'_'.now()->format('d_m_Y').'.xlsx'
            );

            if (file_exists($todayFile)) {
                $allPaths[] = $todayFile;
            } else {
                $paths = $this->exportManager->runForSource($source, onlySpreadsheets: true);
                $allPaths = [...$allPaths, ...$paths];
            }
        }

        if (empty($allPaths)) {
            return response('No data available to export yet.', 404);
        }

        $zipPath = storage_path(
            'app/private/'.$exportPath.'/atlas_export_'.now()->format('Y_m_d_His').'.zip'
        );

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($allPaths as $path) {
            $zip->addFile($path, basename($path));
        }
        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Generate a filtered Excel export for Data Hub.
     * Accepts the same filter params as /api/v1/listings/filter.
     * Returns a single-sheet Excel file with only matching listings.
     */
    public function downloadFiltered(Request $request): BinaryFileResponse|Response
    {
        $source = $request->query('source', '');

        if (! array_key_exists($source, config('scraper.profiles', []))) {
            abort(404, 'Unknown category.');
        }

        $filters = $request->only([
            'source',
            'district',
            'rooms',
            'min_price',
            'max_price',
            'min_area',
            'max_area',
            'agency',
            'has_bill_of_sale',
            'has_mortgage',
            'exclude_duplicates',
            'scraped_from',
            'scraped_to',
            'status',
            'listing_date_from',
            'listing_date_to',
        ]);

        $paths = $this->exportManager->runForSourceFiltered($source, $filters);

        if (empty($paths)) {
            return response('No listings match the selected filters.', 404);
        }

        return response()->download($paths[0]);
    }

    /**
     * Request a background filtered export job.
     * Returns job_id immediately - frontend polls status endpoint.
     */
    public function requestFiltered(Request $request): JsonResponse
    {
        $source = $request->query('source', '');

        if (! array_key_exists($source, config('scraper.profiles', []))) {
            abort(404, 'Unknown category.');
        }

        $filters = $request->only([
            'source',
            'district',
            'rooms',
            'min_price',
            'max_price',
            'min_area',
            'max_area',
            'agency',
            'has_bill_of_sale',
            'has_mortgage',
            'exclude_duplicates',
            'scraped_from',
            'scraped_to',
            'status',
            'listing_date_from',
            'listing_date_to',
        ]);

        $job = ExportJob::create([
            'status' => 'pending',
            'source' => $source,
            'filters' => $filters,
        ]);

        GenerateFilteredExportJob::dispatch($job->id);

        return response()->json(['job_id' => $job->id]);
    }

    /**
     * Poll the status of a background export job.
     */
    public function filteredStatus(int $id): JsonResponse
    {
        $job = ExportJob::findOrFail($id);

        return response()->json([
            'status' => $job->status,
            'error' => $job->error,
        ]);
    }

    /**
     * Download the completed export file.
     */
    public function filteredDownload(int $id): BinaryFileResponse|JsonResponse
    {
        $job = ExportJob::findOrFail($id);

        if ($job->status !== 'completed' || ! $job->file_path) {
            return response()->json(['error' => 'Export not ready.'], 400);
        }

        if (! file_exists($job->file_path)) {
            return response()->json(['error' => 'Export file not found.'], 404);
        }

        return response()->download($job->file_path);
    }
}
