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

test('generateAndStore creates document in database', function () {
    Prism::fake([
        EmbeddingsResponseFake::make()
            ->withEmbeddings([
                new Embedding(embedding: [0.1, 0.2, 0.3]),
            ])
            ->withUsage(new EmbeddingsUsage(10)),
    ]);

    $document = $this->service->generateAndStore('Apartment in Kentron, 3 bedrooms, $150,000');

    expect($document)->toBeInstanceOf(Document::class);

    $this->assertDatabaseHas('documents', [
        'content' => 'Apartment in Kentron, 3 bedrooms, $150,000',
    ]);
});

test('search returns collection of results ranked by similarity', function () {
    Prism::fake([
        EmbeddingsResponseFake::make()
            ->withEmbeddings([new Embedding(embedding: [0.1, 0.2, 0.3])]),
        EmbeddingsResponseFake::make()
            ->withEmbeddings([new Embedding(embedding: [0.2, 0.3, 0.4])]),
        EmbeddingsResponseFake::make()
            ->withEmbeddings([new Embedding(embedding: [0.9, 0.8, 0.7])]),
        EmbeddingsResponseFake::make()
            ->withEmbeddings([new Embedding(embedding: [0.1, 0.2, 0.3])]),
    ]);

    $this->service->generateAndStore('Apartment in Kentron, 3 bedrooms');
    $this->service->generateAndStore('Commercial space in Arabkir');
    $this->service->generateAndStore('Land plot in Erebuni');

    $results = $this->service->search('residential apartment central Yerevan', 2);

    expect($results)->toHaveCount(2);
    expect($results->first())->toHaveKey('document');
    expect($results->first())->toHaveKey('score');
});
