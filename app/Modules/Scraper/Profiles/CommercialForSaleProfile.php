<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Commercial Real Estate for Sale - list.am category 199.
 * The core commercial asset class
 */
class CommercialForSaleProfile extends AbstractListAmProfile
{
    /**
     * Unique profile name. Scopes dedup and cookie storage to this category only.
     */
    public function getName(): string
    {
        return 'listam_commercial_sale';
    }

    public function getCategoryId(): int
    {
        return 199;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'commercial';
    }
}
