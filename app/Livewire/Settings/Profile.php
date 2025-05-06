<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public string $last_name = '';
    public string $first_name = '';
    public string $middle_name = '';
    public string $email = '';
    public string $username = '';
    public string $phone = '';
    public string $gender = '';
    public string $dob = '';
    // public string $photo = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->last_name = Auth::user()->last_name;
        $this->first_name = Auth::user()->first_name;
        $this->middle_name = Auth::user()->middle_name ?? '';
        $this->email = Auth::user()->email;
        $this->username = Auth::user()->username;
        $this->phone = Auth::user()->phone ?? '';
        $this->gender = Auth::user()->gender ?? '';
        $this->dob = Auth::user()->dob ?? '';
        // $this->photo = Auth::user()->photo;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:90', Rule::unique(User::class)->ignore($user->id)],
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:30', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'max:30', Rule::unique(User::class)->ignore($user->id)],
            'gender' => ['nullable', 'string', 'in:Female,Male'],
            'dob' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:'. \Carbon\Carbon::now()->subYears(18)->format('Y-m-d')],
        ]);

        // dd($validated);
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->full_name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}
