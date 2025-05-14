<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscriptionComponent extends Component
{
    public function render()
    {
        return view('livewire.subscriptions.subscription-component')->with([
            'subscriptions' => Subscription::mysubs(Auth::user())->get(),
        ]);
    }
}
