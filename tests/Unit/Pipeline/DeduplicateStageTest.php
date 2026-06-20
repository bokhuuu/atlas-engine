<?php

declare(strict_types=1);

use App\Modules\Scraper\Exceptions\DuplicateListingException;
use App\Modules\Scraper\Pipeline\Stages\DeduplicateStage;
use App\Modules\Scraper\Repositories\ListingRepository;

test('it passes through when listing does not exist', function () {
    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('existsByExternalId')
        ->with('test-123', 'listam')
        ->andReturn(false);

    $stage = new DeduplicateStage($repository);
    $dto = makeListing();

    $result = $stage->handle($dto);

    expect($result->externalId)->toBe('test-123');
});

test('it throws when listing already exists', function () {
    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('existsByExternalId')
        ->with('test-123', 'listam')
        ->andReturn(true);

    $stage = new DeduplicateStage($repository);
    $dto = makeListing();

    expect(fn () => $stage->handle($dto))->toThrow(DuplicateListingException::class);
});
