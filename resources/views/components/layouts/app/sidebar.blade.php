<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                    <flux:navlist.item icon="square-3-stack-3d" :href="route('tasks')" :current="request()->routeIs('tasks')" wire:navigate>
                        {{ __('Tasks') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="signal" :href="route('subscriptions')" :current="request()->routeIs('subscriptions')" wire:navigate>
                        {{ __('Subscriptions') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="bell" :href="route('notifications')" :current="request()->routeIs('notifications')" wire:navigate>
                        {{ __('Notifications') }} 
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="text-yellow-400 dark:text-yellow-600">[{{ Auth::user()->unreadNotifications->count() }}]</span>
                        @endif
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            {{-- <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist> --}}

            <!-- Desktop User Menu -->
            <flux:dropdown>
                <flux:navbar.item 
                     wire:poll.15s 
                    badge="{{auth()->user()->unreadNotifications->count()}}" 
                    class="max-lg:hidden [&>div>svg]:size-5"
                    badge-color="yellow">                           
                    <flux:icon.bell-alert variant="solid" class="{{ auth()->user()->unreadNotifications->count() > 0 ? 'text-amber-500 dark:text-amber-300' : null}}" />                        
                </flux:navbar.item>
                <flux:navmenu>
                    @foreach (auth()->user()->unreadNotifications->take(5) as $notification)
                    <flux:navmenu.item href="/notifications/{{$notification->id}}" style="max-width: 300px!important;">
                        <div class="p-4 mb-4 text-sm text-gray-100 rounded-lg bg-green-600 dark:bg-green-800 dark:text-gray-200" role="alert">
                            <span class="font-medium">{{ $notification['data']['title'] }}</span> 
                        </div>                              
                    </flux:navmenu.item>                               
                    @endforeach                            
                    <flux:navmenu.item href="/notifications">View All</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>

            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->first_name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                    avatar="{{ auth()->user()->photo }}"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:navbar.item 
                    wire:poll.15s 
                    badge="{{auth()->user()->unreadNotifications->count()}}"
                    badge-color="yellow">                           
                    <flux:icon.bell-alert variant="solid" class="{{ auth()->user()->unreadNotifications->count() > 0 ? 'text-amber-500 dark:text-amber-300' : null}}" />                        
                </flux:navbar.item>

                <flux:navmenu>
                    @foreach (auth()->user()->unreadNotifications->take(5) as $notification)
                    <flux:navmenu.item href="/notifications/{{$notification->id}}" style="max-width: 300px!important;">
                        <div class="p-4 mb-4 text-sm text-gray-100 rounded-lg bg-green-600 dark:bg-green-800 dark:text-gray-200" role="alert">
                            <span class="font-medium">{{ $notification['data']['title'] }}</span> 
                            <hr style="margin:5px auto!important; border:1px solid whitesmoke!important;">
                            <span class="truncate text-xs dark:text-yellow-400">
                                {{ $notification->created_at->format('Y-m-d D H : i : s A') }}
                            </span> 
                        </div>                         
                    </flux:navmenu.item>                               
                    @endforeach                            
                    <flux:navmenu.item href="/notifications">View All</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>

            <flux:dropdown position="top" align="end">
                <flux:profile
                    {{-- :name="auth()->user()->first_name" --}}
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                    avatar="{{ auth()->user()->photo }}"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->full_name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        @fluxScripts
    </body>
</html>
