<x-layouts.modern current="forgot-password">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8">
                <div class="flex flex-col gap-6">
                    <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

                    <!-- Session Status -->
                    <x-auth-session-status class="text-center" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
                        @csrf

                        <!-- Email Address -->
                        <flux:input
                            name="email"
                            :label="__('Email Address')"
                            type="email"
                            required
                            autofocus
                            placeholder="email@example.com"
                        />

                        <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                            {{ __('Email password reset link') }}
                        </flux:button>
                    </form>

                    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
                        <span>{{ __('Or, return to') }}</span>
                        <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.modern>
