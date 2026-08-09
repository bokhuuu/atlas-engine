<?php

declare(strict_types=1);

use App\Modules\AI\Agents\PropertyAssistantAgent;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeStatsAgent(): PropertyAssistantAgent
{
    return new PropertyAssistantAgent('test_session_'.uniqid(), 'baku');
}

function seedBakuListing(array $overrides = []): Listing
{
    return Listing::create(array_merge([
        'external_id' => uniqid('ext_'),
        'source_profile_name' => 'binaz_land_sale',
        'url' => 'https://ru.bina.az/items/'.rand(1000, 9999),
        'listing_type' => 'sale',
        'property_type' => 'land',
        'price' => 50000,
        'currency' => 'AZN',
        'price_per_sqm' => 100,
        'area' => 500,
        'district' => 'Khazar',
        'has_bill_of_sale' => true,
        'has_mortgage' => false,
        'images' => [],
        'extras' => [],
        'scraped_at' => now(),
    ], $overrides));
}

// ---------------------------------------------------------------------------
// getLocationStats
// ---------------------------------------------------------------------------

test('getLocationStats returns not found message when no listings mention the location', function () {
    $agent = makeStatsAgent();
    $result = json_decode($agent->getLocationStats('Sea Breeze'), true);

    expect($result)->toHaveKey('message')
        ->and($result['message'])->toContain('scraper profile exists but no data has been collected');
});

// ---------------------------------------------------------------------------
// getDistrictStats
// ---------------------------------------------------------------------------

test('getDistrictStats returns not found message when no listings exist for district', function () {
    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Khazar'), true);

    expect($result)->toHaveKey('message')
        ->and($result['message'])->toContain('No listings found');
});

test('getDistrictStats returns stats for existing district', function () {
    seedBakuListing(['district' => 'Khazar', 'price' => 50000, 'area' => 500]);
    seedBakuListing(['district' => 'Khazar', 'price' => 100000, 'area' => 1000]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Khazar'), true);

    expect($result)->toHaveKey('stats')
        ->and($result['district'])->toBe('Khazar')
        ->and($result['currency'])->toBe('AZN (USD)')
        ->and($result['stats']['count'])->toBe(2)
        ->and($result['stats']['min_price_usd'])->toBe(50000)
        ->and($result['stats']['max_price_usd'])->toBe(100000)
        ->and($result['stats']['avg_price_usd'])->toBe(75000)
        ->and($result['stats']['median_price_usd'])->toBe(75000);
});

test('getDistrictStats filters by source when source provided', function () {
    seedBakuListing(['district' => 'Khazar', 'source_profile_name' => 'binaz_land_sale']);
    seedBakuListing(['district' => 'Khazar', 'source_profile_name' => 'binaz_land_sale', 'price' => 80000]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Khazar', 'binaz_land_sale'), true);

    expect($result['stats']['count'])->toBe(2)
        ->and($result['source_filter'])->toBe('binaz_land_sale');
});

test('getDistrictStats returns not found when source filter excludes all listings', function () {
    seedBakuListing(['district' => 'Khazar', 'source_profile_name' => 'binaz_land_sale']);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Khazar', 'binaz_offices_sale'), true);

    expect($result)->toHaveKey('message');
});

test('getDistrictStats calculates weighted avg price per sqm correctly', function () {
    // Listing 1: price=100000, area=500  → 200/sqm
    // Listing 2: price=200000, area=1000 → 200/sqm
    // Weighted avg = (100000+200000) / (500+1000) = 300000/1500 = 200.0
    seedBakuListing(['district' => 'Sabail', 'price' => 100000, 'area' => 500]);
    seedBakuListing(['district' => 'Sabail', 'price' => 200000, 'area' => 1000]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Sabail'), true);

    expect((float) $result['stats']['weighted_avg_price_per_sqm_usd'])->toBe(200.0);
});

test('getDistrictStats calculates bill of sale percentage correctly', function () {
    seedBakuListing(['district' => 'Narimanov', 'has_bill_of_sale' => true]);
    seedBakuListing(['district' => 'Narimanov', 'has_bill_of_sale' => true]);
    seedBakuListing(['district' => 'Narimanov', 'has_bill_of_sale' => false]);
    seedBakuListing(['district' => 'Narimanov', 'has_bill_of_sale' => null]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Narimanov'), true);

    // 2 true out of 3 non-null = 66.7%
    expect($result['stats']['bill_of_sale_pct'])->toBe(66.7);
});

test('getDistrictStats returns null bill of sale pct when all has_bill_of_sale values are null', function () {
    seedBakuListing(['district' => 'Binagadi', 'has_bill_of_sale' => null]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Binagadi'), true);

    expect($result['stats']['bill_of_sale_pct'])->toBeNull();
});

test('getDistrictStats area stats are correct', function () {
    seedBakuListing(['district' => 'Nizami', 'area' => 200]);
    seedBakuListing(['district' => 'Nizami', 'area' => 800]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Nizami'), true);

    expect((float) $result['stats']['min_area_sqm'])->toBe(200.0)
        ->and((float) $result['stats']['max_area_sqm'])->toBe(800.0)
        ->and((float) $result['stats']['avg_area_sqm'])->toBe(500.0);
});

// ---------------------------------------------------------------------------
// Listing model boolean cast fix
// ---------------------------------------------------------------------------

test('has_bill_of_sale is cast to boolean on Listing model', function () {
    $listing = seedBakuListing(['has_bill_of_sale' => true]);
    $fresh = Listing::find($listing->id);

    expect($fresh->has_bill_of_sale)->toBeBool()->toBeTrue();
});

test('has_mortgage is cast to boolean on Listing model', function () {
    $listing = seedBakuListing(['has_mortgage' => false]);
    $fresh = Listing::find($listing->id);

    expect($fresh->has_mortgage)->toBeBool()->toBeFalse();
});

test('has_bill_of_sale false is cast correctly and not confused with null', function () {
    $listing = seedBakuListing(['has_bill_of_sale' => false]);
    $fresh = Listing::find($listing->id);

    expect($fresh->has_bill_of_sale)->toBeFalse()
        ->and($fresh->has_bill_of_sale)->not->toBeNull();
});

// ---------------------------------------------------------------------------
// calculateListingStats — AZN prices
// ---------------------------------------------------------------------------

test('getDistrictStats returns AZN prices when extras contain original_price_azn', function () {
    seedBakuListing([
        'district' => 'Sabunchu',
        'price' => 58824,
        'extras' => ['original_price_azn' => 100000],
    ]);
    seedBakuListing([
        'district' => 'Sabunchu',
        'price' => 117647,
        'extras' => ['original_price_azn' => 200000],
    ]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Sabunchu'), true);

    expect($result['stats']['min_price_azn'])->toBe(100000)
        ->and($result['stats']['max_price_azn'])->toBe(200000)
        ->and($result['stats']['avg_price_azn'])->toBe(150000)
        ->and($result['stats']['median_price_azn'])->toBe(150000);
});

test('getDistrictStats returns null AZN prices when extras are empty', function () {
    seedBakuListing(['district' => 'Surakhani', 'extras' => []]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Surakhani'), true);

    expect($result['stats']['min_price_azn'])->toBeNull()
        ->and($result['stats']['max_price_azn'])->toBeNull();
});

// ---------------------------------------------------------------------------
// calculateListingStats — area ranges
// ---------------------------------------------------------------------------

test('getDistrictStats returns area ranges with correct counts', function () {
    seedBakuListing(['district' => 'Khatai', 'area' => 500,   'price' => 50000,  'price_per_sqm' => 100, 'extras' => ['original_price_azn' => 85000]]);
    seedBakuListing(['district' => 'Khatai', 'area' => 800,   'price' => 80000,  'price_per_sqm' => 100, 'extras' => ['original_price_azn' => 136000]]);
    seedBakuListing(['district' => 'Khatai', 'area' => 2000,  'price' => 150000, 'price_per_sqm' => 75,  'extras' => ['original_price_azn' => 255000]]);
    seedBakuListing(['district' => 'Khatai', 'area' => 15000, 'price' => 500000, 'price_per_sqm' => 33,  'extras' => ['original_price_azn' => 850000]]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Khatai'), true);

    $ranges = $result['stats']['area_ranges'];

    expect(count($ranges))->toBe(4)
        ->and($ranges[0]['range'])->toContain('0–1,000')
        ->and($ranges[0]['count'])->toBe(2)
        ->and($ranges[1]['count'])->toBe(1)
        ->and($ranges[2]['count'])->toBe(0)
        ->and($ranges[3]['count'])->toBe(1);
});

test('getDistrictStats area ranges have no sotka fields', function () {
    seedBakuListing(['district' => 'Yasamal', 'area' => 500, 'extras' => ['original_price_azn' => 85000]]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Yasamal'), true);

    $range = $result['stats']['area_ranges'][0];

    expect($range)->not->toHaveKey('min_area_sotka')
        ->and($range)->not->toHaveKey('max_area_sotka');
});

test('getDistrictStats overall stats have no sotka fields', function () {
    seedBakuListing(['district' => 'Pirallahi', 'area' => 500]);

    $agent = makeStatsAgent();
    $result = json_decode($agent->getDistrictStats('Pirallahi'), true);

    expect($result['stats'])->not->toHaveKey('min_area_sotka')
        ->and($result['stats'])->not->toHaveKey('max_area_sotka')
        ->and($result['stats'])->not->toHaveKey('avg_area_sotka');
});
