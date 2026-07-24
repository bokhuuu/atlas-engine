<?php

declare(strict_types=1);

namespace App\Modules\Scraper\DTOs;

use Carbon\Carbon;

/**
 * Immutable data transfer object representing a single scraped listing.
 *
 * Created from raw scraper output via fromArray(), passed through the pipeline,
 * and persisted via toArray(). The pipeline may modify nullable fields -
 * required fields (externalId, url, sourceProfileName) are always set.
 *
 * Keys in fromArray() use snake_case (database/scraper convention).
 * Properties use camelCase (PHP convention).
 * Keys in toArray() use snake_case (database convention).
 */
final class ListingDTO
{
    public function __construct(
        public string $externalId,
        public string $url,
        public string $sourceProfileName,
        public ?int $scraperRunId,

        public string $listingType,
        public string $propertyType,

        public ?float $price,
        public ?string $currency,
        public ?float $pricePerSqm,

        public ?float $area,

        public ?int $rooms,
        public ?int $bathrooms,
        public ?int $floor,
        public ?int $totalFloors,
        public ?float $ceilingHeight,
        public ?string $buildingType,
        public ?string $condition,
        public ?bool $isNewBuilding,

        public ?bool $hasBillOfSale,

        public ?string $district,
        public ?string $address,

        public ?string $phone,
        public ?string $agencyName,

        public array $images,
        public array $extras,

        public ?Carbon $listingDate,
        public Carbon $scrapedAt,

        public ?string $description = null,
        public ?string $translatedDescription = null,

        public ?float $previousPrice = null,
        public ?float $priceChangePct = null,
        public ?Carbon $priceChangedAt = null,
    ) {}

    /**
     * Create a ListingDTO from a raw scraper output array.
     * Handles type casting, null safety, and key name normalization.
     * Accepts both 'location' and 'address' keys for the address field.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            externalId: $data['external_id'],
            url: $data['url'],
            sourceProfileName: $data['source_profile_name'],

            listingType: $data['listing_type'],
            propertyType: $data['property_type'],
            scraperRunId: $data['scraper_run_id'] ?? null,

            price: isset($data['price']) ? (float) $data['price'] : null,
            currency: $data['currency'] ?? null,
            pricePerSqm: isset($data['price_per_sqm']) ? (float) $data['price_per_sqm'] : null,

            area: isset($data['area']) ? self::parseNumeric($data['area']) : null,

            rooms: isset($data['rooms']) ? (int) $data['rooms'] : null,
            bathrooms: isset($data['bathrooms']) ? (int) $data['bathrooms'] : null,
            floor: isset($data['floor']) ? (int) $data['floor'] : null,
            totalFloors: isset($data['total_floors']) ? (int) $data['total_floors'] : null,
            ceilingHeight: isset($data['ceiling_height']) ? (float) $data['ceiling_height'] : null,
            buildingType: $data['building_type'] ?? null,
            hasBillOfSale: isset($data['has_bill_of_sale'])
                ? (bool) $data['has_bill_of_sale']
                : null,
            condition: $data['condition'] ?? null,
            isNewBuilding: isset($data['is_new_building'])
                ? filter_var($data['is_new_building'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,

            district: $data['district'] ?? null,
            address: $data['location'] ?? $data['address'] ?? null,

            phone: $data['phone'] ?? null,
            agencyName: $data['agency_name'] ?? null,

            images: $data['image_urls'] ?? [],
            extras: $data['extras'] ?? [],

            description: $data['description'] ?? null,
            translatedDescription: $data['translated_description'] ?? null,

            listingDate: isset($data['listing_date'])
                ? Carbon::parse($data['listing_date'])
                : null,
            scrapedAt: isset($data['scraped_at'])
                ? Carbon::parse($data['scraped_at'])
                : Carbon::now(),
        );
    }

    /**
     * Convert to plain array for the repository layer.
     * All keys use snake_case to match database column names exactly.
     */
    public function toArray(): array
    {
        return [
            'external_id' => $this->externalId,
            'url' => $this->url,
            'source_profile_name' => $this->sourceProfileName,
            'scraper_run_id' => $this->scraperRunId,

            'listing_type' => $this->listingType,
            'property_type' => $this->propertyType,

            'price' => $this->price,
            'currency' => $this->currency,
            'price_per_sqm' => $this->pricePerSqm,

            'area' => $this->area,

            'rooms' => $this->rooms,
            'bathrooms' => $this->bathrooms,
            'floor' => $this->floor,
            'has_bill_of_sale' => $this->hasBillOfSale,
            'total_floors' => $this->totalFloors,
            'ceiling_height' => $this->ceilingHeight,
            'building_type' => $this->buildingType,
            'condition' => $this->condition,
            'is_new_building' => $this->isNewBuilding,

            'district' => $this->district,
            'address' => $this->address,

            'phone' => $this->phone,
            'agency_name' => $this->agencyName,

            'images' => $this->images,
            'extras' => $this->extras,

            'description' => $this->description,
            'translated_description' => $this->translatedDescription,

            'listing_date' => $this->listingDate?->toDateTimeString(),
            'scraped_at' => $this->scrapedAt->toDateTimeString(),

            'previous_price' => $this->previousPrice,
            'price_change_pct' => $this->priceChangePct,
            'price_changed_at' => $this->priceChangedAt?->toDateTimeString(),
        ];
    }

    /**
     * Strips thousands-separator commas and extracts the leading numeric
     * value, ignoring unit abbreviations like "sq.m.". list.am uses commas
     * as thousands separators, not decimal separators (confirmed: "5,412
     * sq.m." means 5412, not 5.412) - mirrors CleanAreaStage's logic, since
     * this needs to be correct even before the pipeline stage gets a turn.
     */
    private static function parseNumeric(mixed $value): ?float
    {
        $withoutThousandsSeparators = str_replace(',', '', (string) $value);

        if (! preg_match('/\d+(\.\d+)?/', $withoutThousandsSeparators, $matches)) {
            return null;
        }

        return (float) $matches[0];
    }
}
