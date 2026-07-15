<?php

declare(strict_types=1);

use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('getRate returns 1.0 for USD', function () {
    $service = new ExchangeRateService;

    expect($service->getRate('USD'))->toBe(1.0);
});

test('getRate returns stored rate from settings', function () {
    DB::table('settings')->insert([
        'key' => 'amd_usd_rate',
        'value' => '370.5',
        'group' => 'market',
        'type' => 'text',
        'label' => 'AMD/USD Exchange Rate',
        'description' => 'Test',
        'order' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $service = new ExchangeRateService;

    expect($service->getRate('AMD'))->toBe(370.5);
});

test('getRate falls back to config when settings empty', function () {
    $service = new ExchangeRateService;

    $expected = (float) config('scraper.exchange_rates.amd_usd');

    expect($service->getRate('AMD'))->toBe($expected);
});

test('getRate returns 1.0 for unknown currency', function () {
    $service = new ExchangeRateService;

    expect($service->getRate('XYZ'))->toBe(1.0);
});

test('fetch stores rates in settings table', function () {
    Http::fake([
        'open.er-api.com/*' => Http::response([
            'result' => 'success',
            'rates' => [
                'AMD' => 366.85,
                'AZN' => 1.70,
                'GEL' => 2.63,
            ],
        ], 200),
    ]);

    $service = new ExchangeRateService;
    $result = $service->fetch();

    expect($result)->toBeTrue();

    $amd = DB::table('settings')->where('key', 'amd_usd_rate')->first();
    expect((float) $amd->value)->toBe(366.85);

    $azn = DB::table('settings')->where('key', 'azn_usd_rate')->first();
    expect((float) $azn->value)->toBe(1.70);
});

test('fetch returns false when API fails', function () {
    Http::fake([
        'open.er-api.com/*' => Http::response([], 500),
    ]);

    $service = new ExchangeRateService;
    $result = $service->fetch();

    expect($result)->toBeFalse();
});

test('fetch returns false when API returns non-success result', function () {
    Http::fake([
        'open.er-api.com/*' => Http::response([
            'result' => 'error',
        ], 200),
    ]);

    $service = new ExchangeRateService;
    $result = $service->fetch();

    expect($result)->toBeFalse();
});
