<x-layouts.modern current="login">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8">
                <div class="flex flex-col gap-6">
                    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your TP Analytics email and password below to log in')" />

                    <!-- Session Status -->
                    <x-auth-session-status class="text-center" :status="session('status')" />

                    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                        @csrf

                        <!-- Email Address -->
                        <flux:input name="email" :label="__('Email address')" type="email" required autofocus autocomplete="email"
                            placeholder="email@example.com" />

                        <!-- Password -->
                        <div class="relative">
                            <flux:input name="password" :label="__('Password')" type="password" required
                                autocomplete="current-password" :placeholder="__('Password')" viewable />

                            @if (Route::has('password.request'))
                                <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                                    {{ __('Forgot your password?') }}
                                </flux:link>
                            @endif
                        </div>

                        <!-- Remember Me -->
                        <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                                {{ __('Log in') }}
                            </flux:button>
                        </div>
                    </form>

                    @if (Route::has('register'))
                        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                            <span>{{ __('Don\'t have an account?') }}</span>
                            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.modern>