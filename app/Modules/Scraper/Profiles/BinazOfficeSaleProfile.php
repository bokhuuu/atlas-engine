<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Offices for Sale - bina.az category 7.
 *
 * Area always in m² (never сот) - no sotka conversion needed.
 * Shares full pipeline with all bina.az profiles via AbstractBinaAzProfile.
 * categoryId 7 with leased=false targets office sale listings.
 */
class BinazOfficeSaleProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_offices_sale';
    }

    public function getCategoryId(): int
    {
        return 7;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'office';
    }
}
