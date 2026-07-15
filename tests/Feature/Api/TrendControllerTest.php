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

test('volume endpoint returns series scoped to market', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'listing_type' => 'sale',
        'listing_date' => now()->subMonths(1)->toDateTimeString(),
    ])->toArray());

    $this->getJson('/api/v1/trends/volume?market=yerevan')
        ->assertOk()
        ->assertJsonStructure(['series'])
        ->assertJsonCount(1, 'series');
});

test('volume endpoint returns empty series for market with no data', function () {
    $this->getJson('/api/v1/trends/volume?market=baku')
        ->assertOk()
        ->assertJson(['series' => []]);
});

test('volume endpoint excludes listings older than 24 months', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'listing_type' => 'sale',
        'listing_date' => now()->subMonths(25)->toDateTimeString(),
    ])->toArray());

    $this->getJson('/api/v1/trends/volume?market=yerevan')
        ->assertOk()
        ->assertJson(['series' => []]);
});

test('volume endpoint supports quarter groupBy parameter', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'listing_type' => 'sale',
        'listing_date' => now()->subMonths(1)->toDateTimeString(),
    ])->toArray());

    $this->getJson('/api/v1/trends/volume?market=yerevan&groupBy=quarter')
        ->assertOk()
        ->assertJsonStructure(['series']);
});

test('price endpoint returns median price per sqm for sale listings', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_sale',
        'listing_type' => 'sale',
        'listing_date' => now()->subMonths(1)->toDateTimeString(),
        'price_per_sqm' => 1500,
    ])->toArray());

    $this->getJson('/api/v1/trends/price?market=yerevan')
        ->assertOk()
        ->assertJsonStructure(['series'])
        ->assertJsonCount(1, 'series');
});

test('price endpoint returns empty series for market with no data', function () {
    $this->getJson('/api/v1/trends/price?market=baku')
        ->assertOk()
        ->assertJson(['series' => []]);
});

test('price endpoint excludes rent listings', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(makeListing([
        'source_profile_name' => 'listam_apartments_rent',
        'listing_type' => 'rent',
        'listing_date' => now()->subMonths(1)->toDateTimeString(),
        'price_per_sqm' => 10,
    ])->toArray());

    $this->getJson('/api/v1/trends/price?market=yerevan')
        ->assertOk()
        ->assertJson(['series' => []]);
});
