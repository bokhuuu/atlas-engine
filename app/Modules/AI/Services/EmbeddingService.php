<?php

declare(strict_types=1);

namespace App\Modules\AI\Services;

use App\Modules\AI\Models\Document;
use Illuminate\Support\Collection;
use Pgvector\Laravel\Distance;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

/**
 * Converts text into vectors (embeddings) and searches them by meaning.
 *
 * How it works:
 * - Store phase: text goes in, a list of numbers (vector) comes out, both saved to DB
 * - Search phase: convert the query to a vector, compare against all stored vectors
 * - PostgreSQL does the math via pgvector - no PHP memory loop, scales to any dataset size
 *
 * This is the foundation of RAG - find relevant documents by meaning,
 * inject them into the AI prompt as context before asking the question.
 *
 * Activated in Thread 11 - pgvector replaces the in-memory cosine similarity approach.
 * To index a market's listings: php artisan atlas:embed-listings --source=<profile>
 */
class EmbeddingService
{
    /**
     * Convert text to a vector and save it to the documents table.
     * source_type and source_id link the embedding back to its origin record.
     */
    public function generateAndStore(
        string $content,
        ?string $sourceType = null,
        ?int $sourceId = null,
    ): Document {
        $embedding = $this->generateEmbedding($content);

        return Document::create([
            'content' => $content,
            'embedding' => $embedding,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);
    }

    /**
     * Find documents whose meaning is closest to the query.
     * Uses pgvector cosine distance - fully database-side, no PHP memory loop.
     * Returns a collection of Document models ordered by similarity (closest first).
     */
    public function search(string $query, ?int $limit = null): Collection
    {
        $limit ??= config('ai.embeddings.search_limit', 5);

        $queryEmbedding = $this->generateEmbedding($query);

        return Document::query()
            ->nearestNeighbors('embedding', $queryEmbedding, Distance::Cosine)
            ->take($limit)
            ->get();
    }

    /**
     * Check whether any documents have been indexed yet.
     * Used by the semantic_search agent tool to return a graceful message
     * when no embeddings exist rather than an empty result set.
     */
    public function hasDocuments(): bool
    {
        return Document::query()->exists();
    }

    /** Ask the AI model to convert text into a numeric vector. */
    private function generateEmbedding(string $text): array
    {
        return Prism::embeddings()
            ->using(Provider::from(config('ai.providers.default')), config('ai.models.embeddings'))
            ->fromInput($text)
            ->asEmbeddings()
            ->embeddings[0]->embedding;
    }
}
