<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationComponent extends Component
{
    public function render()
    {
        return view('livewire.notifications.notification-component')->with([
            'notifications' => Auth::user()->notifications,
        ]);
    }
}
