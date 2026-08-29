<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Normalizes myhome.ge district and sub-district names to canonical English.
 *
 * myhome.ge breadcrumb returns Georgian-language district names.
 * This stage maps them to their canonical English parent district names
 * and stores the sub-district in extras.sub_district for Data Hub filtering.
 *
 * Two dictionaries in config/scraper.php normalization.tbilisi:
 *   - districts: sub-district → parent district (e.g. "Saburtalo" → "Vake-Saburtalo")
 *   - parent_districts: parent district aliases → canonical name
 *
 * Unknown values pass through unchanged — add to config after inspecting real data.
 */
final class NormalizeToEnglishMyhomeStage implements PipelineStageInterface
{
    public function handle(ListingDTO $dto): ListingDTO
    {
        if ($dto->district === null) {
            return $dto;
        }

        $trimmed = preg_replace('/\s+/', ' ', trim($dto->district));

        // Check if it's already a known parent district
        $parentDistricts = config('scraper.normalization.tbilisi.parent_districts', []);
        if (isset($parentDistricts[$trimmed])) {
            $dto->district = $parentDistricts[$trimmed];

            return $dto;
        }

        // Check if it's a sub-district — map to parent and store sub in extras
        $districts = config('scraper.normalization.tbilisi.districts', []);
        if (isset($districts[$trimmed])) {
            $dto->extras['sub_district'] = $trimmed;
            $dto->district = $districts[$trimmed];

            return $dto;
        }

        // Unknown value — pass through trimmed
        $dto->district = $trimmed;

        return $dto;
    }
}
