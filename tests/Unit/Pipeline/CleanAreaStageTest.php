<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\CleanAreaStage;

$stage = new CleanAreaStage;

test('it cleans a formatted area string to a float', function () use ($stage) {
    $dto = makeListing(['area' => '75.5 m²']);

    $result = $stage->handle($dto);

    expect($result->area)->toBe(75.5);
});

test('it strips thousands-separator commas instead of treating them as decimals', function () use ($stage) {
    $dto = makeListing(['area' => '5,412 sq.m.']);

    $result = $stage->handle($dto);

    expect($result->area)->toBe(5412.0);
});

test('it ignores periods inside unit abbreviations like sq.m.', function () use ($stage) {
    $dto = makeListing(['area' => '1,234 sq.m.']);

    $result = $stage->handle($dto);

    expect($result->area)->toBe(1234.0);
});

test('it returns null when area is null', function () use ($stage) {
    $dto = makeListing(['area' => null]);

    $result = $stage->handle($dto);

    expect($result->area)->toBeNull();
});

test('it returns null when area is zero', function () use ($stage) {
    $dto = makeListing(['area' => '0']);

    $result = $stage->handle($dto);

    expect($result->area)->toBeNull();
});

test('it also cleans land_area in extras when present', function () use ($stage) {
    $dto = makeListing(['extras' => ['land_area' => '1,800 sq.m.']]);

    $result = $stage->handle($dto);

    expect($result->extras['land_area'])->toBe(1800.0);
});

test('it leaves extras untouched when land_area is not present', function () use ($stage) {
    $dto = makeListing(['extras' => ['handover_date' => 'Q3 2027']]);

    $result = $stage->handle($dto);

    expect($result->extras)->toBe(['handover_date' => 'Q3 2027']);
});

test('it rejects implausibly large values as bad source data', function () use ($stage) {
    $dto = makeListing(['area' => '1,234,556,677 sq.m.']);

    $result = $stage->handle($dto);

    expect($result->area)->toBeNull();
});
