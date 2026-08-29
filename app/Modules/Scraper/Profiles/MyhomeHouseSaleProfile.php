<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Houses for Sale — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/sale/private-house/tbilisi/
 *      ?deal_types=1&real_estate_types=2&cities=1&currency_id=1&CardView=1&page={page}
 */
class MyhomeHouseSaleProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_houses_sale';
    }

    public function getDealType(): string
    {
        return 'sale';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'private-house';
    }

    public function getDealTypeId(): int
    {
        return 1;
    }

    public function getRealEstateTypeId(): int
    {
        return 2;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'house';
    }
}
