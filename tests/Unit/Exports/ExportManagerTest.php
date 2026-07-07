<?php

declare(strict_types=1);

use App\Modules\Scraper\Exports\ExportManager;
use App\Modules\Scraper\Repositories\ListingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('it returns no export paths when the category has no listings yet', function () {
    Storage::fake('local');

    $paths = app(ExportManager::class)->runForSource('listam_commercial_rent', onlySpreadsheets: true);

    expect($paths)->toBe([]);
});

test('it generates a real spreadsheet file from current DB data, skipping json when onlySpreadsheets is true', function () {
    Storage::fake('local');

    app(ListingRepository::class)->updateOrCreate([
        'external_id' => 'export-1',
        'source_profile_name' => 'listam_commercial_rent',
        'url' => 'https://list.am/en/item/export-1',
        'listing_type' => 'rent',
        'property_type' => 'commercial',
        'price' => 500.0,
        'currency' => 'USD',
        'price_per_sqm' => 10.0,
        'area' => 50.0,
        'images' => [],
        'extras' => [],
        'scraped_at' => now()->toDateTimeString(),
    ]);

    $paths = app(ExportManager::class)->runForSource('listam_commercial_rent', onlySpreadsheets: true);

    expect($paths)->toHaveCount(1);
    expect(file_exists($paths[0]))->toBeTrue();
    expect($paths[0])->toEndWith('.xlsx');
});

test('it generates filtered export with only matching listings', function () {
    Storage::fake('local');

    $repository = app(ListingRepository::class);

    $repository->updateOrCreate([
        'external_id' => 'filter-1',
        'source_profile_name' => 'listam_commercial_rent',
        'url' => 'https://list.am/en/item/filter-1',
        'listing_type' => 'rent',
        'property_type' => 'commercial',
        'price' => 300.0,
        'currency' => 'USD',
        'price_per_sqm' => 6.0,
        'area' => 50.0,
        'district' => 'Kentron',
        'images' => [],
        'extras' => [],
        'scraped_at' => now()->toDateTimeString(),
    ]);

    $repository->updateOrCreate([
        'external_id' => 'filter-2',
        'source_profile_name' => 'listam_commercial_rent',
        'url' => 'https://list.am/en/item/filter-2',
        'listing_type' => 'rent',
        'property_type' => 'commercial',
        'price' => 800.0,
        'currency' => 'USD',
        'price_per_sqm' => 16.0,
        'area' => 50.0,
        'district' => 'Arabkir',
        'images' => [],
        'extras' => [],
        'scraped_at' => now()->toDateTimeString(),
    ]);

    $paths = app(ExportManager::class)->runForSourceFiltered(
        'listam_commercial_rent',
        ['source' => 'listam_commercial_rent', 'district' => 'Kentron'],
    );

    expect($paths)->toHaveCount(1);
    expect(file_exists($paths[0]))->toBeTrue();
});

test('it returns empty paths when filtered listings are empty', function () {
    Storage::fake('local');

    $paths = app(ExportManager::class)->runForSourceFiltered(
        'listam_commercial_rent',
        ['source' => 'listam_commercial_rent', 'district' => 'Kentron'],
    );

    expect($paths)->toBe([]);
});
