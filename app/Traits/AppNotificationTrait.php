<?php

namespace App\Traits;

use App\Enums\AppNotificationEnum;
use App\Enums\PageEnum;

trait AppNotificationTrait
{
    /**
     * Send a notification with a dynamic type via Filament.
     *
     * @param string $status
     * @param string $message
     * @param AppNotificationEnum $type
     */
    private function notify($status, $message, AppNotificationEnum $type): void
    {
        $event = [
            'title' => ucwords($status)."!",
            'message' => $message,
            'icon' => $type,
            'status' => $type,
        ];
            
        $this->dispatch($status, $event);
    }
}
