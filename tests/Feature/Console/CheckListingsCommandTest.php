<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Modules\Scraper\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckListingsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_no_listings_when_db_empty(): void
    {
        $this->artisan('atlas:check-listings')
            ->expectsOutput('No active listings to check.')
            ->assertExitCode(0);
    }

    public function test_dry_run_does_not_make_requests(): void
    {
        Http::fake();

        Listing::factory()->count(3)->create(['disappeared_at' => null]);

        $this->artisan('atlas:check-listings --dry-run')
            ->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_marks_listing_as_disappeared_on_404(): void
    {
        Http::fake(['*' => Http::response('', 404)]);

        $listing = Listing::factory()->create([
            'url' => 'https://list.am/en/item/99999999',
            'disappeared_at' => null,
            'last_checked_at' => null,
        ]);

        $this->artisan('atlas:check-listings')
            ->assertExitCode(0);

        $listing->refresh();

        $this->assertNotNull($listing->disappeared_at);
        $this->assertNotNull($listing->last_checked_at);
    }

    public function test_updates_last_seen_at_on_200(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        $listing = Listing::factory()->create([
            'url' => 'https://list.am/en/item/99999999',
            'disappeared_at' => null,
            'last_checked_at' => null,
        ]);

        $this->artisan('atlas:check-listings')
            ->assertExitCode(0);

        $listing->refresh();

        $this->assertNotNull($listing->last_seen_at);
        $this->assertNotNull($listing->last_checked_at);
        $this->assertNull($listing->disappeared_at);
    }
}
