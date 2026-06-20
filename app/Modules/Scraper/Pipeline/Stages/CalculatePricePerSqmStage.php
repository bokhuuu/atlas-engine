<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Calculates price per square meter from price and area.
 *
 * Runs after CleanPriceStage and CleanAreaStage so both values
 * are guaranteed to be clean floats or null before division.
 */
final class CalculatePricePerSqmStage implements PipelineStageInterface
{
    public function handle(ListingDTO $dto): ListingDTO
    {
        if ($dto->price !== null && $dto->area !== null && $dto->area > 0) {
            $dto->pricePerSqm = round($dto->price / $dto->area, 2);
        }

        return $dto;
    }
}
