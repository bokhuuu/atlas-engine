<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Apartments for Sale - list.am category 60.
 * The original, fully verified category - baseline residential comps.
 */
class ApartmentsForSaleProfile extends AbstractListAmProfile
{
    /**
     * Unique profile name. Scopes dedup and cookie storage to this category only.
     */
    public function getName(): string
    {
        return 'listam_apartments_sale';
    }

    public function getCategoryId(): int
    {
        return 60;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'apartment';
    }

    public function getExports(): array
    {
        return ['excel', 'json', 'real_estate_report'];
    }
}
