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
    $repository->updateOrCreate(makeListing(
        ['source_profile_name' => 'listam_apartments_sale']
    )->toArray());

    $this->getJson('/api/v1/listings?source=listam_apartments_sale')
        ->assertOk()
        ->assertJsonStructure(['data', 'total', 'per_page']);
});

test('listings endpoint filters by district', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing(
        ['source_profile_name' => 'listam_apartments_sale', 'district' => 'Kentron']
    )->toArray());
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

test('filter endpoint returns paginated results', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'district' => 'Kentron',
        'price' => 150000,
    ])->toArray());

    $this->getJson('/api/v1/listings/filter?source=listam_apartments_sale')
        ->assertOk()
        ->assertJsonStructure(['data', 'total', 'per_page']);
});

test('filter endpoint filters by district', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'district' => 'Kentron',
    ])->toArray());
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'external_id' => 'test-456',
        'url' => 'https://list.am/en/item/456',
        'district' => 'Arabkir',
    ])->toArray());

    $response = $this->getJson('/api/v1/listings/filter?source=listam_apartments_sale&district=Kentron')
        ->assertOk();

    expect($response->json('total'))->toBe(1);
});

test('filter endpoint filters by status active', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'disappeared_at' => null,
        'scraped_at' => now()->toDateTimeString(),
        'listing_date' => now()->toDateTimeString(),
    ])->toArray());
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'external_id' => 'test-456',
        'url' => 'https://list.am/en/item/456',
        'disappeared_at' => now()->toDateTimeString(),
    ])->toArray());

    $response = $this->getJson('/api/v1/listings/filter?source=listam_apartments_sale&status[]=active')
        ->assertOk();

    expect($response->json('total'))->toBe(1);
});

test('stats endpoint returns disappeared and stale counts', function () {
    $response = $this->getJson('/api/v1/listings/stats?source=listam_apartments_sale')
        ->assertOk();

    expect($response->json('data'))->toBeArray();
});

test('filter endpoint filters by price range', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'price' => 80000,
    ])->toArray());
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'external_id' => 'test-456',
        'url' => 'https://list.am/en/item/456',
        'price' => 200000,
    ])->toArray());

    $response = $this->getJson('/api/v1/listings/filter?source=listam_apartments_sale&max_price=100000')
        ->assertOk();

    expect($response->json('total'))->toBe(1);
});

test('filter endpoint filters by scraped date range', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'scraped_at' => '2021-01-15 10:00:00',
    ])->toArray());
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'external_id' => 'test-456',
        'url' => 'https://list.am/en/item/456',
        'scraped_at' => now()->toDateTimeString(),
    ])->toArray());

    $response = $this->getJson('/api/v1/listings/filter?source=listam_apartments_sale&scraped_from=2021-01-01&scraped_to=2021-12-31')
        ->assertOk();

    expect($response->json('total'))->toBe(1);
});
