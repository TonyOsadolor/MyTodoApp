<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your Profile Information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- Name Group -->
            <flux:input.group>
                <!-- First Name -->
                <flux:input
                    wire:model="first_name" :label="__('First Name')" type="text" required autofocus autocomplete="off"
                    :placeholder="__('John')"
                />

                <!-- Last Name -->
                <flux:input
                    wire:model="last_name" :label="__('Last Name')" type="text" required autocomplete="off"
                    :placeholder="__('Doe')"
                />

                <!-- Middle Name -->
                <flux:input
                    wire:model="middle_name" :label="__('Middle Name')" type="text" autocomplete="off"
                    :placeholder="__('Lucia')"
                />
            </flux:input.group>

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Username -->
            <flux:input
                wire:model="username" :label="__('Username')" type="text" required autocomplete="off"
                :placeholder="__('JohnxxDoe')"
            />

            <!-- Phone Number -->
            <flux:input
                wire:model="phone" :label="__('Phone Number')" type="tel" autocomplete="off"
                :placeholder="__('+2349038472639')"
            />

            <!-- Gender -->
            <flux:select wire:model="gender" searchable placeholder="Choose Gender..."
                :label="__('Gender')">
                <flux:select.option>Female</flux:select.option>
                <flux:select.option>Male</flux:select.option>
            </flux:select>

            <!-- Date of Birth -->
            <flux:input
                wire:model="dob" :label="__('Date of Birth')"
                value="2000-05-05" type="date"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Profile Updated Successfully!') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.change-user-avatar />
        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
