<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Flags probable duplicate listings for bina.az profiles.
 *
 * Uses two complementary signals to catch the two common duplicate patterns
 * on bina.az - same owner relisting, and same plot listed by multiple agencies:
 *
 * Signal 1 - Phone match (same owner, different agency):
 *   Same phone + same district + area within ±5%
 *   → Owner listed same plot through multiple brokers
 *
 * Signal 2 - Price+Area match (different phones, same plot):
 *   Same district + area within ±2% + price within ±3%
 *   → Same physical plot listed by competing agencies at identical price
 *
 * Flagged listings share a duplicate_group_id so Data Hub filters them
 * out by default, showing only unique plots in market statistics.
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

        $existing = $this->findByPhone($dto) ?? $this->findByPriceArea($dto);

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
     * Find duplicate by phone number match.
     * Same phone + same district + area within ±5% → same owner, different agency.
     */
    private function findByPhone(ListingDTO $dto): ?Listing
    {
        if ($dto->phone === null) {
            return null;
        }

        $areaTolerance = 0.05;
        $areaMin = $dto->area * (1 - $areaTolerance);
        $areaMax = $dto->area * (1 + $areaTolerance);

        return Listing::query()
            ->where('phone', $dto->phone)
            ->where('district', $dto->district)
            ->whereBetween('area', [$areaMin, $areaMax])
            ->where('source_profile_name', $dto->sourceProfileName)
            ->where('external_id', '!=', $dto->externalId)
            ->first();
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
