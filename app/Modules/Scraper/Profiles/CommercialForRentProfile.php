<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Commercial Real Estate for Rent - list.am category 59.
 * Paired with CommercialForSaleProfile for the commercial yield/cap-rate estimate.
 */
class CommercialForRentProfile extends AbstractListAmProfile
{
    /**
     * Unique profile name. Scopes dedup and cookie storage to this category only.
     */
    public function getName(): string
    {
        return 'listam_commercial_rent';
    }

    public function getCategoryId(): int
    {
        return 59;
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
