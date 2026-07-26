<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Flags probable duplicate listings for bina.az profiles.
 *
 * Identifies the same physical plot listed by multiple agencies
 * using price and area proximity:
 *
 *   Same district + area within ±2% + price within ±3%
 *   → Same physical plot listed by competing agencies at identical price
 *
 * Stricter tolerances than the generic FlagProbableDuplicatesStage
 * to avoid false positives in the Baku land market where many
 * legitimate plots share similar characteristics.
 *
 * Yerevan profiles use FlagProbableDuplicatesStage - this stage is
 * bina.az only and does not affect list.am scraping.
 */
final class FlagBinazDuplicatesStage implements PipelineStageInterface
{
    public function handle(ListingDTO $dto): ListingDTO
    {
        if ($dto->district === null || $dto->area === null) {
            return $dto;
        }

        $existing = $this->findByPriceArea($dto);

        if ($existing === null) {
            return $dto;
        }

        $groupId = $existing->duplicate_group_id ?? $existing->id;

        if ($existing->duplicate_group_id === null) {
            $existing->duplicate_group_id = $groupId;
            $existing->save();
        }

        $dto->extras['duplicate_group_id'] = $groupId;

        return $dto;
    }

    /**
     * Find duplicate by price+area proximity.
     * Same district + area ±2% + price ±3% → same plot, different agencies.
     * Stricter tolerances than phone match to avoid false positives.
     */
    private function findByPriceArea(ListingDTO $dto): ?Listing
    {
        if ($dto->price === null) {
            return null;
        }

        $areaTolerance = 0.02;
        $priceTolerance = 0.03;

        $areaMin = $dto->area * (1 - $areaTolerance);
        $areaMax = $dto->area * (1 + $areaTolerance);
        $priceMin = $dto->price * (1 - $priceTolerance);
        $priceMax = $dto->price * (1 + $priceTolerance);

        return Listing::query()
            ->where('district', $dto->district)
            ->whereBetween('area', [$areaMin, $areaMax])
            ->whereBetween('price', [$priceMin, $priceMax])
            ->where('source_profile_name', $dto->sourceProfileName)
            ->where('external_id', '!=', $dto->externalId)
            ->first();
    }
}
