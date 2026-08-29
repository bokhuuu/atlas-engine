<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Apartments for Sale — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/sale/apartment/tbilisi/
 *      ?deal_types=1&real_estate_types=1&cities=1&currency_id=1&CardView=1&page={page}
 *
 * First myhome.ge profile — used to verify selectors and data quality
 * before adding remaining 5 categories.
 */
class MyhomeApartmentSaleProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_apartments_sale';
    }

    public function getDealType(): string
    {
        return 'sale';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'apartment';
    }

    public function getDealTypeId(): int
    {
        return 1;
    }

    public function getRealEstateTypeId(): int
    {
        return 1;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'apartment';
    }
}
