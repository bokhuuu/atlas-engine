<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Houses for Sale - list.am category 1386.
 * Residential sector coverage
 */
class HousesForSaleProfile extends AbstractListAmProfile
{
    /**
     * Unique profile name. Scopes dedup and cookie storage to this category only.
     */
    public function getName(): string
    {
        return 'listam_houses_sale';
    }

    public function getCategoryId(): int
    {
        return 1386;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'house';
    }

    /**
     * Houses use "House Area" instead of apartments' "Floor Area", and have
     * a second area value apartments don't - the land the house sits on.
     * Confirmed via DevTools against a real listing, not assumed.
     */
    public function getDetailSelectors(): array
    {
        return [
            ...parent::getDetailSelectors(),
            'area' => 'House Area',
            'land_area' => 'Total Land Area',
        ];
    }
}
