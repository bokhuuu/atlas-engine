<?php

declare(strict_types=1);

use App\Modules\AI\Agents\PropertyAssistantAgent;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeAgent(): PropertyAssistantAgent
{
    return new PropertyAssistantAgent('test_session_'.uniqid());
}

function seedListing(array $overrides = []): Listing
{
    return Listing::create(array_merge([
        'external_id' => uniqid('ext_'),
        'source_profile_name' => 'listam_apartments_sale',
        'url' => 'https://list.am/en/item/'.rand(1000, 9999),
        'listing_type' => 'sale',
        'property_type' => 'apartment',
        'price' => 150000,
        'currency' => 'USD',
        'price_per_sqm' => 2000,
        'area' => 75,
        'district' => 'Kentron',
        'images' => [],
        'extras' => [],
        'scraped_at' => now(),
    ], $overrides));
}

test('getPriceChanges returns no reductions message when none exist', function () {
    $agent = makeAgent();
    $result = json_decode($agent->getPriceChanges('listam_apartments_sale'), true);

    expect($result)->toHaveKey('message')
        ->and($result['message'])->toContain('No price reductions');
});

test('getPriceChanges returns error for unknown source', function () {
    $agent = makeAgent();
    $result = json_decode($agent->getPriceChanges('listam_unknown'), true);

    expect($result)->toHaveKey('error');
});

test('getPriceChanges returns districts with reductions when they exist', function () {
    seedListing([
        'district' => 'Kentron',
        'price_change_pct' => -5.5,
        'previous_price' => 160000,
        'price_changed_at' => now()->subDays(2),
    ]);

    $agent = makeAgent();
    $result = json_decode($agent->getPriceChanges('listam_apartments_sale'), true);

    expect($result)->toHaveKey('districts')
        ->and($result['districts'][0]['district'])->toBe('Kentron')
        ->and($result['districts'][0]['reduction_count'])->toBe(1);
});

test('getYieldEstimate returns no data message when insufficient listings', function () {
    $agent = makeAgent();
    $result = json_decode($agent->getYieldEstimate('residential'), true);

    expect($result)->toHaveKey('message')
        ->and($result['message'])->toContain('Not enough data');
});

test('getYieldEstimate returns error gracefully for unknown pair falls back to residential', function () {
    $agent = makeAgent();
    $result = json_decode($agent->getYieldEstimate('unknown_pair'), true);

    expect($result)->toHaveKey('message');
});

test('semanticSearch returns no data message when documents table is empty', function () {
    $agent = makeAgent();
    $result = json_decode($agent->semanticSearch('apartment with renovation'), true);

    expect($result)->toHaveKey('message')
        ->and($result['message'])->toContain('No listing text has been indexed');
});
