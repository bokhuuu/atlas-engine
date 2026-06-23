<?php

declare(strict_types=1);

use App\Modules\Scraper\Models\Listing;
use App\Modules\Scraper\Pipeline\Stages\FlagProbableDuplicatesStage;
use App\Modules\Scraper\Repositories\ListingRepository;

function makeCandidateListing(array $overrides = []): Listing
{
    $attributes = array_merge([
        'district' => 'Kentron',
        'floor' => 3,
        'rooms' => 2,
        'area' => 60.0,
        'price' => 100000.0,
        'duplicate_group_id' => null,
    ], $overrides);

    $id = $attributes['id'] ?? 999;
    unset($attributes['id']);

    $listing = new Listing($attributes);
    $listing->id = $id;

    return $listing;
}

test('it flags a listing within area and price tolerance, same floor and rooms', function () {
    $candidate = makeCandidateListing();

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));
    $repository->shouldReceive('assignDuplicateGroupId')->once()->with(999, 999);

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => 'Kentron', 'floor' => 3, 'rooms' => 2, 'area' => 61.0, 'price' => 105000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBe(999);
});

test('it does not flag when floor differs and both listings have one', function () {
    $candidate = makeCandidateListing(['floor' => 3]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => 'Kentron', 'floor' => 7, 'rooms' => 2, 'area' => 60.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});

test('it flags a match even when floors differ, as long as one side has no floor at all', function () {
    $candidate = makeCandidateListing(['floor' => 5]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));
    $repository->shouldReceive('assignDuplicateGroupId')->once();

    $stage = new FlagProbableDuplicatesStage($repository);

    // dto has no floor at all - houses/land/commercial scenario.
    $dto = makeListing(['district' => 'Kentron', 'floor' => null, 'rooms' => 2, 'area' => 60.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBe(999);
});

test('it does not flag when rooms differ and both listings have a room count', function () {
    $candidate = makeCandidateListing(['rooms' => 2]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => 'Kentron', 'floor' => 3, 'rooms' => 5, 'area' => 60.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});

test('it flags a match even when room counts differ, as long as one side has no room count at all', function () {
    $candidate = makeCandidateListing(['rooms' => 4]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));
    $repository->shouldReceive('assignDuplicateGroupId')->once();

    $stage = new FlagProbableDuplicatesStage($repository);

    // dto has no room count at all - commercial property scenario.
    $dto = makeListing(['district' => 'Kentron', 'floor' => 3, 'rooms' => null, 'area' => 60.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBe(999);
});

test('it uses stricter tolerance when floor or rooms cannot be confirmed on both sides', function () {
    $candidate = makeCandidateListing(['floor' => null, 'rooms' => null, 'area' => 100.0, 'price' => 100000.0]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));

    $stage = new FlagProbableDuplicatesStage($repository);

    // 3.85% area difference - would pass the normal 5% tolerance, but
    // should fail the stricter 3% tolerance used when floor/rooms are
    // unconfirmed (commercial/land scenario).
    $dto = makeListing(['district' => 'Kentron', 'floor' => null, 'rooms' => null, 'area' => 104.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});

test('it only matches against a group anchor, not any member - prevents transitive chaining', function () {
    // An existing group: anchor (id 500, its own duplicate_group_id)
    // and a member (id 501) that drifted further from the anchor than
    // the anchor's own tolerance allows - exactly the real scenario
    // found in live Commercial Sale data.
    $anchor = makeCandidateListing(['id' => 500, 'duplicate_group_id' => 500, 'floor' => null, 'rooms' => null, 'area' => 100.0, 'price' => 200000.0]);
    $member = makeCandidateListing(['id' => 501, 'duplicate_group_id' => 500, 'floor' => null, 'rooms' => null, 'area' => 120.0, 'price' => 240000.0]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$anchor, $member]));
    $repository->shouldNotReceive('assignDuplicateGroupId');

    $stage = new FlagProbableDuplicatesStage($repository);

    // Close to member 501 (1.6% area, 2% price) but far from anchor 500
    // (18% area, 18.4% price). Must NOT flag - matching the member
    // alone is exactly the chaining bug this fix prevents.
    $dto = makeListing(['district' => 'Kentron', 'floor' => null, 'rooms' => null, 'area' => 122.0, 'price' => 245000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});

test('it does not flag when area exceeds tolerance', function () {
    $candidate = makeCandidateListing();

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => 'Kentron', 'floor' => 3, 'rooms' => 2, 'area' => 80.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});

test('it does not flag when price exceeds tolerance', function () {
    $candidate = makeCandidateListing();

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => 'Kentron', 'floor' => 3, 'rooms' => 2, 'area' => 60.0, 'price' => 130000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});

test('it skips matching entirely when a required field is missing', function () {
    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldNotReceive('findPotentialDuplicates');

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => null]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});

test('it reuses an existing duplicate_group_id instead of creating a new one', function () {
    $candidate = makeCandidateListing(['duplicate_group_id' => 555]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldReceive('findPotentialDuplicates')->once()->andReturn(collect([$candidate]));
    $repository->shouldNotReceive('assignDuplicateGroupId');

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => 'Kentron', 'floor' => 3, 'rooms' => 2, 'area' => 60.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBe(555);
});

test('it respects the duplicate_detection.enabled config toggle', function () {
    config(['scraper.duplicate_detection.enabled' => false]);

    $repository = Mockery::mock(ListingRepository::class);
    $repository->shouldNotReceive('findPotentialDuplicates');

    $stage = new FlagProbableDuplicatesStage($repository);

    $dto = makeListing(['district' => 'Kentron', 'floor' => 3, 'rooms' => 2, 'area' => 60.0, 'price' => 100000.0]);

    $result = $stage->handle($dto);

    expect($result->extras['duplicate_group_id'] ?? null)->toBeNull();
});
