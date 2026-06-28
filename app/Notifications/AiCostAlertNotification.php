<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * In-app notification shown in the bell when an AI call exceeds the cost threshold.
 */
class AiCostAlertNotification extends Notification
{
    public function __construct(
        private readonly string $feature,
        private readonly string $model,
        private readonly float $cost,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'ai_cost_alert',
            'feature' => $this->feature,
            'model' => $this->model,
            'message' => sprintf(
                'AI cost alert: %s call on %s cost $%.4f.',
                $this->feature,
                $this->model,
                $this->cost,
            ),
        ];
    }
}
