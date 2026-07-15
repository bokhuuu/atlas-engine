<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;
use App\Services\ExchangeRateService;

/**
 * Calculates price per square meter from price and area, then converts
 * both price and price_per_sqm to USD using the stored exchange rate
 * for the listing's currency.
 *
 * Runs after CleanPriceStage and CleanAreaStage so both values are
 * guaranteed to be clean floats or null before division.
 *
 * Converting at pipeline time (not at query time) keeps all downstream
 * consumers - stat queries, exports, AI tools - simple. They always
 * read USD values directly. The original currency is preserved in the
 * currency column for reference.
 *
 * USD listings pass through unchanged (rate = 1.0).
 * Unknown currencies fall back to 1.0 - no conversion, logged by service.
 */
final class CalculatePricePerSqmStage implements PipelineStageInterface
{
    public function __construct(
        private readonly ExchangeRateService $exchangeRateService,
    ) {}

    public function handle(ListingDTO $dto): ListingDTO
    {
        if ($dto->price !== null && $dto->area !== null && $dto->area > 0) {
            $rate = $this->exchangeRateService->getRate($dto->currency ?? 'USD');

            $dto->price = round($dto->price / $rate, 2);
            $dto->pricePerSqm = round($dto->price / $dto->area, 2);
        }

        return $dto;
    }
}
