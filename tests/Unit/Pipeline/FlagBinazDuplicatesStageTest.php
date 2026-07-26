<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\FlagBinazDuplicatesStage;

$stage = new FlagBinazDuplicatesStage;

test('returns dto unchanged when district is null', function () use ($stage) {
    $dto = makeListing(['district' => null, 'area' => 1000.0]);
    $result = $stage->handle($dto);
    expect($result->extras)->not->toHaveKey('duplicate_group_id');
});

test('returns dto unchanged when area is null', function () use ($stage) {
    $dto = makeListing(['district' => 'Khazar', 'area' => null]);
    $result = $stage->handle($dto);
    expect($result->extras)->not->toHaveKey('duplicate_group_id');
});

test('returns dto unchanged when no matching listing exists', function () use ($stage) {
    $dto = makeListing([
        'district' => 'Khazar',
        'area' => 1000.0,
        'price' => 50000.0,
        'phone' => '0501234567',
        'source_profile_name' => 'binaz_land_sale',
    ]);
    $result = $stage->handle($dto);
    expect($result->extras)->not->toHaveKey('duplicate_group_id');
});
