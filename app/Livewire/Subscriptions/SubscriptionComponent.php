<?php

namespace App\Livewire\Subscriptions;

use App\Traits\AppNotificationTrait;
use Illuminate\Support\Facades\Auth;
use App\Enums\AppNotificationEnum;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use App\Models\Subscription;
use Livewire\Component;

class SubscriptionComponent extends Component
{
    use AppNotificationTrait;

    /**
     * Add Subscription
     */ 
    #[Validate('required', as: 'Subscription Plan', message: ':attribute is required')]
    public $subscription_plan = '';

    #[Validate('required', as: 'Payment Method', message: ':attribute is required')]
    public $payment_type = '';
    public function addSubscription()
    {
        $data = $this->validate(
            [
                'subscription_plan' => [
                    'exists:subscription_plans,id',
                    Rule::unique('subscriptions', 'subscription_plan_id')->where(function ($query) {
                        return $query->where('user_id', Auth::user()->id);
                    }),
                ],
                'payment_type' => 'in:monthly',
            ],
            [
                'subscription_plan.exists' => ':attribute is not supported',
                'subscription_plan.unique' => 'You already have this :attribute added',
                'payment_type.in' => ':attribute is not supported',
            ]
        );

        $subscription = Subscription::create([
            'user_id' => Auth::user()->id,
            'subscription_plan_id' => $data['subscription_plan'],
            'payment_type' => $data['payment_type'],
            'payment_date' => now(),
            'next_payment_date' => isset($data['payment_type']) ? now()->addMonth() : now(),
            'is_active' => false,
        ]);

        $this->reset();
        $this->modal("addSubscriptionModal")->close();
        $msg = $subscription->plan->name ." Subscription Successfully Added!!";
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);
    }

    /**
     * Open Modal
     */
    public function openModal(string $name): void
    {
        $this->modal($name)->show();
    }

    /**
     * Open Modal
     */
    public function closeModal(string $name): void
    {
        $this->modal($name)->close();
    }

    /**
     * Change Active Setting
     */
    public function changeActiveSetting(int $subscriptionId)
    {
        $subscription = Subscription::find($subscriptionId);

        if ($subscription && !in_array($subscription->plan->id, [3,4])) {
            $subscription->is_active = !$subscription->is_active ? true : false;
            $subscription->save();

            $subscription->refresh();
            
            $msg = $subscription->is_active ? 'Activated' : 'Deactivated';
            $this->notify('success', "{$subscription->plan->name} {$msg} Successfully!", AppNotificationEnum::SUCCESS);
            return;
        }

        $subscription->refresh();
        $msg = "Please contact Admin to Complete";
        $this->notify('error', $msg, AppNotificationEnum::ERROR);
    }

    /**
     * Render page to FE
     */
    public function render()
    {
        return view('livewire.subscriptions.subscription-component')->with([
            'subscriptions' => Subscription::mysubs(Auth::user())->get(),
        ]);
    }
}
