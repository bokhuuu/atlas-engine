<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('clean zombies marks old running runs as failed', function () {
    DB::table('scraper_runs')->insert([
        'source' => 'binaz_land_sale',
        'state' => 'running',
        'started_at' => now()->subHours(9),
        'created_at' => now()->subHours(9),
        'updated_at' => now()->subHours(9),
    ]);

    $this->artisan('scraper:clean-zombies --hours=8')
        ->expectsOutput('Marked 1 zombie scraper run(s) as failed.')
        ->assertExitCode(0);

    expect(DB::table('scraper_runs')->where('state', 'failed')->count())->toBe(1);
});

test('clean zombies does not touch recent running runs', function () {
    DB::table('scraper_runs')->insert([
        'source' => 'binaz_land_sale',
        'state' => 'running',
        'started_at' => now()->subHours(2),
        'created_at' => now()->subHours(2),
        'updated_at' => now()->subHours(2),
    ]);

    $this->artisan('scraper:clean-zombies --hours=8')
        ->expectsOutput('Marked 0 zombie scraper run(s) as failed.')
        ->assertExitCode(0);

    expect(DB::table('scraper_runs')->where('state', 'running')->count())->toBe(1);
});

test('clean zombies does not touch completed runs', function () {
    DB::table('scraper_runs')->insert([
        'source' => 'binaz_land_sale',
        'state' => 'completed',
        'started_at' => now()->subHours(9),
        'finished_at' => now()->subHours(8),
        'created_at' => now()->subHours(9),
        'updated_at' => now()->subHours(8),
    ]);

    $this->artisan('scraper:clean-zombies --hours=8')
        ->expectsOutput('Marked 0 zombie scraper run(s) as failed.')
        ->assertExitCode(0);

    expect(DB::table('scraper_runs')->where('state', 'completed')->count())->toBe(1);
});
