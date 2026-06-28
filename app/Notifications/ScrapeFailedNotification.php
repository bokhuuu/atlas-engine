<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Modules\Scraper\Support\CategoryLabels;
use Illuminate\Notifications\Notification;

/**
 * In-app notification shown in the bell when a scrape fails.
 */
class ScrapeFailedNotification extends Notification
{
    public function __construct(
        private readonly string $source,
        private readonly string $errorMessage,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $label = CategoryLabels::forSource($this->source);

        return [
            'type' => 'scrape_failed',
            'source' => $this->source,
            'label' => $label,
            'message' => "{$label} scrape failed: {$this->errorMessage}",
        ];
    }
}
