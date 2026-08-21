<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Houses for Sale - bina.az category 5.
 *
 * Covers residential houses and dachas (Дом/Дача).
 * floor, total_floors, rooms extracted from detail page.
 * leased=false in GraphQL filter targets sale listings only.
 */
class BinazHouseSaleProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_houses_sale';
    }

    public function getCategoryId(): int
    {
        return 5;
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
