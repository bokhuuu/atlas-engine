<?php

declare(strict_types=1);

use App\Modules\Scraper\Profiles\MyhomeCountryHouseSaleProfile;

$profile = new MyhomeCountryHouseSaleProfile;

test('country house sale profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_country_houses_sale');
});

test('country house sale profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('sale');
});

test('country house sale profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('house');
});

test('country house sale profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(1);
});

test('country house sale profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(3);
});

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/sale/country-house/tbilisi/'
            .'?deal_types=1&real_estate_types=3&cities=1&currency_id=1&CardView=1&page=1'
    );
});
