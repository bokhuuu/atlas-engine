<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Apartments for Sale - bina.az category 1.
 *
 * Covers both secondary (Вторичка) and new build (Новостройка) apartments.
 * is_new_building populated from "Категория" row on detail page.
 * floor, total_floors, rooms extracted from property rows.
 * leased=false in GraphQL filter targets sale listings only.
 */
class BinazApartmentSaleProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_apartments_sale';
    }

    public function getCategoryId(): int
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
