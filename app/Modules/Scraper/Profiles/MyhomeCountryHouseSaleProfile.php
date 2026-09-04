<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Country Houses for Sale — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/sale/country-house/tbilisi/
 *      ?deal_types=1&real_estate_types=3&cities=1&currency_id=1&CardView=1&page={page}
 */
class MyhomeCountryHouseSaleProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_country_houses_sale';
    }

    public function getDealType(): string
    {
        return 'sale';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'country-house';
    }

    public function getDealTypeId(): int
    {
        return 1;
    }

    public function getRealEstateTypeId(): int
    {
        return 3;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'country_house';
    }
}
