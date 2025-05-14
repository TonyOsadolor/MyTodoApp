<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowNotificationComponent extends Component
{
    public $id;
    public function render()
    {
        $notification = DB::table('notifications')->where('id', $this->id)->firstOrFail();

        if(!$notification->read_at){
            DB::table('notifications')->where('id', $this->id)->update(['read_at' => now()]);
        }

        return view('livewire.notifications.show-notification-component')->with([
            'id' => $notification->id,
            'data' => json_decode($notification->data, true),

        ]);
    }
}
