<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Strips unit symbols and formatting from the area field
 * and converts it to a clean float.
 *
 * Also cleans extras['land_area'] when present (houses only) -
 * same kind of value, same cleaning logic.
 *
 * Leaves area as null if no numeric value can be extracted.
 */
final class CleanAreaStage implements PipelineStageInterface
{
    /**
     * Strips unit symbols from the area so it's stored as a clean
     * number instead of raw scraped text.
     */
    public function handle(ListingDTO $dto): ListingDTO
    {
        $dto->area = $this->cleanArea($dto->area);

        if (isset($dto->extras['land_area'])) {
            $dto->extras['land_area'] = $this->cleanArea($dto->extras['land_area']);
        }

        return $dto;
    }

    /**
     * Remove non-numeric characters except decimal separators,
     * normalize comma to period and return as float.
     * Returns null if no valid numeric value can be extracted.
     */
    private function cleanArea(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $cleaned = preg_replace('/[^0-9,.]/', '', (string) $value);
        $normalized = str_replace(',', '.', $cleaned);

        if ($normalized === '' || (float) $normalized === 0.0) {
            return null;
        }

        return (float) $normalized;
    }
}
