<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Apartments for Rent - bina.az category 1.
 *
 * Same category as sale (categoryId 1) — distinguished by leased=true
 * in the GraphQL filter. is_new_building, floor, total_floors, rooms
 * all extracted from detail page same as sale profile.
 */
class BinazApartmentRentProfile extends AbstractBinaAzProfile
{
    public function getName(): string
    {
        return 'binaz_apartments_rent';
    }

    public function getCategoryId(): int
    {
        return 1;
    }

    public function getListingType(): string
    {
        return 'rent';
    }

    public function getPropertyType(): string
    {
        return 'apartment';
    }
}
