<?php

declare(strict_types=1);

namespace Tests\Unit\Browser;

use App\Modules\Scraper\Browser\ScreenshotService;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\TestCase;

class ScreenshotServiceTest extends TestCase
{
    public function test_returns_null_when_screenshots_disabled(): void
    {
        config(['scraper.screenshots_enabled' => false]);

        $service = app(ScreenshotService::class);

        $result = $service->capture(
            browser: $this->createMock(Browser::class),
            externalId: '12345',
            source: 'listam_apartments_sale',
        );

        $this->assertNull($result);
    }

    public function test_signed_url_returns_string_for_valid_path(): void
    {
        Storage::fake('b2_screenshots');
        Storage::disk('b2_screenshots')->put('screenshots/listam/test.png', 'fake content');

        $service = app(ScreenshotService::class);

        $result = $service->signedUrl('screenshots/listam/test.png');

        $this->assertIsString($result);
    }

    public function test_capture_returns_null_on_browser_exception(): void
    {
        config(['scraper.screenshots_enabled' => true]);

        $browser = $this->createMock(Browser::class);
        $browser->method('screenshot')->willThrowException(new \Exception('Chrome error'));

        $service = app(ScreenshotService::class);

        $result = $service->capture(
            browser: $browser,
            externalId: '12345',
            source: 'listam_apartments_sale',
        );

        $this->assertNull($result);
    }
}
