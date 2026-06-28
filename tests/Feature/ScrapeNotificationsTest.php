<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\Scraper\Events\ScrapeCompleted;
use App\Modules\Scraper\Events\ScrapeFailed;
use App\Notifications\AiCostAlertNotification;
use App\Notifications\ScrapeCompletedNotification;
use App\Notifications\ScrapeFailedNotification;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('notifies admins in bell when scrape completes', function () {
    Notification::fake();

    $admin = User::factory()->withRole('admin')->create();
    $viewer = User::factory()->withRole('viewer')->create();

    ScrapeCompleted::dispatch(
        scraperRunId: 1,
        source: 'listam_apartments_sale',
        listingCount: 87,
    );

    Notification::assertSentTo($admin, ScrapeCompletedNotification::class);
    Notification::assertNotSentTo($viewer, ScrapeCompletedNotification::class);
});

it('notifies admins in bell when scrape fails', function () {
    Notification::fake();

    $admin = User::factory()->withRole('admin')->create();
    $viewer = User::factory()->withRole('viewer')->create();

    ScrapeFailed::dispatch(
        scraperRunId: 1,
        source: 'listam_apartments_sale',
        errorMessage: 'Connection timeout.',
    );

    Notification::assertSentTo($admin, ScrapeFailedNotification::class);
    Notification::assertNotSentTo($viewer, ScrapeFailedNotification::class);
});

it('scrape completed notification contains correct message', function () {
    Notification::fake();

    $admin = User::factory()->withRole('admin')->create();

    ScrapeCompleted::dispatch(
        scraperRunId: 1,
        source: 'listam_apartments_sale',
        listingCount: 42,
    );

    Notification::assertSentTo(
        $admin,
        ScrapeCompletedNotification::class,
        function (ScrapeCompletedNotification $notification) use ($admin) {
            $data = $notification->toDatabase($admin);

            return $data['listing_count'] === 42
                && $data['type'] === 'scrape_completed'
                && str_contains($data['message'], '42 new listings saved');
        }
    );
});

it('ai cost alert notification contains correct data', function () {
    $notification = new AiCostAlertNotification(
        feature: 'chat',
        model: 'claude-haiku-4-5',
        cost: 0.025,
    );

    $admin = User::factory()->withRole('admin')->create();
    $data = $notification->toDatabase($admin);

    expect($data['type'])->toBe('ai_cost_alert')
        ->and($data['feature'])->toBe('chat')
        ->and($data['model'])->toBe('claude-haiku-4-5')
        ->and($data['message'])->toContain('$0.0250');
});
