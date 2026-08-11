<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Offices for Rent - bina.az category 7.
 *
 * Area always in m² (never сот) - no sotka conversion needed.
 * Shares full pipeline with all bina.az profiles via AbstractBinaAzProfile.
 * categoryId 7 with leased=true targets office rent listings.
 */
class BinazOfficeRentProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_offices_rent';
    }

    public function getCategoryId(): int
    {
        return 7;
    }

    public function getListingType(): string
    {
        return 'rent';
    }

    public function getPropertyType(): string
    {
        return 'office';
    }
}
