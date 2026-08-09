<?php

declare(strict_types=1);

use App\Modules\Scraper\Exports\LandSizeRangeSheet;
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

test('land size range sheet has header and overall row and four range rows', function () {
    $listings = [
        ['area' => 500, 'price' => 50000, 'price_per_sqm' => 100, 'extras' => ['original_price_azn' => 85000]],
        ['area' => 2000, 'price' => 150000, 'price_per_sqm' => 75, 'extras' => ['original_price_azn' => 255000]],
        ['area' => 6000, 'price' => 300000, 'price_per_sqm' => 50, 'extras' => ['original_price_azn' => 510000]],
        ['area' => 15000, 'price' => 500000, 'price_per_sqm' => 33, 'extras' => ['original_price_azn' => 850000]],
    ];

    $sheet = new LandSizeRangeSheet($listings);
    $rows = $sheet->array();

    // header + overall + blank + 4 ranges = 7 rows
    expect(count($rows))->toBe(7);
    expect($rows[0][0])->toBe('Metric / Size Range');
    expect($rows[1][0])->toBe('OVERALL');
    expect($rows[1][1])->toBe(4);
});

test('land size range sheet counts listings correctly per range', function () {
    $listings = [
        ['area' => 500,   'price' => 50000,  'price_per_sqm' => 100, 'extras' => ['original_price_azn' => 85000]],
        ['area' => 800,   'price' => 80000,  'price_per_sqm' => 100, 'extras' => ['original_price_azn' => 136000]],
        ['area' => 2000,  'price' => 150000, 'price_per_sqm' => 75,  'extras' => ['original_price_azn' => 255000]],
        ['area' => 15000, 'price' => 500000, 'price_per_sqm' => 33,  'extras' => ['original_price_azn' => 850000]],
    ];

    $sheet = new LandSizeRangeSheet($listings);
    $rows = $sheet->array();

    // row 3 = blank, row 4 = first range (0-1000 sqm) = 2 listings
    expect($rows[3][1])->toBe(2);
    // row 5 = second range (1001-5000 sqm) = 1 listing
    expect($rows[4][1])->toBe(1);
});
