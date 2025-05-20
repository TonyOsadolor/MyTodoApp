<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
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
        </flux:input.group>

        <!-- Email Address -->
        <flux:input
            wire:model="email" :label="__('Email address')" type="email" required 
            autocomplete="off"  min-length="8"
            placeholder="johndoe@provider.com"
        />

        <!-- Phone Number -->
            <flux:input
                wire:model="phone" :label="__('Phone Number')" type="tel" autocomplete="off"
                :placeholder="__('+2349038472639')"
            />

        <!-- Phone Number -->
        {{-- <flux:input.group>
            <flux:select>
                <flux:select.option selected>+234</flux:select.option>
            </flux:select>
            
            <flux:input  class="max-w-fit"
                wire:model="phone" :label="__('Phone Number')" type="tel" 
                autocomplete="off"
                placeholder="9036-57-4839"
            />
        </flux:input.group> --}}

        <!-- Username -->
        <flux:input
            wire:model="username" :label="__('Username')" type="text" required autocomplete="off"
            :placeholder="__('JohnxxDoe')"
        />

        <!-- Password -->
        <flux:input
            wire:model="password" :label="__('Password')" type="password" required 
            autocomplete="off" min-length="8"
            :placeholder="__('Password')" viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation" :label="__('Confirm password')" type="password" 
            required autocomplete="off" :placeholder="__('Confirm password')" viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
