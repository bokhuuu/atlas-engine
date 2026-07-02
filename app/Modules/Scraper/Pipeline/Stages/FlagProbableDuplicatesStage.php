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
 * Rule-based by design, not AI-based - explainable to clients and
 * costs nothing in AI token budget. Thresholds confirmed against real
 * scraped data, not picked arbitrarily - see ATLAS.md.
 *
 * A new listing only joins an existing group by matching that group's
 * ANCHOR (the listing whose own id equals the group id) - never by
 * matching any other member. Without this, transitive chaining can
 * occur: A matches B, B matches C, so A and C end up grouped together
 * even though A and C don't actually match each other. Confirmed as a
 * real issue on Commercial Sale's real data, not theoretical.
 *
 * District must match exactly. Floor and room count match exactly only
 * when both listings have a value - apartments always do; houses/land/
 * commercial often don't, so these are skipped rather than blocking a
 * match when missing. When either floor or rooms can't be checked,
 * tolerance tightens (fewer confirmed signals = more coincidence risk
 * at the same tolerance - confirmed by Commercial's real flag rate
 * running 2-3x every other category's before this fix).
 *
 * Flags, never deletes. duplicate_group_id links matched listings;
 * downstream reporting decides how to count a group.
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

        if ($dto->district === null || $dto->area === null || $dto->price === null) {
            return $dto;
        }

        $candidates = $this->repository->findPotentialDuplicates(
            $dto->sourceProfileName,
            $dto->district,
            $dto->externalId,
        );

        $byGroup = $candidates->groupBy(fn (Listing $c) => $c->duplicate_group_id ?? $c->id);

        foreach ($byGroup as $groupId => $members) {
            $anchor = $members->firstWhere('id', $groupId) ?? $members->first();

            if ($this->isProbableMatch($dto, $anchor)) {
                $dto->extras['duplicate_group_id'] = $groupId;

                if ($anchor->duplicate_group_id === null) {
                    $this->repository->assignDuplicateGroupId($anchor->id, $groupId);
                }

                return $dto;
            }
        }

        return $dto;
    }

    private function isProbableMatch(ListingDTO $dto, Listing $candidate): bool
    {
        $floorKnownBothSides = $dto->floor !== null && $candidate->floor !== null;
        $roomsKnownBothSides = $dto->rooms !== null && $candidate->rooms !== null;

        if ($floorKnownBothSides && $dto->floor !== $candidate->floor) {
            return false;
        }

        if ($roomsKnownBothSides && $dto->rooms !== $candidate->rooms) {
            return false;
        }

        $bothSignalsConfirmed = $floorKnownBothSides && $roomsKnownBothSides;

        $areaTolerance = $bothSignalsConfirmed
            ? (float) config('scraper.duplicate_detection.area_tolerance_percent', 5.0)
            : (float) config('scraper.duplicate_detection.area_tolerance_percent_strict', 3.0);

        $priceTolerance = $bothSignalsConfirmed
            ? (float) config('scraper.duplicate_detection.price_tolerance_percent', 10.0)
            : (float) config('scraper.duplicate_detection.price_tolerance_percent_strict', 6.0);

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
