<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Commercial Objects for Sale - bina.az category 10.
 *
 * Covers shops, restaurants, warehouses, production spaces (Объект).
 * Different from offices (category 7) which is specifically office space.
 * leased=false in GraphQL filter targets sale listings only.
 */
class BinazCommercialSaleProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_commercial_sale';
    }

    public function getCategoryId(): int
    {
        return 10;
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
