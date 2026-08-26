<?php

declare(strict_types=1);

use App\Modules\Scraper\Profiles\MyhomeApartmentRentProfile;

$profile = new MyhomeApartmentRentProfile;

test('apartment rent profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_apartments_rent');
});

test('apartment rent profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('rent');
});

test('apartment rent profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('apartment');
});

test('apartment rent profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(2);
});

test('apartment rent profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(1);
});

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/rent/apartment/tbilisi/'
            .'?deal_types=2&real_estate_types=1&cities=1&currency_id=1&CardView=1&page=1'
    );
});
