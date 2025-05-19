<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserAppNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\UUID;

class Subscription extends Model
{
    use UUID;
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Boot for Creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });

        static::created(function ($model) {
            SubscriptionHistory::create([
                'user_id' => $model->user_id,
                'user_email' => $model->user->email,
                'subscription_id' => $model->id,
                'amount' => $model->paln->amount ?? 0.00,
                'start_date' => now(),
                'end_date' => $model->next_payment_date,
            ]);

            Notification::send($model->user, new UserAppNotification([
                'title' => "New {$model->plan->name} Subscription Added",
                'body' => "Hello {$model->user->full_name}, \n A new {$model->plan->name} Subscription Plan has been added to your profile, this will enable receive live update on your account via the alert channel. \n \n This Subscription will expire by: {$model->next_payment_date}. \n Good Luck!",
                'has_button' => true,
                'url' => "/subscriptions",
            ]));

        });
    }

    /**
     * Get the plan that owns the subscription.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id', 'id');
    }

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    #[Scope]
    protected function mysubs(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
