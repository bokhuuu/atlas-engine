<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * Land for Sale - list.am category 55.
 * Feeds feasibility studies - a core service line
 */
class LandForSaleProfile extends AbstractListAmProfile
{
    /**
     * Unique profile name. Scopes dedup and cookie storage to this category only.
     */
    public function getName(): string
    {
        return 'listam_land_sale';
    }

    public function getCategoryId(): int
    {
        return 55;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'land';
    }

    /**
     * Land uses "Land Area" - different wording from both apartments
     * ("Floor Area") and houses ("Total Land Area"). Confirmed via DevTools.
     * Rooms/floor/bathrooms correctly stay null - those labels never appear
     * on a land listing, and extractSpecByLabel() handles that safely already.
     */
    public function getDetailSelectors(): array
    {
        return [
            ...parent::getDetailSelectors(),
            'area' => 'Land Area',
        ];
    }
}
