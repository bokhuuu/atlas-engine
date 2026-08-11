<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\CleanBuildingTypeStage;

$stage = new CleanBuildingTypeStage;

test('it preserves building type casing', function () use ($stage) {
    $dto = makeListing(['building_type' => 'Panel']);

    $result = $stage->handle($dto);

    expect($result->buildingType)->toBe('Panel');
});

test('it collapses multiple spaces into one', function () use ($stage) {
    $dto = makeListing(['building_type' => 'Monolithic  Frame']);

    $result = $stage->handle($dto);

    expect($result->buildingType)->toBe('Monolithic Frame');
});

test('it returns null when building type is null', function () use ($stage) {
    $dto = makeListing(['building_type' => null]);

    $result = $stage->handle($dto);

    expect($result->buildingType)->toBeNull();
});
