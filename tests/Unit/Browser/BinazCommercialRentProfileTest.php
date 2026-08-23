<?php

declare(strict_types=1);

use App\Modules\Scraper\Browser\BinazScraper;
use App\Modules\Scraper\Pipeline\Stages\CleanPhoneStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeToEnglishStage;
use App\Modules\Scraper\Profiles\BinazCommercialRentProfile;

$profile = new BinazCommercialRentProfile;

test('profile has correct name', function () use ($profile) {
    expect($profile->getName())->toBe('binaz_commercial_rent');
});

test('profile has correct category id', function () use ($profile) {
    expect($profile->getCategoryId())->toBe(10);
});

test('profile has correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('rent');
});

test('profile has correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('commercial');
});

test('profile returns max pages of 1 for sequential cursor pagination', function () use ($profile) {
    expect($profile->getMaxPages())->toBe(1);
});

test('profile uses BinazScraper', function () use ($profile) {
    expect($profile->getScraperClass())->toBe(BinazScraper::class);
});

test('profile pipeline includes NormalizeToEnglishStage', function () use ($profile) {
    $stages = $profile->getPipelineStages();

    $hasNormalizeStage = collect($stages)->contains(
        fn ($stage) => $stage instanceof NormalizeToEnglishStage
    );

    expect($hasNormalizeStage)->toBeTrue();
});

test('profile includes real estate report export', function () use ($profile) {
    expect($profile->getExports())->toContain('real_estate_report');
});

test('profile base url is bina az russian version', function () use ($profile) {
    expect($profile->getBaseUrl())->toBe('https://ru.bina.az');
});

test('profile has extended timeout for cursor pagination loop', function () use ($profile) {
    expect($profile->getQueueConfig()['timeout'])->toBeGreaterThanOrEqual(1800);
});

test('profile pipeline excludes CleanPhoneStage to preserve phone formatting', function () use ($profile) {
    $stages = $profile->getPipelineStages();

    $hasCleanPhone = collect($stages)->contains(
        fn ($stage) => $stage instanceof CleanPhoneStage
    );

    expect($hasCleanPhone)->toBeFalse();
});

test('profile does not filter by location id', function () use ($profile) {
    expect($profile->getLocationIds())->toBeNull();
});
