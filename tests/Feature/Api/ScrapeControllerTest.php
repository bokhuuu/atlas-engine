<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\Scraper\Enums\ScraperState;
use App\Modules\Scraper\Models\ScraperRun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    Sanctum::actingAs(User::factory()->create());
});

test('start returns 422 with invalid source', function () {
    Bus::fake();

    $this->postJson('/api/v1/scrape/start', ['source' => 'unknown'])
        ->assertUnprocessable();
});

test('start returns 202 with valid source', function () {
    Bus::fake();

    $this->postJson('/api/v1/scrape/start', ['source' => 'listam_apartments_sale'])
        ->assertStatus(202)
        ->assertJsonStructure(['message', 'run_id', 'source']);
});

test('status returns 404 when no runs exist', function () {
    $this->getJson('/api/v1/scrape/status?source=listam_apartments_sale')
        ->assertNotFound();
});

test('status returns latest run', function () {
    ScraperRun::create([
        'source' => 'listam_apartments_sale',
        'state' => ScraperState::Running,
        'started_at' => now(),
    ]);

    $this->getJson('/api/v1/scrape/status?source=listam_apartments_sale')
        ->assertOk()
        ->assertJsonStructure(['run_id', 'source', 'state']);
});

test('cancel returns success for active run', function () {
    $run = ScraperRun::create([
        'source' => 'listam_apartments_sale',
        'state' => ScraperState::Running,
        'started_at' => now(),
    ]);

    $this->postJson('/api/v1/scrape/cancel', ['run_id' => $run->id])
        ->assertOk()
        ->assertJsonPath('run_id', $run->id);
});

test('trend returns empty series when no completed runs exist', function () {
    $this->getJson('/api/v1/scrape/trend?source=listam_apartments_sale')
        ->assertOk()
        ->assertJsonStructure(['source', 'series'])
        ->assertJsonPath('series', []);
});

test('trend returns series with cumulative totals for completed runs', function () {
    ScraperRun::create([
        'source' => 'listam_apartments_sale',
        'state' => ScraperState::Completed,
        'saved_listings' => 40,
        'started_at' => now()->subDay(),
        'finished_at' => now()->subDay(),
    ]);

    ScraperRun::create([
        'source' => 'listam_apartments_sale',
        'state' => ScraperState::Completed,
        'saved_listings' => 20,
        'started_at' => now(),
        'finished_at' => now(),
    ]);

    $this->getJson('/api/v1/scrape/trend?source=listam_apartments_sale')
        ->assertOk()
        ->assertJsonCount(2, 'series')
        ->assertJsonPath('series.0.new_listings', 40)
        ->assertJsonPath('series.0.cumulative_listings', 40)
        ->assertJsonPath('series.1.new_listings', 20)
        ->assertJsonPath('series.1.cumulative_listings', 60);
});
