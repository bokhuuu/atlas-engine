<?php

declare(strict_types=1);

use App\Modules\Scraper\Profiles\MyhomeCountryHouseRentProfile;

$profile = new MyhomeCountryHouseRentProfile;

test('country house rent profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_country_houses_rent');
});

test('country house rent profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('rent');
});

test('country house rent profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('country_house');
});

test('country house rent profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(2);
});

test('country house rent profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(3);
});

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/rent/country-house/tbilisi/'
            .'?deal_types=2&real_estate_types=3&cities=1&currency_id=1&CardView=1&page=1'
    );
});
