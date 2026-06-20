<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Exceptions\InvalidListingException;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Validates that all required fields are present on the DTO.
 *
 * Throws InvalidListingException if any required field is null or empty.
 * The pipeline catches this and skips the listing without interrupting the run.
 */
final class ValidateRequiredFieldsStage implements PipelineStageInterface
{
    public function __construct(
        private readonly array $requiredFields,
    ) {}

    public function handle(ListingDTO $dto): ListingDTO
    {
        foreach ($this->requiredFields as $field) {
            if ($this->isMissing($dto->$field)) {
                throw new InvalidListingException(
                    "Required field '{$field}' is missing or empty."
                );
            }
        }

        return $dto;
    }

    /**
     * A field is missing if it is null or an empty string.
     */
    private function isMissing(mixed $value): bool
    {
        return $value === null || $value === '';
    }
}
