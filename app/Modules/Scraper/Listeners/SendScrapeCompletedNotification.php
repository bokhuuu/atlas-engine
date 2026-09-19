<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Listeners;

use App\Models\User;
use App\Modules\Scraper\Events\ScrapeCompleted;
use App\Notifications\ScrapeCompletedNotification;

/**
 * Listens for the ScrapeCompleted event and dispatches notifications.
 *
 * Resolves the profile from config to get the configured notifiers.
 * Each notifier is instantiated and called with the event payload.
 * Adding a new notifier to the profile is the only change needed.
 */
class SendScrapeCompletedNotification
{
    public function handle(ScrapeCompleted $event): void
    {
        $notification = new ScrapeCompletedNotification($event->source, $event->listingCount);

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
            'status' => 'completed',
            'listing_count' => $event->listingCount,
            'encountered_count' => $event->encounteredCount,
        ];

        foreach ($profile->getNotifiers() as $notifierClass) {
            $notifier = app($notifierClass);
            $notifier->notify($payload);
        }
    }
}
