<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Repositories\ListingRepository;

/**
 * Detects price changes on re-scraped listings.
 *
 * When a listing already exists in the database and its price has changed,
 * this stage records the previous price, calculates the percentage change,
 * and timestamps the change. New listings pass through unchanged.
 *
 * Runs after DeduplicateStage so we only check listings that will actually
 * be saved — not listings being skipped as exact duplicates.
 */
class DetectPriceChangeStage
{
    public function __construct(
        private readonly ListingRepository $listingRepository,
    ) {}

    public function handle(ListingDTO $dto): ListingDTO
    {
        $existing = $this->listingRepository->findByExternalId(
            $dto->externalId,
            $dto->sourceProfileName,
        );

        if ($existing && $existing->price && $dto->price && $existing->price !== $dto->price) {
            $changePct = round(
                (($dto->price - $existing->price) / $existing->price) * 100,
                2
            );

            $dto->previousPrice = $existing->price;
            $dto->priceChangePct = $changePct;
            $dto->priceChangedAt = now();
        }

        return $dto;
    }
}
