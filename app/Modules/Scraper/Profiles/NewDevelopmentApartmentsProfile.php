<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Profiles;

/**
 * New Development Apartments for Sale - list.am category 268.
 * Supply-pipeline signal - what's being built, not just what exists.
 */
class NewDevelopmentApartmentsProfile extends AbstractListAmProfile
{
    /**
     * Unique profile name. Scopes dedup and cookie storage to this category only.
     */
    public function getName(): string
    {
        return 'listam_new_developments_sale';
    }

    public function getCategoryId(): int
    {
        return 268;
    }

    public function getListingType(): string
    {
        return 'sale';
    }

    public function getPropertyType(): string
    {
        return 'new_development_apartment';
    }

    /**
     * New developments use "Interior Finishing" instead of "Renovation" -
     * a never-lived-in apartment was never renovated, so that label doesn't
     * exist here. Reusing the 'renovation' key keeps the output field the
     * same as every other category, just sourced from different wording.
     * "Handover Date" is genuinely new - no other category has this.
     * Confirmed via DevTools against a real listing, not assumed.
     */
    public function getDetailSelectors(): array
    {
        return [
            ...parent::getDetailSelectors(),
            'renovation' => 'Interior Finishing',
            'handover_date' => 'Handover Date',
        ];
    }
}
