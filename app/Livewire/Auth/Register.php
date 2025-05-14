<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Registered;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\AppNotificationTrait;
use App\Enums\AppNotificationEnum;
use Illuminate\Validation\Rules;
use App\Enums\AccountStatusEnum;
use Livewire\Attributes\Layout;
use App\Enums\RoleTypeEnum;
use Livewire\Component;
use App\Models\User;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    use AppNotificationTrait;

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $username = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate(
            [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:90', 'unique:'.User::class],
                'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:30', 'unique:'.User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'first_name.required' => ':attribute is required',                
                'first_name.string' => ':attribute must be a string',                
                'first_name.max' => ':attribute cannot be greater than 255 characters',
                
                'last_name.required' => ':attribute is required',                
                'last_name.string' => ':attribute must be a string',                
                'last_name.max' => ':attribute cannot be greater than 255 characters',

                'email.required' => ':attribute is required',
                'email.email' => 'Invalid :attribute supplied',
                'email.max' => ':attribute cannot be longer than 90 characters',
                'email.unique' => ':attribute already registered',

                'username.required' => ':attribute is required',
                'username.string' => ':attribute must be string',
                'username.regex' => ':attribute cannot contain special characters',
                'username.max' => ':attribute cannot be greater than 30 characters',
                'username.unique' => ':attribute is already taken',
            ]
        );

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = AccountStatusEnum::ACTIVE;
        $validated['is_active'] = true;
        $validated['role'] = RoleTypeEnum::USER;

        event(new Registered(($user = User::create($validated))));

        // Add Default Subscription for User
        $subscription = app()->make(SubscriptionService::class)->initialize($user);

        Auth::login($user);
        $msg = 'Registration Successful, proceed to Verify your Email';
        $this->notify('success', $msg, AppNotificationEnum::SUCCESS);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}
