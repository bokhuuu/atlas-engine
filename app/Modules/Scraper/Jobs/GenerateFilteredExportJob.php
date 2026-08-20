<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Jobs;

use App\Modules\Scraper\Exports\ExportManager;
use App\Modules\Scraper\Models\ExportJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Generates a filtered Excel export in the background.
 *
 * Dispatched by ExportController::requestFiltered().
 * Updates ExportJob status so frontend can poll for completion.
 */
class GenerateFilteredExportJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;

    public int $tries = 1;

    public function __construct(
        private readonly int $exportJobId,
    ) {}

    public function handle(ExportManager $exportManager): void
    {
        $job = ExportJob::findOrFail($this->exportJobId);

        $job->update(['status' => 'processing']);

        $paths = $exportManager->runForSourceFiltered($job->source, $job->filters);

        if (empty($paths)) {
            $job->update([
                'status' => 'failed',
                'error' => 'No listings match the selected filters.',
            ]);

            return;
        }

        $job->update([
            'status' => 'completed',
            'file_path' => $paths[0],
            'expires_at' => now()->addHours(24),
        ]);
    }

    public function failed(Throwable $exception): void
    {
        ExportJob::where('id', $this->exportJobId)->update([
            'status' => 'failed',
            'error' => $exception->getMessage(),
        ]);
    }
}
