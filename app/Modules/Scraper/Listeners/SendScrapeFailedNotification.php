<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Listeners;

use App\Models\User;
use App\Modules\Scraper\Events\ScrapeFailed;
use App\Notifications\ScrapeFailedNotification;

/**
 * Listens for the ScrapeFailed event and dispatches failure notifications.
 *
 * Resolves the profile from config to get the configured notifiers.
 * Each notifier is instantiated and called with the error payload.
 * Adding a new notifier to the profile is the only change needed.
 */
class SendScrapeFailedNotification
{
    public function handle(ScrapeFailed $event): void
    {
        $notification = new ScrapeFailedNotification($event->source, $event->errorMessage);

        User::role(['admin', 'super_admin'])->each(
            fn (User $user) => $user->notify($notification)
        );

        if (app()->environment('testing')) {
            return;
        }

        $profileClass = config('scraper.profiles.'.$event->source);
        $profile = app($profileClass);

        $payload = [
            'source' => $event->source,
            'status' => 'failed',
            'error' => $event->errorMessage,
        ];

        foreach ($profile->getNotifiers() as $notifierClass) {
            $notifier = app($notifierClass);
            $notifier->notify($payload);
        }
    }
}
