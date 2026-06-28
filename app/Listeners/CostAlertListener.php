<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\AiCallCompleted;
use App\Models\User;
use App\Modules\AI\Services\UsageTrackingService;
use App\Modules\Scraper\Notifications\MailNotifier;
use App\Modules\Scraper\Notifications\TelegramNotifier;
use App\Notifications\AiCostAlertNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Sends a Telegram + email alert when a single AI call exceeds the cost threshold.
 *
 * Listens for AiCallCompleted events, calculates the call cost and notifies
 * via both channels if the cost exceeds config('ai.cost_alert_threshold').
 * Runs in the background queue so it never slows down the main response.
 *
 * Uses the same TelegramNotifier and MailNotifier as scraper notifications
 * to keep all Atlas alerts consistent and in one place.
 */
class CostAlertListener implements ShouldQueue
{
    private float $costThreshold;

    public function __construct(
        private UsageTrackingService $usageTracker,
    ) {
        $this->costThreshold = (float) config('ai.cost_alert_threshold', 0.01);
    }

    /**
     * Calculate the cost of this AI call and alert if it exceeds the threshold.
     * Silently returns if cost is under threshold — no action needed.
     * Failures on either channel are logged but never thrown — one channel
     * failing must not prevent the other from sending.
     */
    public function handle(AiCallCompleted $event): void
    {
        $cost = $this->usageTracker->calculateCost(
            model: $event->model,
            promptTokens: $event->promptTokens,
            completionTokens: $event->completionTokens,
        );

        if ($cost < $this->costThreshold) {
            return;
        }

        $payload = [
            'source' => 'ai_assistant',
            'status' => 'cost_alert',
            'listing_count' => null,
            'error' => sprintf(
                'Feature: %s | Model: %s | Cost: $%.6f | Tokens: %d prompt / %d completion',
                $event->feature,
                $event->model,
                $cost,
                $event->promptTokens,
                $event->completionTokens,
            ),
        ];

        try {
            app(TelegramNotifier::class)->notify($payload);
        } catch (\Throwable $e) {
            Log::warning('CostAlertListener: Telegram failed.', ['error' => $e->getMessage()]);
        }

        try {
            app(MailNotifier::class)->notify($payload);
        } catch (\Throwable $e) {
            Log::warning('CostAlertListener: Mail failed.', ['error' => $e->getMessage()]);
        }

        $notification = new AiCostAlertNotification(
            feature: $event->feature,
            model: $event->model,
            cost: $cost,
        );

        User::role(['admin', 'super_admin'])->each(
            fn (User $user) => $user->notify($notification)
        );
    }
}
