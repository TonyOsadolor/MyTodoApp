<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:avatar src="{{ auth()->user()->photo }}" />
        <flux:heading>{{ __('Change Avatar') }}</flux:heading>
        <flux:subheading>{{ __('Change User\' Avatar') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="upload-user-avatar">
        <flux:button variant="filled" x-data="" x-on:click.prevent="$dispatch('open-modal', 'upload-user-avatar')">
            {{ __('Upload Avatar') }}
        </flux:button>
            <!-- Flash Success Message Starts Here -->
            <x-action-message class="me-3" on="avatar-changed-error" x-data="{ message: '' }" x-on:avatar-changed-error.window="message = $event.detail.message">
                <span x-text="message"></span>
            </x-action-message>
            @if (session()->has('avatar_error'))
                <div class="dark:text-orange-400 text-red-900" x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)"
                        x-show="show" x-transition>
                    {{ session('avatar_error') }}
                </div>
            @endif
            <!-- Flash Success Message Ends Here -->

            <!-- Flash Error Message Starts Here -->
            <x-action-message class="me-3" on="avatar-changed-success">
                {{ __('Avatar Uploaded Successfully!') }}
            </x-action-message>
            <!-- Flash Error Message Ends Here -->
    </flux:modal.trigger>

    <flux:modal name="upload-user-avatar" :show="$errors->isNotEmpty()" focusable class="max-w-lg">

        <form wire:submit.prevent="changeAvatar" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Change Your Avatar') }}</flux:heading>

                <flux:subheading>
                    {{ __('You Avatar will be uploaded and Change across the App') }}
                </flux:subheading>
            </div>

            <flux:input type="file" wire:model.live="avatar" label="Avatar" />

            <flux:text style="color:whitesmoke!important;" wire:loading wire:target="avatar" class="text-xs">Processing Image, please wait...</flux:text>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>             

                <flux:button variant="primary" type="submit" wire:loading.attr="disabled" wire:target="avatar">
                    {{ __('Upload Avatar') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

</section>
