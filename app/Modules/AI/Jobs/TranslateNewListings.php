<?php

declare(strict_types=1);

namespace App\Modules\AI\Jobs;

use App\Modules\AI\Services\TranslationService;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Translates listing descriptions from native language to English.
 *
 * Finds all listings where description is populated but
 * translated_description is null, translates each via TranslationService,
 * and stores the result. Scoped to a specific source profile when provided.
 *
 * Dispatched automatically by ScrapeCompletedListener after each scrape run.
 * Can also be dispatched manually for backfill:
 *   php artisan queue:work (after dispatching via tinker)
 *
 * Failures per listing are logged and skipped - one bad translation
 * never blocks the rest of the batch.
 */
class TranslateNewListings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(
        private readonly ?string $source = null,
    ) {}

    /**
     * Find and translate all listings with description but no translation.
     * Scoped to source profile when provided, otherwise all sources.
     */
    public function handle(TranslationService $translationService): void
    {
        $query = Listing::whereNotNull('description')
            ->whereNull('translated_description');

        if ($this->source !== null) {
            $query->where('source_profile_name', $this->source);
        }

        $count = $query->count();

        if ($count === 0) {
            Log::info('TranslateNewListings: no listings to translate', [
                'source' => $this->source ?? 'all',
            ]);

            return;
        }

        Log::info('TranslateNewListings: starting translation batch', [
            'source' => $this->source ?? 'all',
            'count' => $count,
        ]);

        $translated = 0;
        $failed = 0;

        $query->chunkById((int) config('ai.translation.chunk_size', 50), function ($listings) use ($translationService, &$translated, &$failed) {
            foreach ($listings as $listing) {
                $result = $translationService->translate($listing->description);

                if ($result !== null) {
                    $listing->update(['translated_description' => $result]);
                    $translated++;
                } else {
                    $failed++;
                    Log::warning('TranslateNewListings: translation returned null', [
                        'listing_id' => $listing->id,
                        'source' => $listing->source_profile_name,
                    ]);
                }
            }
        });

        Log::info('TranslateNewListings: batch complete', [
            'source' => $this->source ?? 'all',
            'translated' => $translated,
            'failed' => $failed,
        ]);

        Log::info('TranslateNewListings: batch complete', [
            'source' => $this->source ?? 'all',
            'translated' => $translated,
            'failed' => $failed,
        ]);

        // If there are still untranslated listings, dispatch another batch
        $remaining = Listing::whereNotNull('description')
            ->whereNull('translated_description')
            ->when($this->source !== null, fn ($q) => $q->where('source_profile_name', $this->source))
            ->count();

        if ($remaining > 0) {
            self::dispatch($this->source)->delay(now()->addSeconds(5));
            Log::info('TranslateNewListings: dispatching next batch', [
                'remaining' => $remaining,
                'source' => $this->source ?? 'all',
            ]);
        }
    }
}
