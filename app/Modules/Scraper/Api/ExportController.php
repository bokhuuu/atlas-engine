<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Api;

use App\Http\Controllers\Controller;
use App\Modules\Scraper\Exports\ExportManager;
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
    ) {}

    /**
     * Generate and download a fresh export for one category, built
     * from whatever is currently in the database for that source.
     *
     * Only spreadsheet formats are offered - if a category's getExports()
     * includes 'json', it's skipped here (API-only, not a file download).
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
    public function downloadAll(): BinaryFileResponse
    {
        $sources = array_keys(config('scraper.profiles', []));
        $allPaths = [];

        foreach ($sources as $source) {
            $paths = $this->exportManager->runForSource($source, onlySpreadsheets: true);
            $allPaths = [...$allPaths, ...$paths];
        }

        $zipPath = storage_path(
            'app/'.config('scraper.export_path').'/atlas_export_'.now()->format('Y_m_d_His').'.zip'
        );

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($allPaths as $path) {
            $zip->addFile($path, basename($path));
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
