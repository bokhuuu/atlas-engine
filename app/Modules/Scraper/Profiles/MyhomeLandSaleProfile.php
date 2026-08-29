<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Land Plots for Sale — myhome.ge Tbilisi.
 *
 * URL: https://www.myhome.ge/en/real-estate/sale/land/tbilisi/
 *      ?deal_types=1&real_estate_types=4&cities=1&currency_id=1&CardView=1&page={page}
 */
class MyhomeLandSaleProfile extends AbstractMyhomeProfile
{
    public function getName(): string
    {
        return 'myhome_land_sale';
    }

    public function getDealType(): string
    {
        return 'sale';
    }

    public function getPropertyTypeSlug(): string
    {
        return 'land';
    }

    public function getDealTypeId(): int
    {
        return 1;
    }

    public function getRealEstateTypeId(): int
    {
        return 4;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'land';
    }
}
