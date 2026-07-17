<?php

declare(strict_types=1);

namespace Tests\Unit\Browser;

use App\Modules\Scraper\Browser\ScreenshotService;
use App\Modules\Scraper\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScreenshotServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_early_when_screenshots_disabled(): void
    {
        config(['scraper.screenshots_enabled' => false]);

        $listing = Listing::factory()->create([
            'url' => 'https://list.am/en/item/12345',
            'screenshot_url' => null,
        ]);

        $service = app(ScreenshotService::class);
        $service->captureAndUpload($listing);

        $this->assertNull($listing->fresh()->screenshot_url);
    }

    public function test_returns_early_when_url_is_empty(): void
    {
        config(['scraper.screenshots_enabled' => true]);

        $listing = Listing::factory()->create([
            'url' => null,
            'screenshot_url' => null,
        ]);

        $service = app(ScreenshotService::class);
        $service->captureAndUpload($listing);

        $this->assertNull($listing->fresh()->screenshot_url);
    }

    public function test_returns_early_when_access_key_not_configured(): void
    {
        config(['scraper.screenshots_enabled' => true]);
        config(['scraper.screenshot_access_key' => null]);

        $listing = Listing::factory()->create([
            'url' => 'https://list.am/en/item/12345',
            'screenshot_url' => null,
        ]);

        $service = app(ScreenshotService::class);
        $service->captureAndUpload($listing);

        $this->assertNull($listing->fresh()->screenshot_url);
    }

    public function test_captures_and_uploads_screenshot_successfully(): void
    {
        config(['scraper.screenshots_enabled' => true]);
        config(['scraper.screenshot_access_key' => 'test-key']);

        Storage::fake('b2');

        Http::fake([
            'api.screenshotone.com/*' => Http::response("\xFF\xD8\xFF".str_repeat('x', 100), 200),
        ]);

        $listing = Listing::factory()->create([
            'url' => 'https://list.am/en/item/12345',
            'screenshot_url' => null,
        ]);

        $service = app(ScreenshotService::class);
        $service->captureAndUpload($listing);

        $this->assertNotNull($listing->fresh()->screenshot_url);
    }

    public function test_handles_api_failure_gracefully(): void
    {
        config(['scraper.screenshots_enabled' => true]);
        config(['scraper.screenshot_access_key' => 'test-key']);

        Http::fake([
            'api.screenshotone.com/*' => Http::response('error', 500),
        ]);

        $listing = Listing::factory()->create([
            'url' => 'https://list.am/en/item/12345',
            'screenshot_url' => null,
        ]);

        $service = app(ScreenshotService::class);
        $service->captureAndUpload($listing);

        $this->assertNull($listing->fresh()->screenshot_url);
    }
}
