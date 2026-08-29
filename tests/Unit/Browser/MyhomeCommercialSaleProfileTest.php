<?php

declare(strict_types=1);

use App\Modules\Scraper\Profiles\MyhomeCommercialSaleProfile;

$profile = new MyhomeCommercialSaleProfile;

test('commercial sale profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_commercial_sale');
});

test('commercial sale profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('sale');
});

test('commercial sale profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('commercial');
});

test('commercial sale profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(1);
});

test('commercial sale profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(5);
});

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/sale/commercial-area/tbilisi/'
            .'?deal_types=1&real_estate_types=5&cities=1&currency_id=1&CardView=1&page=1'
    );
});
