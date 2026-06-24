<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Exports;

use App\Modules\Scraper\Contracts\ScraperProfileInterface;
use App\Modules\Scraper\Repositories\ListingRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Resolves the active profile from config, fetches the scraped listings
 * for the completed run, then calls each configured exporter in turn.
 *
 * Adding a new export format requires only:
 *   1. A class implementing ExporterInterface
 *   2. An entry in config/scraper.php exporters map
 *   3. The format key declared in the profile's getExports()
 *
 * ExportManager itself never changes.
 */
class ExportManager
{
    public function __construct(
        private readonly ListingRepository $repository,
    ) {}

    /**
     * Run all configured exporters for the completed scrape run.
     *
     * Fetches listings scoped to the scraperRunId so exports always
     * reflect exactly what was collected in this run - not the full table.
     */
    public function run(string $source, int $scraperRunId): array
    {
        $profileClass = config('scraper.profiles.'.$source);
        $profile = app($profileClass);

        $listings = $this->repository->findByRun($scraperRunId);

        if ($listings->isEmpty()) {
            Log::warning('ExportManager: no listings found for run.', [
                'source' => $source,
                'scraper_run_id' => $scraperRunId,
            ]);

            return [];
        }

        return $this->runExporters($profile, $source, $listings);
    }

    /**
     * Run configured exporters for a category's full current dataset,
     * not scoped to any one scrape run.
     *
     * Used for on-demand exports an admin generates from existing DB
     * data, without re-scraping - the panel's "Download" buttons.
     *
     * onlySpreadsheets skips the 'json' format - JSON access is served
     * via the REST API for machine consumers, not offered as a file
     * download here.
     */
    public function runForSource(string $source, bool $onlySpreadsheets = false): array
    {
        $profileClass = config('scraper.profiles.'.$source);
        $profile = app($profileClass);

        $listings = $this->repository->findBySource($source);

        if ($listings->isEmpty()) {
            Log::warning('ExportManager: no listings found for source.', [
                'source' => $source,
            ]);

            return [];
        }

        return $this->runExporters($profile, $source, $listings, $onlySpreadsheets);
    }

    /**
     * Shared exporter loop used by both run() and runForSource() -
     * the only real difference between the two is how listings get
     * fetched, not what happens to them afterward.
     */
    private function runExporters(ScraperProfileInterface $profile, string $source, Collection $listings, bool $onlySpreadsheets = false): array
    {
        $data = $listings->map(fn ($listing) => $listing->toArray())->toArray();
        $paths = [];

        foreach ($profile->getExports() as $format) {
            if ($onlySpreadsheets && $format === 'json') {
                continue;
            }

            $exporterClass = config('scraper.exporters.'.$format);

            if (! $exporterClass) {
                Log::warning('ExportManager: unknown export format.', [
                    'format' => $format,
                    'source' => $source,
                ]);

                continue;
            }

            $exporter = app($exporterClass);
            $paths[] = $exporter->export($data, $profile);

            Log::info('ExportManager: export complete.', [
                'format' => $format,
                'source' => $source,
            ]);
        }

        return $paths;
    }
}
