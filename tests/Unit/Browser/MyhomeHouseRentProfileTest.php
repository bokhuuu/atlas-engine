<?php

declare(strict_types=1);

use App\Modules\Scraper\Profiles\MyhomeHouseRentProfile;

$profile = new MyhomeHouseRentProfile;

test('house rent profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_houses_rent');
});

test('house rent profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('rent');
});

test('house rent profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('house');
});

test('house rent profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(2);
});

test('house rent profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(2);
});

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/rent/private-house/tbilisi/'
            .'?deal_types=2&real_estate_types=2&cities=1&currency_id=1&CardView=1&page=1'
    );
});
