<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Modules\Scraper\Support\CategoryLabels;
use Illuminate\Notifications\Notification;

/**
 * In-app notification shown in the bell when a scrape completes.
 * Stored in the database via Laravel's Notifiable trait.
 */
class ScrapeCompletedNotification extends Notification
{
    public function __construct(
        private readonly string $source,
        private readonly int $listingCount,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $label = CategoryLabels::forSource($this->source);

        return [
            'type' => 'scrape_completed',
            'source' => $this->source,
            'label' => $label,
            'listing_count' => $this->listingCount,
            'message' => "{$label} scrape completed: {$this->listingCount} new listings saved.",
        ];
    }
}
