<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Modules\AI\Models\Document;
use App\Modules\AI\Services\EmbeddingService;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Console\Command;

class EmbedListings extends Command
{
    protected $signature = 'atlas:embed-listings
                            {--source= : The source profile name to embed (e.g. listam_apartments_sale)}
                            {--chunk=50 : Number of listings to process per batch}';

    protected $description = 'Generate and store vector embeddings for listing text. Run once per market when text data is available.';

    public function handle(EmbeddingService $service): int
    {
        $source = $this->option('source');
        $chunk = (int) $this->option('chunk');

        if (! $source) {
            $this->error('--source is required. Example: php artisan atlas:embed-listings --source=listam_apartments_sale');

            return self::FAILURE;
        }

        $alreadyIndexed = Document::query()
            ->where('source_type', 'listing')
            ->pluck('source_id')
            ->flip();

        $query = Listing::query()
            ->where('source_profile_name', $source)
            ->whereNotNull('title')
            ->whereNotIn('id', $alreadyIndexed->keys()->all());

        $total = $query->count();

        if ($total === 0) {
            $this->info('No new listings to embed for '.$source.'.');

            return self::SUCCESS;
        }

        $this->info("Embedding {$total} listings for {$source}...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $embedded = 0;
        $failed = 0;

        $query->chunkById($chunk, function ($listings) use ($service, $bar, &$embedded, &$failed) {
            foreach ($listings as $listing) {
                try {
                    $text = trim(implode(' ', array_filter([
                        $listing->title,
                        $listing->description ?? null,
                    ])));

                    if (empty($text)) {
                        continue;
                    }

                    $service->generateAndStore($text, 'listing', $listing->id);
                    $embedded++;
                } catch (\Throwable $e) {
                    $failed++;
                    $this->newLine();
                    $this->warn("Failed listing ID {$listing->id}: {$e->getMessage()}");
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Done. Embedded: {$embedded}, Failed: {$failed}.");

        return self::SUCCESS;
    }
}
