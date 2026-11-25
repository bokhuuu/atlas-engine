<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Land for Sale - bina.az category 9.
 *
 * First Baku profile — land listings from bina.az.
 * Area comes in сот (sotka) - converted to m² by BinazScraper (×100).
 * hasBillOfSale stored in extras - critical for Baku land pricing.
 */
class BinazLandSaleProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_land_sale';
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
}
