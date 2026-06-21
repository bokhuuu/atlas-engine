<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;
use App\Modules\Scraper\Repositories\ListingRepository;

/**
 * Flags probable duplicate listings - the same physical property posted
 * by different parties (owner + agent, etc.) under different external_ids.
 *
 * This is a different problem than DeduplicateStage, which only catches
 * the exact same post reappearing. Two different posts describing the
 * same real apartment have different external_ids and pass straight
 * through DeduplicateStage untouched.
 *
 * Rule-based by design, not AI-based - explainable to Colliers ("matched
 * on district/floor/rooms exactly, area within 5%, price within 10%")
 * and costs nothing in AI token budget. Thresholds confirmed against
 * real scraped data, not picked arbitrarily - see ATLAS.md.
 *
 * Flags, never deletes. duplicate_group_id links matched listings;
 * downstream reporting decides how to count a group.
 *
 * Thresholds are config-driven (config/scraper.php), not hardcoded.
 * All database access goes through ListingRepository, not direct
 * Eloquent calls, matching every other stage in this pipeline.
 */
final class FlagProbableDuplicatesStage implements PipelineStageInterface
{
    public function __construct(
        private readonly ListingRepository $repository,
    ) {}

    public function handle(ListingDTO $dto): ListingDTO
    {
        if (! config('scraper.duplicate_detection.enabled', true)) {
            return $dto;
        }

        if ($dto->district === null || $dto->area === null || $dto->price === null || $dto->floor === null || $dto->rooms === null) {
            return $dto;
        }

        $candidates = $this->repository->findPotentialDuplicates(
            $dto->sourceProfileName,
            $dto->district,
            $dto->floor,
            $dto->rooms,
            $dto->externalId,
        );

        foreach ($candidates as $candidate) {
            if ($this->isProbableMatch($dto, $candidate)) {
                $groupId = $candidate->duplicate_group_id ?? $candidate->id;

                $dto->extras['duplicate_group_id'] = $groupId;

                if ($candidate->duplicate_group_id === null) {
                    $this->repository->assignDuplicateGroupId($candidate->id, $groupId);
                }

                return $dto;
            }
        }

        return $dto;
    }

    /**
     * Both area and price must be within tolerance, together - either
     * alone is too weak a signal, confirmed against real listing data.
     */
    private function isProbableMatch(ListingDTO $dto, Listing $candidate): bool
    {
        $areaTolerance = (float) config('scraper.duplicate_detection.area_tolerance_percent', 5.0);
        $priceTolerance = (float) config('scraper.duplicate_detection.price_tolerance_percent', 10.0);

        $areaDiff = $this->percentDifference($dto->area, (float) $candidate->area);
        $priceDiff = $this->percentDifference($dto->price, (float) $candidate->price);

        return $areaDiff <= $areaTolerance && $priceDiff <= $priceTolerance;
    }

    private function percentDifference(float $a, float $b): float
    {
        $max = max($a, $b);

        return $max > 0 ? abs($a - $b) / $max * 100 : 0.0;
    }
}
