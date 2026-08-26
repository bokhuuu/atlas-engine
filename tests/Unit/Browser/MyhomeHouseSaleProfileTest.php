<?php

declare(strict_types=1);

use App\Modules\Scraper\Profiles\MyhomeHouseSaleProfile;

$profile = new MyhomeHouseSaleProfile;

test('house sale profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_houses_sale');
});

test('house sale profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('sale');
});

test('house sale profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('house');
});

test('house sale profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(1);
});

test('house sale profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(2);
});

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/sale/private-house/tbilisi/'
            .'?deal_types=1&real_estate_types=2&cities=1&currency_id=1&CardView=1&page=1'
    );
});
