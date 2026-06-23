<?php

declare(strict_types=1);

use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Repositories\ListingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$repository = new ListingRepository;

function listingData(array $overrides = []): array
{
    return array_merge([
        'external_id' => 'test-123',
        'source_profile_name' => 'listam',
        'url' => 'https://list.am/en/item/123',
        'listing_type' => 'sale',
        'property_type' => 'apartment',
        'price' => 150000.0,
        'currency' => 'USD',
        'price_per_sqm' => 2000.0,
        'area' => 75.0,
        'images' => [],
        'extras' => [],
        'scraped_at' => now()->toDateTimeString(),
    ], $overrides);
}

test('it saves a listing to the database', function () use ($repository) {
    $repository->updateOrCreate(listingData());

    expect(Listing::count())->toBe(1);
});

test('it updates an existing listing instead of creating a duplicate', function () use ($repository) {
    $repository->updateOrCreate(listingData(['price' => 150000.0]));
    $repository->updateOrCreate(listingData(['price' => 160000.0]));

    expect(Listing::count())->toBe(1);
    expect(Listing::first()->price)->toBe(160000.0);
});

test('it returns true when listing exists by external id', function () use ($repository) {
    $repository->updateOrCreate(listingData());

    expect($repository->existsByExternalId('test-123', 'listam'))->toBeTrue();
});

test('it returns false when listing does not exist', function () use ($repository) {
    expect($repository->existsByExternalId('listam', 'nonexistent'))->toBeFalse();
});

test('it finds listings by source', function () use ($repository) {
    $repository->updateOrCreate(listingData());
    $repository->updateOrCreate(listingData([
        'external_id' => 'test-456',
        'url' => 'https://list.am/en/item/456',
    ]));

    $results = $repository->findBySource('listam');

    expect($results)->toHaveCount(2);
});

test('it counts listings by source', function () use ($repository) {
    $repository->updateOrCreate(listingData());

    expect($repository->countBySource('listam'))->toBe(1);
});

test('it averages duplicate groups as one data point in district stats', function () {
    $repository = new ListingRepository;
    $repository->updateOrCreate(listingData(['external_id' => 'dup-1', 'district' => 'Kentron', 'price_per_sqm' => 100, 'duplicate_group_id' => null]));
    $listing = Listing::where('external_id', 'dup-1')->first();
    $listing->update(['duplicate_group_id' => $listing->id]);

    $repository->updateOrCreate(listingData(['external_id' => 'dup-2', 'district' => 'Kentron', 'price_per_sqm' => 120, 'duplicate_group_id' => $listing->id]));

    $repository->updateOrCreate(listingData(['external_id' => 'standalone', 'district' => 'Kentron', 'price_per_sqm' => 200]));

    $stats = $repository->getDistrictPriceStats('listam');
    $kentron = $stats->firstWhere('district', 'Kentron');

    expect((float) $kentron->avg_price_per_sqm)->toBe(155.0);
    expect((int) $kentron->listing_count)->toBe(2);
});

test('it returns null median price per sqm when source has no data', function () use ($repository) {
    expect($repository->getMedianPricePerSqm('listam'))->toBeNull();
});

test('it calculates median price per sqm for an odd number of listings', function () use ($repository) {
    $repository->updateOrCreate(listingData(['external_id' => 'm-1', 'price_per_sqm' => 100]));
    $repository->updateOrCreate(listingData(['external_id' => 'm-2', 'url' => 'https://list.am/en/item/m-2', 'price_per_sqm' => 200]));
    $repository->updateOrCreate(listingData(['external_id' => 'm-3', 'url' => 'https://list.am/en/item/m-3', 'price_per_sqm' => 300]));

    expect($repository->getMedianPricePerSqm('listam'))->toBe(200.0);
});

test('it calculates median price per sqm for an even number of listings by averaging the two middle values', function () use ($repository) {
    $repository->updateOrCreate(listingData(['external_id' => 'm-1', 'price_per_sqm' => 100]));
    $repository->updateOrCreate(listingData(['external_id' => 'm-2', 'url' => 'https://list.am/en/item/m-2', 'price_per_sqm' => 200]));
    $repository->updateOrCreate(listingData(['external_id' => 'm-3', 'url' => 'https://list.am/en/item/m-3', 'price_per_sqm' => 300]));
    $repository->updateOrCreate(listingData(['external_id' => 'm-4', 'url' => 'https://list.am/en/item/m-4', 'price_per_sqm' => 400]));

    expect($repository->getMedianPricePerSqm('listam'))->toBe(250.0);
});

test('it treats a duplicate group as one data point when calculating median price per sqm', function () use ($repository) {
    $repository->updateOrCreate(listingData(['external_id' => 'dup-1', 'price_per_sqm' => 100]));
    $listing = Listing::where('external_id', 'dup-1')->first();
    $listing->update(['duplicate_group_id' => $listing->id]);

    $repository->updateOrCreate(listingData(['external_id' => 'dup-2', 'url' => 'https://list.am/en/item/dup-2', 'price_per_sqm' => 120, 'duplicate_group_id' => $listing->id]));
    $repository->updateOrCreate(listingData(['external_id' => 'standalone', 'url' => 'https://list.am/en/item/standalone', 'price_per_sqm' => 200]));

    expect($repository->getMedianPricePerSqm('listam'))->toBe(155.0);
});

test('it counts distinct properties by source, collapsing duplicate groups into one', function () use ($repository) {
    $repository->updateOrCreate(listingData(['external_id' => 'dup-1', 'price_per_sqm' => 100]));
    $listing = Listing::where('external_id', 'dup-1')->first();
    $listing->update(['duplicate_group_id' => $listing->id]);

    $repository->updateOrCreate(listingData(['external_id' => 'dup-2', 'url' => 'https://list.am/en/item/dup-2', 'price_per_sqm' => 120, 'duplicate_group_id' => $listing->id]));
    $repository->updateOrCreate(listingData(['external_id' => 'standalone', 'url' => 'https://list.am/en/item/standalone', 'price_per_sqm' => 200]));

    expect(Listing::count())->toBe(3);
    expect($repository->countDistinctBySource('listam'))->toBe(2);
});
