<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-56">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-3 mb-3 flex items-center space-x-2 rtl:space-x-reverse"
            wire:navigate>
            <x-app-logo class="w-8 h-8" />
        </a>

        <flux:navlist variant="outline" class="space-y-1">
            <flux:navlist.group :heading="__('Platform')" class="grid gap-1">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate class="px-3 py-2 text-sm">{{ __('Dashboard') }}</flux:navlist.item>
                <flux:navlist.item icon="credit-card" :href="route('transactions')"
                    :current="request()->routeIs('transactions')" wire:navigate class="px-3 py-2 text-sm">
                    {{ __('Transactions') }}</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        {{-- <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </flux:navlist.item>
        </flux:navlist> --}}

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" data-test="sidebar-menu-button" class="px-3 py-2 text-sm" />

            <flux:menu class="w-[200px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-xs font-normal">
                        <div class="flex items-center gap-2 px-2 py-1.5 text-start text-xs">
                            <span class="relative flex h-6 w-6 shrink-0 overflow-hidden rounded-md">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-md bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white text-xs">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-xs leading-tight">
                                <span class="truncate font-medium">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs opacity-70">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate class="px-3 py-1.5 text-xs">
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full px-3 py-1.5 text-xs" data-test="logout-button">
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
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" class="text-sm" />

            <flux:menu class="w-[180px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-xs font-normal">
                        <div class="flex items-center gap-2 px-2 py-1.5 text-start text-xs">
                            <span class="relative flex h-6 w-6 shrink-0 overflow-hidden rounded-md">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-md bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white text-xs">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-xs leading-tight">
                                <span class="truncate font-medium">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs opacity-70">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate class="px-3 py-1.5 text-xs">
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full px-3 py-1.5 text-xs" data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
    @stack('scripts')
</body>

</html>