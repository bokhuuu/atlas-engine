<?php

declare(strict_types=1);

use App\Modules\Scraper\Pipeline\Stages\CalculatePricePerSqmStage;
use App\Modules\Scraper\Pipeline\Stages\CleanAreaStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPhoneStage;
use App\Modules\Scraper\Pipeline\Stages\CleanPriceStage;
use App\Modules\Scraper\Pipeline\Stages\DeduplicateStage;
use App\Modules\Scraper\Pipeline\Stages\DetectPriceChangeStage;
use App\Modules\Scraper\Pipeline\Stages\FlagProbableDuplicatesStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeStringFieldsStage;
use App\Modules\Scraper\Pipeline\Stages\NormalizeToEnglishMyhomeStage;
use App\Modules\Scraper\Pipeline\Stages\ValidateRequiredFieldsStage;
use App\Modules\Scraper\Profiles\MyhomeApartmentSaleProfile;

/**
 * Tests for AbstractMyhomeProfile via MyhomeApartmentSaleProfile.
 *
 * Covers URL building and pipeline composition — the two things
 * AbstractMyhomeProfile is responsible for.
 */
$profile = new MyhomeApartmentSaleProfile;

// -------------------------------------------------------------------------
// Profile identity
// -------------------------------------------------------------------------

test('apartment sale profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_apartments_sale');
});

test('apartment sale profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('sale');
});

test('apartment sale profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('apartment');
});

test('apartment sale profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(1);
});

test('apartment sale profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(1);
});

// -------------------------------------------------------------------------
// URL building
// -------------------------------------------------------------------------

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/sale/apartment/tbilisi/'
            .'?deal_types=1&real_estate_types=1&cities=1&currency_id=1&CardView=1&page=1'
    );
});

test('it builds correct index URL for page 3', function () use ($profile) {
    expect($profile->buildIndexUrl(3))->toContain('page=3');
});

test('index URL contains tbilisi city filter', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toContain('cities=1');
});

test('index URL contains GEL currency filter', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toContain('currency_id=1');
});

test('index URL contains card view parameter', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toContain('CardView=1');
});

test('base URL is myhome.ge', function () use ($profile) {
    expect($profile->getBaseUrl())->toBe('https://www.myhome.ge');
});

// -------------------------------------------------------------------------
// Pipeline composition
// -------------------------------------------------------------------------

test('pipeline includes CleanPhoneStage', function () use ($profile) {
    $stageClasses = array_map(fn ($s) => get_class($s), $profile->getPipelineStages());

    expect($stageClasses)->toContain(CleanPhoneStage::class);
});

test('pipeline includes NormalizeToEnglishMyhomeStage', function () use ($profile) {
    $stageClasses = array_map(fn ($s) => get_class($s), $profile->getPipelineStages());

    expect($stageClasses)->toContain(NormalizeToEnglishMyhomeStage::class);
});

test('pipeline includes all required stages', function () use ($profile) {
    $stageClasses = array_map(fn ($s) => get_class($s), $profile->getPipelineStages());

    expect($stageClasses)
        ->toContain(NormalizeStringFieldsStage::class)
        ->toContain(NormalizeToEnglishMyhomeStage::class)
        ->toContain(ValidateRequiredFieldsStage::class)
        ->toContain(CleanPriceStage::class)
        ->toContain(CleanPhoneStage::class)
        ->toContain(CleanAreaStage::class)
        ->toContain(CalculatePricePerSqmStage::class)
        ->toContain(DeduplicateStage::class)
        ->toContain(DetectPriceChangeStage::class)
        ->toContain(FlagProbableDuplicatesStage::class);
});

test('NormalizeToEnglishMyhomeStage runs before ValidateRequiredFieldsStage', function () use ($profile) {
    $stageClasses = array_map(fn ($s) => get_class($s), $profile->getPipelineStages());

    $normalizeIndex = array_search(NormalizeToEnglishMyhomeStage::class, $stageClasses);
    $validateIndex = array_search(ValidateRequiredFieldsStage::class, $stageClasses);

    expect($normalizeIndex)->toBeLessThan($validateIndex);
});

// -------------------------------------------------------------------------
// Required fields
// -------------------------------------------------------------------------

test('required fields include sourceProfileName, externalId, url and price', function () use ($profile) {
    expect($profile->getRequiredFields())
        ->toContain('sourceProfileName')
        ->toContain('externalId')
        ->toContain('url')
        ->toContain('price');
});
