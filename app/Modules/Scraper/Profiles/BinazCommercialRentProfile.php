<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Commercial Objects for Rent - bina.az category 10.
 *
 * Same category as sale (categoryId 10) — distinguished by leased=true
 * in the GraphQL filter.
 */
class BinazCommercialRentProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_commercial_rent';
    }

    public function getCategoryId(): int
    {
        return 10;
    }

    public function getListingType(): string
    {
        return 'rent';
    }

    public function getPropertyType(): string
    {
        return 'commercial';
    }
}
