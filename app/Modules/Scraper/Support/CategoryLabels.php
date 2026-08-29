<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Support;

/**
 * Human-readable display labels per scraper source key.
 *
 * Display copy only - not a threshold or setting, so it lives here
 * rather than in config/scraper.php. Single source of truth so the
 * dashboard, market stats page, and any future screen never drift
 * on what a category is called.
 */
class CategoryLabels
{
    private const LABELS = [
        'listam_apartments_sale' => 'Apartments for Sale',
        'listam_apartments_rent' => 'Long Term Apartment Rentals',
        'listam_houses_sale' => 'Houses for Sale',
        'listam_commercial_sale' => 'Commercial for Sale',
        'listam_commercial_rent' => 'Commercial for Rent',
        'listam_land_sale' => 'Land for Sale',
        'listam_new_developments_sale' => 'New Development Apartments',

        'binaz_land_sale' => 'Land for Sale',
        'binaz_land_sale_sea_breeze' => 'Land for Sale — Sea Breeze',
        'binaz_offices_sale' => 'Offices for Sale',
        'binaz_offices_rent' => 'Offices for Rent',
        'binaz_apartments_sale' => 'Apartments for Sale',
        'binaz_apartments_rent' => 'Apartments for Rent',
        'binaz_houses_sale' => 'Houses for Sale',
        'binaz_houses_rent' => 'Houses for Rent',
        'binaz_commercial_sale' => 'Commercial for Sale',
        'binaz_commercial_rent' => 'Commercial for Rent',

        'myhome_apartments_sale' => 'Apartments for Sale',
        'myhome_apartments_rent' => 'Apartments for Rent',
        'myhome_houses_sale' => 'Houses for Sale',
        'myhome_houses_rent' => 'Houses for Rent',
        'myhome_country_houses_sale' => 'Country Houses for Sale',
        'myhome_country_houses_rent' => 'Country Houses for Rent',
        'myhome_land_sale' => 'Land for Sale',
        'myhome_commercial_sale' => 'Commercial for Sale',
    ];

    public static function forSource(string $source): string
    {
        return self::LABELS[$source] ?? $source;
    }

    public static function all(): array
    {
        return self::LABELS;
    }
}
