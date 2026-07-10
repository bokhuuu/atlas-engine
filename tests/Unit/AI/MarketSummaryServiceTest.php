<?php

declare(strict_types=1);

use App\Modules\AI\Services\MarketSummaryService;
use App\Modules\AI\Services\TextGenerationService;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Services\MarketRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('summarize returns null when source has no listings', function () {
    $listingRepo = app(ListingRepository::class);
    $textGenerator = Mockery::mock(TextGenerationService::class);
    $textGenerator->shouldNotReceive('generate');

    $service = new MarketSummaryService($listingRepo, $textGenerator, app(MarketRegistry::class));

    expect($service->summarize('listam_apartments_sale'))->toBeNull();
});

test('summarize returns cached value on second call without calling AI again', function () {
    Cache::put('ai:market_summary:listam_apartments_sale', 'cached summary', 3600);

    $listingRepo = Mockery::mock(ListingRepository::class);
    $textGenerator = Mockery::mock(TextGenerationService::class);
    $textGenerator->shouldNotReceive('generate');

    $service = new MarketSummaryService($listingRepo, $textGenerator, app(MarketRegistry::class));

    expect($service->summarize('listam_apartments_sale'))->toBe('cached summary');
});

test('summarize calls text generator and returns summary when data exists', function () {
    $listingRepo = Mockery::mock(ListingRepository::class);
    $listingRepo->shouldReceive('countDistinctBySource')->andReturn(100);
    $listingRepo->shouldReceive('getMedianPricePerSqm')->andReturn(1200.0);
    $listingRepo->shouldReceive('getDistrictPriceStats')->andReturn(collect([
        (object) ['district' => 'Kentron', 'listing_count' => 40, 'avg_price_per_sqm' => '1800'],
        (object) ['district' => 'Arabkir', 'listing_count' => 30, 'avg_price_per_sqm' => '1100'],
    ]));
    $listingRepo->shouldReceive('getMedianPricePerSqmByDistrict')->andReturn(collect());

    $textGenerator = Mockery::mock(TextGenerationService::class);
    $textGenerator->shouldReceive('generate')->once()->andReturn('The Apartments for Sale market is active.');

    $service = new MarketSummaryService($listingRepo, $textGenerator, app(MarketRegistry::class));
    $result = $service->summarize('listam_apartments_sale');

    expect($result)->toBe('The Apartments for Sale market is active.');
});
