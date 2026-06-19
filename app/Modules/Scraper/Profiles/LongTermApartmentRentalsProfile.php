<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Long Term Apartment Rentals - list.am category 56.
 * Paired with ApartmentsForSaleProfile for the residential yield/cap-rate estimate.
 */
class LongTermApartmentRentalsProfile extends AbstractListAmProfile
{
    /**
     * Unique profile name. Scopes dedup and cookie storage to this category only.
     */
    public function getName(): string
    {
        return 'listam_apartments_rent';
    }

    public function getCategoryId(): int
    {
        return 56;
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
