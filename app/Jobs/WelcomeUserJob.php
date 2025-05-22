<?php

namespace App\Jobs;

use App\Models\User;
use App\Enums\RoleTypeEnum;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WelcomeUserNotification;

class WelcomeUserJob implements ShouldQueue
{
    use Queueable;

    protected ?User $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->user->role == RoleTypeEnum::USER) {
            Notification::send($this->user, new WelcomeUserNotification($this->user));
        }
    }
}
