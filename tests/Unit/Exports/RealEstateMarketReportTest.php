<?php

declare(strict_types=1);

use App\Modules\Scraper\Exports\PriceDistributionSheet;
use App\Modules\Scraper\Exports\RealEstateMarketReport;

test('it collapses duplicate groups into one averaged row', function () {
    $data = [
        ['id' => 1, 'duplicate_group_id' => 1, 'price' => 100000, 'price_per_sqm' => 1000, 'area' => 100],
        ['id' => 2, 'duplicate_group_id' => 1, 'price' => 120000, 'price_per_sqm' => 1200, 'area' => 100],
        ['id' => 3, 'duplicate_group_id' => null, 'price' => 200000, 'price_per_sqm' => 2000, 'area' => 100],
    ];

    $result = RealEstateMarketReport::deduplicateForAggregation($data);

    expect($result)->toHaveCount(2);

    $collapsed = collect($result)->firstWhere('id', 1);
    expect((float) $collapsed['price'])->toBe(110000.0);
    expect((float) $collapsed['price_per_sqm'])->toBe(1100.0);

    $standalone = collect($result)->firstWhere('id', 3);
    expect($standalone['price'])->toBe(200000);
});

test('it uses sale brackets by default', function () {
    $sheet = new PriceDistributionSheet([
        ['listing_type' => 'sale', 'price' => 75000, 'area' => 50, 'price_per_sqm' => 1500],
    ]);

    expect(collect($sheet->array())->pluck(0))->toContain('$50,000 – $100,000');
});

test('it uses rent brackets when listing_type is rent', function () {
    $sheet = new PriceDistributionSheet([
        ['listing_type' => 'rent', 'price' => 400, 'area' => 50, 'price_per_sqm' => 8],
    ]);

    expect(collect($sheet->array())->pluck(0))->toContain('$300 – $500');
});
