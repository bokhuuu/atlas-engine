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
 * list.am uses commas as thousands separators, not decimal separators
 * (confirmed against a real listing: "5,412 sq.m." means 5412, not
 * 5.412). Commas are stripped entirely rather than converted to decimal
 * points. Unit abbreviations like "sq.m." contain periods that aren't
 * decimal points either, so the real number is extracted via regex
 * match rather than a strip-and-cast approach, which would otherwise
 * absorb those stray periods into the parsed value.
 *
 * Leaves area as null if no numeric value can be extracted.
 */
final class CleanAreaStage implements PipelineStageInterface
{
    public function handle(ListingDTO $dto): ListingDTO
    {
        $dto->area = $this->cleanArea($dto->area);

        if (isset($dto->extras['land_area'])) {
            $dto->extras['land_area'] = $this->cleanArea($dto->extras['land_area']);
        }

        return $dto;
    }

    /**
     * Remove thousands-separator commas, then extract the leading
     * numeric value (digits plus an optional decimal point) - ignoring
     * anything after it, including unit abbreviations like "sq.m.".
     * Returns null if no valid numeric value can be extracted.
     */
    private function cleanArea(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $withoutThousandsSeparators = str_replace(',', '', (string) $value);

        if (! preg_match('/\d+(\.\d+)?/', $withoutThousandsSeparators, $matches)) {
            return null;
        }

        $area = (float) $matches[0];

        return $area > 0 ? $area : null;
    }
}
