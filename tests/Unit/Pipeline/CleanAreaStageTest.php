<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\CleanAreaStage;

$stage = new CleanAreaStage;

test('it cleans a formatted area string to a float', function () use ($stage) {
    $dto = makeListing(['area' => '75.5 m²']);

    $result = $stage->handle($dto);

    expect($result->area)->toBe(75.5);
});

test('it normalizes comma decimal separator to period', function () use ($stage) {
    $dto = makeListing(['area' => '75,5']);

    $result = $stage->handle($dto);

    expect($result->area)->toBe(75.5);
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
    $dto = makeListing(['extras' => ['land_area' => '350 m²']]);

    $result = $stage->handle($dto);

    expect($result->extras['land_area'])->toBe(350.0);
});

test('it leaves extras untouched when land_area is not present', function () use ($stage) {
    $dto = makeListing(['extras' => ['handover_date' => 'Q3 2027']]);

    $result = $stage->handle($dto);

    expect($result->extras)->toBe(['handover_date' => 'Q3 2027']);
});
