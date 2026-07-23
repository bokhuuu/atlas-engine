<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Translates Russian-language structured fields to English.
 *
 * Runs at pipeline time — zero API calls, instant, config-driven.
 * Covers district names, building types, and conditions sourced from
 * bina.az (Russian-only site). Unknown values pass through unchanged
 * and can be added to the dictionary after inspecting real scraped data.
 *
 * Config lives in config/scraper.php under normalization.baku — portable
 * for any future Russian-language market without code changes.
 */
final class NormalizeToEnglishStage implements PipelineStageInterface
{
    public function handle(ListingDTO $dto): ListingDTO
    {
        $dto->district = $this->normalize($dto->district, 'districts');
        $dto->buildingType = $this->normalize($dto->buildingType, 'building_types');
        $dto->condition = $this->normalize($dto->condition, 'conditions');

        return $dto;
    }

    /**
     * Look up a value in the given dictionary section.
     * Returns the English equivalent if found, original value if not.
     * Null-safe — returns null if the input is null.
     */
    private function normalize(?string $value, string $dictionaryKey): ?string
    {
        if ($value === null) {
            return null;
        }

        $dictionary = config("scraper.normalization.baku.{$dictionaryKey}", []);

        $trimmed = trim($value);

        foreach ($dictionary as $russian => $english) {
            if (mb_strtolower($trimmed) === mb_strtolower($russian)) {
                return $english;
            }
        }

        return $trimmed;
    }
}
