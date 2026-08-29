<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Commercial Areas for Rent — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/rent/commercial-area/tbilisi/
 *      ?deal_types=2&real_estate_types=5&cities=1&currency_id=1&CardView=1&page={page}
 */
class MyhomeCommercialRentProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_commercial_rent';
    }

    public function getDealType(): string
    {
        return 'rent';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'commercial-area';
    }

    public function getDealTypeId(): int
    {
        return 2;
    }

    public function getRealEstateTypeId(): int
    {
        return 5;
    }

    public function getListingType(): string
    {
        return 'rent';
    }

    public function getPropertyType(): string
    {
        return 'commercial';
    }
}
