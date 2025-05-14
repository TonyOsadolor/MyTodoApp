<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Alert',
                'description' => 'This Alert Subscription is for free and it alerts only on inApp',
                'is_free' => true,
                'amount' => 0.00,
            ],
            [
                'name' => 'Email',
                'description' => 'This Email Subscription is for free and it alerts only on email',
                'is_free' => true,
                'amount' => 0.00,
            ],
            [
                'name' => 'SMS',
                'description' => 'This SMS Subscription is not for free and it alerts only on user\'s SMS',
                'is_free' => false,
                'amount' => 600.00,
            ],
            [
                'name' => 'Whatsapp',
                'description' => 'This Whatsapp Subscription is not for free and it alerts only on user\'s WhatsApp',
                'is_free' => false,
                'amount' => 500.00,
            ],
        ];

        foreach ($categories as $category) {
            SubscriptionPlan::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'is_free' => $category['is_free'],
                'amount' => $category['is_free'] ? 0.00 : $category['amount'],
                'is_active' => true,
            ]);
        }
    }
}
