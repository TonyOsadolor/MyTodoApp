<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use App\Traits\AppNotificationTrait;
use App\Enums\AppNotificationEnum;
use Livewire\Component;

class NotificationComponent extends Component
{
    use AppNotificationTrait;

    /**
     * Mark All as Read
     */
    public function markAllAsRead()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();

            $msg = "Unread Notifications Mark as Read";
            $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
            return;
        } catch (\Exception $e) {
            $this->notify('error', 'Something went wrong', AppNotificationEnum::ERROR);
        }
    }
    /**
     * Delete All Read
     */
    public function deleteAllRead()
    {
        try {
            Auth::user()->notifications()->whereNotNull('read_at')->delete();

            $msg = "Read Notifications Deleted";
            $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
            return;
        } catch (\Exception $e) {
            $this->notify('error', 'Something went wrong', AppNotificationEnum::ERROR);
        }
    }

    public function render()
    {
        return view('livewire.notifications.notification-component')->with([
            'notifications' => Auth::user()->notifications()->paginate(15),
        ]);
    }
}
