<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\NormalizeToEnglishMyhomeStage;

$stage = new NormalizeToEnglishMyhomeStage;

// -------------------------------------------------------------------------
// Sub-district → parent district mapping
// -------------------------------------------------------------------------

test('it maps Saburtalo to Vake-Saburtalo', function () use ($stage) {
    $dto = makeListing(['district' => 'Saburtalo']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Vake-Saburtalo');
});

test('it maps Vake to Vake-Saburtalo', function () use ($stage) {
    $dto = makeListing(['district' => 'Vake']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Vake-Saburtalo');
});

test('it maps Varketili to Isani-Samgori', function () use ($stage) {
    $dto = makeListing(['district' => 'Varketili']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Isani-Samgori');
});

test('it maps Gldani to Gldani-Nadzaladevi', function () use ($stage) {
    $dto = makeListing(['district' => 'Gldani']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Gldani-Nadzaladevi');
});

test('it maps Didube to Didube-Chugureti', function () use ($stage) {
    $dto = makeListing(['district' => 'Didube']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Didube-Chugureti');
});

test('it maps Kojori to Suburbs of Tbilisi', function () use ($stage) {
    $dto = makeListing(['district' => 'Kojori']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Suburbs of Tbilisi');
});

test('it maps Mtatsminda to Didube-Chugureti', function () use ($stage) {
    $dto = makeListing(['district' => 'Mtatsminda']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Didube-Chugureti');
});

// -------------------------------------------------------------------------
// Sub-district stored in extras
// -------------------------------------------------------------------------

test('it stores sub-district in extras when mapping to parent', function () use ($stage) {
    $dto = makeListing(['district' => 'Saburtalo']);
    $result = $stage->handle($dto);
    expect($result->extras['sub_district'])->toBe('Saburtalo');
});

test('it stores Gldani as sub-district in extras', function () use ($stage) {
    $dto = makeListing(['district' => 'Gldani']);
    $result = $stage->handle($dto);
    expect($result->extras['sub_district'])->toBe('Gldani');
});

// -------------------------------------------------------------------------
// Parent district pass-through
// -------------------------------------------------------------------------

test('it passes through a known parent district unchanged', function () use ($stage) {
    $dto = makeListing(['district' => 'Vake-Saburtalo']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Vake-Saburtalo');
});

test('it passes through Isani-Samgori unchanged', function () use ($stage) {
    $dto = makeListing(['district' => 'Isani-Samgori']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Isani-Samgori');
});

// -------------------------------------------------------------------------
// Edge cases
// -------------------------------------------------------------------------

test('it handles null district gracefully', function () use ($stage) {
    $dto = makeListing(['district' => null]);
    $result = $stage->handle($dto);
    expect($result->district)->toBeNull();
});

test('it passes through unknown district unchanged', function () use ($stage) {
    $dto = makeListing(['district' => 'UnknownPlace']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('UnknownPlace');
});

test('it trims extra whitespace before matching', function () use ($stage) {
    $dto = makeListing(['district' => '  Saburtalo  ']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Vake-Saburtalo');
});

test('it collapses multiple spaces before matching', function () use ($stage) {
    $dto = makeListing(['district' => 'Didi  digomi']);
    $result = $stage->handle($dto);
    expect($result->district)->toBe('Vake-Saburtalo');
});
