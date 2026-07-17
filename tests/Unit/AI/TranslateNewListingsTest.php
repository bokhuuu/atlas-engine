<?php

declare(strict_types=1);

use App\Modules\AI\Jobs\TranslateNewListings;
use App\Modules\AI\Services\TranslationService;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('skips listings with no description', function () {
    Listing::factory()->create([
        'description' => null,
        'translated_description' => null,
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldNotReceive('translate');

    $job = new TranslateNewListings;
    $job->handle($service);
});

test('skips listings already translated', function () {
    Listing::factory()->create([
        'description' => 'Some text',
        'translated_description' => 'Already translated',
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldNotReceive('translate');

    $job = new TranslateNewListings;
    $job->handle($service);
});

test('translates listings with description but no translation', function () {
    $listing = Listing::factory()->create([
        'description' => 'Հայերեն նկարագրություն',
        'translated_description' => null,
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldReceive('translate')
        ->once()
        ->with('Հայերեն նկարագրություն')
        ->andReturn('Armenian description');

    $job = new TranslateNewListings;
    $job->handle($service);

    expect($listing->fresh()->translated_description)->toBe('Armenian description');
});

test('scopes to source when provided', function () {
    Listing::factory()->create([
        'source_profile_name' => 'listam_apartments_sale',
        'description' => 'Text to translate',
        'translated_description' => null,
    ]);

    Listing::factory()->create([
        'source_profile_name' => 'binaz_apartments_sale',
        'description' => 'Other market text',
        'translated_description' => null,
    ]);

    $service = Mockery::mock(TranslationService::class);
    $service->shouldReceive('translate')
        ->once()
        ->andReturn('Translated');

    $job = new TranslateNewListings('listam_apartments_sale');
    $job->handle($service);
});
