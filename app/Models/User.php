<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Jobs\WelcomeUserJob;
use Illuminate\Support\Str;
use App\Traits\UUID;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
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

        static::updating(function ($model) {
            if ($model->isDirty('email_verified_at')) {
                WelcomeUserJob::dispatch($model);
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The appended attributes for the model.
     *
     * @var array
     */
    protected $appends = ['full_name', 'is_verified', 'text_number'];

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get formatted phone number for texting user.
     *
     * @return string
     */
    public function getTextNumberAttribute()
    {
        if (empty($this->phone)) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        return str_starts_with($phone, '0') ? '234' . substr($phone, 1) : (str_starts_with($phone, '234') ? $phone : '234' . $phone);
    }

    /**
     * Get the User's Photo.
     *
     * @return string
     */
    public function getPhotoAttribute($value)
    {
        return $value ? $value : config('mytodoapp.default_avatar');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->full_name)
            ->explode(' ')
            ->map(fn (string $full_name) => Str::of($full_name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the verification status of the user.
     *
     * @return string
     */
    public function getIsVerifiedAttribute()
    {
        if (!empty($this->email_verified_at)) {
            return true;
        }
        return false;
    }

    /**
     * Get the tasks for the user.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the tasks for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
