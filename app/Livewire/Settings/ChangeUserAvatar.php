<?php

namespace App\Livewire\Settings;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChangeUserAvatar extends Component
{
    use WithFileUploads;
    
    public $avatar = '';

    /**
     * Process uploaded avatar.
     */
    public function changeAvatar(): void
    {
        $user = Auth::user();
        $defaultAvatar = config('mytodoapp.default_avatar');
        $hasAvatar = $user->photo == $defaultAvatar ? false: true;

        $validated = $this->validate([
            'avatar' => ['required', 'mimes:jpeg,png,jpg,gif,bmp', 'max:1024'],
        ],['avatar.required' => 'Avatar not uploaded, try again..']);

        if ($validated['avatar']) {
            try {

                $filename = 'user_' . $user->id . '_' . time() . '.jpg';
                $path = $validated['avatar']->storeAs('avatars', $filename, 'cloudinary');

                $data['photo'] = Storage::disk('cloudinary')->url($path);

                $user->fill($data)->save();

                $this->avatar = null;
                Flux::modal('upload-user-avatar')->close();
                $this->dispatch('avatar-changed-success', name: $user->full_name);

            } catch (\Exception $error) {

                $this->dispatch('avatar-changed-error', name: $user->full_name, message: 'Internal Error, Details: ' .$error->getMessage());
                Log::error($error->getMessage());
                Flux::modal('upload-user-avatar')->close();
                session()->flash('avatar_error', 'Internal Error, Details: ' .$error->getMessage());
                return;
            }
        }
        
    }
}
