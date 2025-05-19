<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SubscriptionPlanEnum;
use Illuminate\Support\Str;

class SubscriptionPlan extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Assigning UUIDs & Code to New Subscription Categories
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->code = self::generateCode($model->name);
        });
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
     * Generate CODE for Category Creation
     */
    public static function generateCode($modelName)
    {
        $random = rand(1111, 9999);
        $complete = match(true){
            strtolower($modelName) == SubscriptionPlanEnum::ALERT => 'ALT'.$random,
            strtolower($modelName) == SubscriptionPlanEnum::EMAIL => 'EMA'.$random,
            strtolower($modelName) == SubscriptionPlanEnum::SMS => 'SMS'.$random,
            strtolower($modelName) == SubscriptionPlanEnum::WHATSAPP => 'WHT'.$random,
            default => 'DFT'.$random,
        }; 
        
        return strtoupper($complete);
    }

    /**
     * Get the tasks for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
