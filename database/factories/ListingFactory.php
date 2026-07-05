<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Scraper\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        return [
            'external_id' => $this->faker->unique()->numerify('########'),
            'source_profile_name' => 'listam_apartments_sale',
            'url' => 'https://list.am/en/item/'.$this->faker->unique()->numerify('########'),
            'listing_type' => 'sale',
            'property_type' => 'apartment',
            'price' => $this->faker->numberBetween(50000, 500000),
            'currency' => 'USD',
            'price_per_sqm' => $this->faker->numberBetween(500, 3000),
            'area' => $this->faker->numberBetween(40, 200),
            'rooms' => $this->faker->numberBetween(1, 5),
            'district' => $this->faker->randomElement(['Kentron', 'Arabkir', 'Malatia', 'Nor Nork']),
            'images' => [],
            'extras' => [],
            'scraped_at' => now(),
            'disappeared_at' => null,
            'last_seen_at' => null,
            'last_checked_at' => null,
            'screenshot_url' => null,
        ];
    }
}
