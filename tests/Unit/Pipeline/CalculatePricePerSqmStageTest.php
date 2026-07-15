<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\CalculatePricePerSqmStage;
use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(ExchangeRateService::class);
    $this->stage = new CalculatePricePerSqmStage($this->service);
});

test('it calculates price per sqm from price and area', function () {
    $dto = makeListing(['price' => 150000.0, 'area' => 75.0, 'currency' => 'USD']);

    $result = $this->stage->handle($dto);

    expect($result->pricePerSqm)->toBe(2000.0);
});

test('it returns null price per sqm when price is null', function () {
    $dto = makeListing(['price' => null, 'area' => 75.0]);

    $result = $this->stage->handle($dto);

    expect($result->pricePerSqm)->toBeNull();
});

test('it returns null price per sqm when area is null', function () {
    $dto = makeListing(['price' => 150000.0, 'area' => null]);

    $result = $this->stage->handle($dto);

    expect($result->pricePerSqm)->toBeNull();
});

test('it returns null price per sqm when area is zero', function () {
    $dto = makeListing(['price' => 150000.0, 'area' => 0.0]);

    $result = $this->stage->handle($dto);

    expect($result->pricePerSqm)->toBeNull();
});

test('it converts AMD price to USD using stored rate', function () {
    DB::table('settings')->insert([
        'key' => 'amd_usd_rate',
        'value' => '400.0',
        'group' => 'market',
        'type' => 'text',
        'label' => 'AMD/USD',
        'description' => 'Test rate',
        'order' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $dto = makeListing(['price' => 40000000.0, 'area' => 100.0, 'currency' => 'AMD']);

    $result = $this->stage->handle($dto);

    expect($result->price)->toBe(100000.0)  // 40000000 / 400
        ->and($result->pricePerSqm)->toBe(1000.0); // 100000 / 100
});

test('it converts AZN price to USD using stored rate', function () {
    DB::table('settings')->insert([
        'key' => 'azn_usd_rate',
        'value' => '1.7',
        'group' => 'market',
        'type' => 'text',
        'label' => 'AZN/USD',
        'description' => 'Test rate',
        'order' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $dto = makeListing(['price' => 170000.0, 'area' => 100.0, 'currency' => 'AZN']);

    $result = $this->stage->handle($dto);

    expect($result->price)->toBe(100000.0)  // 170000 / 1.7
        ->and($result->pricePerSqm)->toBe(1000.0); // 100000 / 100
});

test('it falls back to config rate when settings empty', function () {
    $dto = makeListing(['price' => 40000000.0, 'area' => 100.0, 'currency' => 'AMD']);

    $fallbackRate = (float) config('scraper.exchange_rates.amd_usd');
    $expectedPrice = round(40000000.0 / $fallbackRate, 2);
    $expectedPsqm = round($expectedPrice / 100.0, 2);

    $result = $this->stage->handle($dto);

    expect($result->price)->toBe($expectedPrice)
        ->and($result->pricePerSqm)->toBe($expectedPsqm);
});

test('usd listing passes through unchanged', function () {
    $dto = makeListing(['price' => 150000.0, 'area' => 75.0, 'currency' => 'USD']);

    $result = $this->stage->handle($dto);

    expect($result->price)->toBe(150000.0)
        ->and($result->pricePerSqm)->toBe(2000.0);
});
