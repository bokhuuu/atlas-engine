<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Models;

use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a single real estate listing scraped from an external source.
 *
 * Stores normalized property data including location, pricing and specifications.
 * Supports multiple scraping sources via the compound external_id + source unique index.
 */
class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'scraper_run_id',
        'external_id',
        'source_profile_name',
        'url',
        'listing_type',
        'property_type',

        'price',
        'currency',
        'price_per_sqm',

        'area',

        'rooms',
        'bathrooms',
        'floor',
        'total_floors',
        'ceiling_height',
        'building_type',
        'condition',
        'is_new_building',

        'has_bill_of_sale',
        'has_mortgage',

        'district',
        'address',

        'phone',
        'agency_name',
        'images',
        'extras',
        'description',
        'duplicate_group_id',

        'listing_date',
        'scraped_at',

        'previous_price',
        'price_change_pct',
        'price_changed_at',

        'last_seen_at',
        'last_checked_at',
        'disappeared_at',
        'screenshot_url',
        'translated_description',
    ];

    protected $casts = [
        'price' => 'float',
        'price_per_sqm' => 'float',
        'area' => 'float',
        'ceiling_height' => 'float',
        'is_new_building' => 'boolean',
        'images' => 'array',
        'extras' => 'array',
        'listing_date' => 'datetime',
        'scraped_at' => 'datetime',
        'previous_price' => 'float',
        'price_change_pct' => 'float',
        'price_changed_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'last_checked_at' => 'datetime',
        'disappeared_at' => 'datetime',
    ];

    /**
     * Scope to filter listings by scraping source.
     */
    public function scopeForSource(Builder $query, string $source): Builder
    {
        return $query->where('source_profile_name', $source);
    }

    protected static function newFactory(): ListingFactory
    {
        return ListingFactory::new();
    }
}
