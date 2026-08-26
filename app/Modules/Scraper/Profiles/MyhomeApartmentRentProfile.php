<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Apartments for Rent — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/rent/apartment/tbilisi/
 *      ?deal_types=2&real_estate_types=1&cities=1&currency_id=1&CardView=1&page={page}
 */
class MyhomeApartmentRentProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_apartments_rent';
    }

    public function getDealType(): string
    {
        return 'rent';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'apartment';
    }

    public function getDealTypeId(): int
    {
        return 2;
    }

    public function getRealEstateTypeId(): int
    {
        return 1;
    }

    public function getListingType(): string
    {
        return 'rent';
    }

    public function getPropertyType(): string
    {
        return 'apartment';
    }
}
