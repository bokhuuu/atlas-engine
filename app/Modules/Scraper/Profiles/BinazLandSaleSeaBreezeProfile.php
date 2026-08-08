<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Land for Sale - Sea Breeze development, bina.az category 9.
 *
 * Scopes scraping to Sea Breeze (locationId 380) via bina.az GraphQL filter.
 * Returns exactly the listings bina.az shows when filtering by Sea Breeze location.
 * Area comes in сот (sotka) - converted to m² by BinazScraper (×100).
 */
class BinazLandSaleSeaBreezeProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_land_sale_sea_breeze';
    }

    public function getCategoryId(): int
    {
        return 9;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'land';
    }

    public function getLocationIds(): ?array
    {
        return [(string) config('scraper.binaz.locations.sea_breeze', '380')];
    }
}
