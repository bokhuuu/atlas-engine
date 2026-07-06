<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Modules\Scraper\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaptureScreenshotsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_no_listings_when_none_need_screenshots(): void
    {
        $this->artisan('atlas:capture-screenshots')
            ->expectsOutput('No listings need screenshots.')
            ->assertExitCode(0);
    }

    public function test_dry_run_does_not_open_chrome(): void
    {
        Listing::factory()->count(3)->create([
            'screenshot_url' => null,
            'disappeared_at' => null,
        ]);

        $this->artisan('atlas:capture-screenshots --dry-run')
            ->assertExitCode(0);
    }

    public function test_skips_disappeared_listings(): void
    {
        Listing::factory()->count(3)->create([
            'screenshot_url' => null,
            'disappeared_at' => now(),
        ]);

        $this->artisan('atlas:capture-screenshots')
            ->expectsOutput('No listings need screenshots.')
            ->assertExitCode(0);
    }

    public function test_skips_listings_that_already_have_screenshot(): void
    {
        Listing::factory()->count(3)->create([
            'screenshot_url' => 'screenshots/listam/test.png',
            'disappeared_at' => null,
        ]);

        $this->artisan('atlas:capture-screenshots')
            ->expectsOutput('No listings need screenshots.')
            ->assertExitCode(0);
    }
}
