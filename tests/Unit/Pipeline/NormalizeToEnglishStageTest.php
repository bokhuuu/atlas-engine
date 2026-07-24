<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\NormalizeToEnglishStage;

$stage = new NormalizeToEnglishStage;

test('it translates a known Russian district to English', function () use ($stage) {
    $dto = makeListing(['district' => 'Бузовна']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Khazar');
});

test('it translates a known Russian building type to English', function () use ($stage) {
    $dto = makeListing(['building_type' => 'Бизнес центр']);

    $result = $stage->handle($dto);

    expect($result->buildingType)->toBe('Business Center');
});

test('it translates a known Russian condition to English', function () use ($stage) {
    $dto = makeListing(['condition' => 'С ремонтом']);

    $result = $stage->handle($dto);

    expect($result->condition)->toBe('Renovated');
});

test('it passes through unknown district values unchanged', function () use ($stage) {
    $dto = makeListing(['district' => 'UnknownPlace']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('UnknownPlace');
});

test('it handles null district gracefully', function () use ($stage) {
    $dto = makeListing(['district' => null]);

    $result = $stage->handle($dto);

    expect($result->district)->toBeNull();
});

test('it handles null building type gracefully', function () use ($stage) {
    $dto = makeListing(['building_type' => null]);

    $result = $stage->handle($dto);

    expect($result->buildingType)->toBeNull();
});

test('it trims whitespace before matching', function () use ($stage) {
    $dto = makeListing(['district' => ' Наримановский ']);

    $result = $stage->handle($dto);

    expect($result->district)->toBe('Narimanov');
});
