<?php

declare(strict_types=1);

use App\Modules\Scraper\Enums\ScraperState;
use App\Modules\Scraper\Models\ScraperRun;
use App\Modules\Scraper\Repositories\ScraperRunRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$repository = new ScraperRunRepository;

function runData(array $overrides = []): array
{
    return array_merge([
        'source' => 'listam',
        'state' => ScraperState::Pending,
        'started_at' => now()->toDateTimeString(),
    ], $overrides);
}

test('it saves a scraper run', function () use ($repository) {
    $repository->save(runData());

    expect(ScraperRun::count())->toBe(1);
});

test('it updates state of a scraper run', function () use ($repository) {
    $run = $repository->save(runData());

    $repository->updateState($run->id, ScraperState::Running);

    expect(ScraperRun::find($run->id)->state)->toBe(ScraperState::Running);
});

test('it marks a run as completed', function () use ($repository) {
    $run = $repository->save(runData());

    $repository->markAsCompleted($run->id, savedListings: 100, scrapedPages: 5);

    $updated = ScraperRun::find($run->id);

    expect($updated->state)->toBe(ScraperState::Completed)
        ->and($updated->saved_listings)->toBe(100)
        ->and($updated->scraped_pages)->toBe(5)
        ->and($updated->finished_at)->not->toBeNull();
});

test('it marks a run as failed', function () use ($repository) {
    $run = $repository->save(runData());

    $repository->markAsFailed($run->id, 'Something went wrong');

    $updated = ScraperRun::find($run->id);

    expect($updated->state)->toBe(ScraperState::Failed)
        ->and($updated->error)->toBe('Something went wrong')
        ->and($updated->finished_at)->not->toBeNull();
});

test('it marks a run as cancelled', function () use ($repository) {
    $run = $repository->save(runData());

    $repository->markAsCancelled($run->id);

    expect(ScraperRun::find($run->id)->state)->toBe(ScraperState::Cancelled);
});

test('it finds a run by id', function () use ($repository) {
    $run = $repository->save(runData());

    $found = $repository->findById($run->id);

    expect($found->id)->toBe($run->id);
});

test('it finds the latest run for a source', function () use ($repository) {
    $repository->save(runData());
    $repository->save(runData());

    $latest = $repository->findLatest('listam');

    expect($latest)->not->toBeNull()
        ->and(ScraperRun::where('source', 'listam')->count())->toBe(2);
});

test('getTrend returns only completed runs in ascending order', function () use ($repository) {
    $repository->save(array_merge(runData(), [
        'state' => ScraperState::Completed,
        'saved_listings' => 50,
        'started_at' => now()->subDays(2)->toDateTimeString(),
    ]));

    $repository->save(array_merge(runData(), [
        'state' => ScraperState::Failed,
        'saved_listings' => 0,
        'started_at' => now()->subDay()->toDateTimeString(),
    ]));

    $repository->save(array_merge(runData(), [
        'state' => ScraperState::Completed,
        'saved_listings' => 30,
        'started_at' => now()->toDateTimeString(),
    ]));

    $trend = $repository->getTrend('listam');

    expect($trend)->toHaveCount(2)
        ->and($trend->first()->saved_listings)->toBe(50)
        ->and($trend->last()->saved_listings)->toBe(30);
});

test('getTrend returns empty collection when no completed runs exist', function () use ($repository) {
    $repository->save(runData());

    $trend = $repository->getTrend('listam');

    expect($trend)->toBeEmpty();
});
