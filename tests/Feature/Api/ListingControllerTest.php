<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\Scraper\Repositories\ListingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    Sanctum::actingAs(User::factory()->create());
});

test('listings endpoint returns paginated results', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing(['source_profile_name' => 'listam_apartments_sale'])->toArray());

    $this->getJson('/api/v1/listings?source=listam_apartments_sale')
        ->assertOk()
        ->assertJsonStructure(['data', 'total', 'per_page']);
});

test('listings endpoint filters by district', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing(['source_profile_name' => 'listam_apartments_sale', 'district' => 'Kentron'])->toArray());
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'external_id' => 'test-456',
        'url' => 'https://list.am/en/item/456',
        'district' => 'Arabkir',
    ])->toArray());

    $response = $this->getJson('/api/v1/listings?source=listam_apartments_sale&district=Kentron')
        ->assertOk();

    expect($response->json('total'))->toBe(1);
});

test('stats endpoint returns district price data', function () {
    $this->getJson('/api/v1/listings/stats?source=listam_apartments_sale')
        ->assertOk()
        ->assertJsonStructure(['source', 'data']);
});
