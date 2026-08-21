<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Houses for Rent - bina.az category 5.
 *
 * Same category as sale (categoryId 5) - distinguished by leased=true
 * in the GraphQL filter.
 */
class BinazHouseRentProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_houses_rent';
    }

    public function getCategoryId(): int
    {
        return 5;
    }

    public function getListingType(): string
    {
        return 'rent';
    }

    public function getPropertyType(): string
    {
        return 'house';
    }
}
