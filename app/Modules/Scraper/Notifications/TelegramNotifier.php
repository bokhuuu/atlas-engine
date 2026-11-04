<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Notifications;

use App\Modules\Scraper\Notifications\Contracts\NotifierInterface;
use Telegram\Bot\Api;

/**
 * Sends scraper notifications to a Telegram chat.
 *
 * The message format is the same regardless of which event triggered it.
 */
class TelegramNotifier implements NotifierInterface
{
    private Api $telegram;

    /** Sets up the Telegram API client using the configured bot token. */
    public function __construct()
    {
        $this->telegram = app(Api::class, ['token' => config('services.telegram.token')]);
    }

    /** Sends the built message to the configured Telegram chat. */
    public function notify(array $payload): void
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $this->buildMessage($payload),
            ]);
        } catch (\Throwable $e) {
            logger()->warning('TelegramNotifier: failed to send message', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /** Turns the event payload into a short, readable Telegram message. */
    private function buildMessage(array $payload): string
    {
        $source = $payload['source'] ?? 'unknown';
        $status = $payload['status'] ?? 'unknown';
        $count = $payload['listing_count'] ?? null;
        $error = $payload['error'] ?? null;

        $message = "Atlas [{$source}] — {$status}";

        if ($count !== null) {
            $encountered = $payload['encountered_count'] ?? null;
            if ($encountered !== null && $encountered > 0 && $count === 0) {
                $message .= "\nListings saved: {$count} ({$encountered} encountered, all existing)";
            } else {
                $message .= "\nListings saved: {$count}";
            }
        }

        if ($error !== null) {
            $message .= "\nError: {$error}";
        }

        return $message;
    }
}
