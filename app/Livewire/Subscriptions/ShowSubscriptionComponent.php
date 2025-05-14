<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use Livewire\Component;

class ShowSubscriptionComponent extends Component
{
    public $id;
    public function render()
    {
        $subscription = Subscription::where('id', $this->id)->firstOrFail();

        return view('livewire.subscriptions.show-subscription-component')->with([
            'subscription' => $subscription,

        ]);
    }
}
