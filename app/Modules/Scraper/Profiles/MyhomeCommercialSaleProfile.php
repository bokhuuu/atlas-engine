<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Commercial Areas for Sale — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/sale/commercial-area/tbilisi/
 *      ?deal_types=1&real_estate_types=5&cities=1&currency_id=1&CardView=1&page={page}
 */
class MyhomeCommercialSaleProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_commercial_sale';
    }

    public function getDealType(): string
    {
        return 'sale';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'commercial-area';
    }

    public function getDealTypeId(): int
    {
        return 1;
    }

    public function getRealEstateTypeId(): int
    {
        return 5;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'commercial';
    }
}
