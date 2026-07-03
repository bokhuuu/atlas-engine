<?php

declare(strict_types=1);

use App\Modules\AI\Models\Document;
use App\Modules\AI\Services\EmbeddingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Testing\EmbeddingsResponseFake;
use Prism\Prism\ValueObjects\Embedding;
use Prism\Prism\ValueObjects\EmbeddingsUsage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new EmbeddingService;
});

function fakeEmbedding(float $seed = 0.1): Embedding
{
    return new Embedding(embedding: array_fill(0, 1536, $seed));
}

test('generateAndStore creates document in database', function () {
    Prism::fake([
        EmbeddingsResponseFake::make()
            ->withEmbeddings([fakeEmbedding(0.1)])
            ->withUsage(new EmbeddingsUsage(10)),
    ]);

    $document = $this->service->generateAndStore('Apartment in Kentron, 3 bedrooms, $150,000');

    expect($document)->toBeInstanceOf(Document::class);

    $this->assertDatabaseHas('documents', [
        'content' => 'Apartment in Kentron, 3 bedrooms, $150,000',
        'source_type' => null,
        'source_id' => null,
    ]);
});

test('generateAndStore saves source_type and source_id when provided', function () {
    Prism::fake([
        EmbeddingsResponseFake::make()
            ->withEmbeddings([fakeEmbedding(0.1)])
            ->withUsage(new EmbeddingsUsage(10)),
    ]);

    $this->service->generateAndStore('Apartment in Kentron', 'listing', 42);

    $this->assertDatabaseHas('documents', [
        'content' => 'Apartment in Kentron',
        'source_type' => 'listing',
        'source_id' => 42,
    ]);
});

test('search returns collection of Document models ranked by similarity', function () {
    Prism::fake([
        EmbeddingsResponseFake::make()
            ->withEmbeddings([fakeEmbedding(0.1)])
            ->withUsage(new EmbeddingsUsage(10)),
        EmbeddingsResponseFake::make()
            ->withEmbeddings([fakeEmbedding(0.2)])
            ->withUsage(new EmbeddingsUsage(10)),
        EmbeddingsResponseFake::make()
            ->withEmbeddings([fakeEmbedding(0.9)])
            ->withUsage(new EmbeddingsUsage(10)),
        EmbeddingsResponseFake::make()
            ->withEmbeddings([fakeEmbedding(0.1)])
            ->withUsage(new EmbeddingsUsage(10)),
    ]);

    $this->service->generateAndStore('Apartment in Kentron, 3 bedrooms');
    $this->service->generateAndStore('Commercial space in Arabkir');
    $this->service->generateAndStore('Land plot in Erebuni');

    $results = $this->service->search('residential apartment central Yerevan', 2);

    expect($results)->toHaveCount(2);
    expect($results->first())->toBeInstanceOf(Document::class);
});

test('hasDocuments returns false when table is empty', function () {
    expect($this->service->hasDocuments())->toBeFalse();
});

test('hasDocuments returns true when documents exist', function () {
    Prism::fake([
        EmbeddingsResponseFake::make()
            ->withEmbeddings([fakeEmbedding(0.1)])
            ->withUsage(new EmbeddingsUsage(10)),
    ]);

    $this->service->generateAndStore('test content');

    expect($this->service->hasDocuments())->toBeTrue();
});
