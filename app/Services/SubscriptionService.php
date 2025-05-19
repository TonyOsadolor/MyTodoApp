<?php

namespace App\Services;

use App\Enums\SubscriptionCodeEnum;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Exception;

class SubscriptionService
{
    /**
     * Initialize Users Registration for Subscription
     * 
     * @param \App\Models\User $user
     * 
     */
    public function initialize(User $user): void
    {
        $subscriptions = [
            ['plan_id' => $this->getPlanByCode(SubscriptionCodeEnum::ALERT)?->id],
            ['plan_id' => $this->getPlanByCode(SubscriptionCodeEnum::EMAIL)?->id],
        ];

        foreach ($subscriptions as $plan) {
            if (!$plan['plan_id']) {continue;}
            try {
                Subscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan['plan_id'],
                    'payment_type' => 'lifetime',
                    'payment_date' => now(),
                    'next_payment_date' => now()->addYears(20),
                ]);

            } catch (\Exception $error) {
                throw new Exception("An error occurred: " . $error->getMessage(), 0, $error);
            }
        }
    }

    /**
     * Get Subcription Plans by code
     */
    public function getPlanByCode($code): ?SubscriptionPlan
    {
        return SubscriptionPlan::where('code', $code)->first();
    }
}