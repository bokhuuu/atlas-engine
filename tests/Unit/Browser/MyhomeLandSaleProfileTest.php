<?php

declare(strict_types=1);

use App\Modules\Scraper\Profiles\MyhomeLandSaleProfile;

$profile = new MyhomeLandSaleProfile;

test('land sale profile returns correct name', function () use ($profile) {
    expect($profile->getName())->toBe('myhome_land_sale');
});

test('land sale profile returns correct listing type', function () use ($profile) {
    expect($profile->getListingType())->toBe('sale');
});

test('land sale profile returns correct property type', function () use ($profile) {
    expect($profile->getPropertyType())->toBe('land');
});

test('land sale profile returns correct deal type id', function () use ($profile) {
    expect($profile->getDealTypeId())->toBe(1);
});

test('land sale profile returns correct real estate type id', function () use ($profile) {
    expect($profile->getRealEstateTypeId())->toBe(4);
});

test('it builds correct index URL for page 1', function () use ($profile) {
    expect($profile->buildIndexUrl(1))->toBe(
        'https://www.myhome.ge/en/real-estate/sale/land/tbilisi/'
            .'?deal_types=1&real_estate_types=4&cities=1&currency_id=1&CardView=1&page=1'
    );
});
