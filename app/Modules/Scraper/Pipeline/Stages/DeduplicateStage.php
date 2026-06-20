<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Pipeline\Stages;

use App\Modules\Scraper\DTOs\ListingDTO;
use App\Modules\Scraper\Repositories\ListingRepository;
use App\Modules\Scraper\Exceptions\DuplicateListingException;
use App\Modules\Scraper\Pipeline\PipelineStageInterface;

/**
 * Checks whether a listing already exists in the database
 * by source and externalId.
 *
 * Throws DuplicateListingException if a match is found,
 * causing the pipeline to skip this listing entirely.
 */
final class DeduplicateStage implements PipelineStageInterface
{
    public function __construct(
        private readonly ListingRepository $repository,
    ) {}

    public function handle(ListingDTO $dto): ListingDTO
    {
        if ($this->repository->existsByExternalId($dto->externalId, $dto->sourceProfileName)) {
            throw new DuplicateListingException(
                "Listing '{$dto->externalId}' from '{$dto->sourceProfileName}' already exists."
            );
        }

        return $dto;
    }
}
