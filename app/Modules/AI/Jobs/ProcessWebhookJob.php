<?php

declare(strict_types=1);

namespace App\Modules\AI\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Processes an uploaded file received via webhook asynchronously.
 *
 * Receives an uploaded file from WebhookController and processes it.
 * Runs async via Horizon - webhook returns 200 immediately while this runs in background.
 *
 * Add domain processing logic to handle() when activating webhooks.
 * Examples:
 * - PDF catalog → PdfExtractionService + EmbeddingService
 * - Image → MultiModalService
 * - CSV → parse rows and store in DB
 *
 * Always clean up the temporary file after processing.
 */
class ProcessWebhookJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public readonly string $filePath,
        public readonly string $mimeType,
        public readonly array $metadata = [],
    ) {}

    /**
     * Process the uploaded file.
     * Add domain logic here when activating webhooks.
     * Always cleans up the temporary file even if processing fails.
     */
    public function handle(): void
    {
        try {
            Log::info('Processing webhook file', [
                'file' => $this->filePath,
                'mime_type' => $this->mimeType,
                'metadata' => $this->metadata,
            ]);

            // Add domain processing logic here when activating webhooks.
            // Example for PDF catalogs:
            // $items = app(PdfExtractionService::class)->extract($this->filePath, ['name', 'price']);
            // foreach ($items as $item) {
            //     app(EmbeddingService::class)->generateAndStore(json_encode($item));
            // }

        } finally {
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }
        }
    }

    /** Log failure details when all retry attempts are exhausted. */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessWebhookJob failed', [
            'file' => $this->filePath,
            'error' => $exception->getMessage(),
        ]);
    }
}
