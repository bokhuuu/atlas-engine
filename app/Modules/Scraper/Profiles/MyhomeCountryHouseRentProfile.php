<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Country Houses for Rent — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/rent/country-house/tbilisi/
 *      ?deal_types=2&real_estate_types=3&cities=1&currency_id=1&CardView=1&page={page}
 */
class MyhomeCountryHouseRentProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_country_houses_rent';
    }

    public function getDealType(): string
    {
        return 'rent';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'country-house';
    }

    public function getDealTypeId(): int
    {
        return 2;
    }

    public function getRealEstateTypeId(): int
    {
        return 3;
    }

    public function getListingType(): string
    {
        return 'rent';
    }

    public function getPropertyType(): string
    {
        return 'house';
    }
}
