<x-layouts.modern current="login">
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8"
        x-data="{
            step: '{{ session('login_email') ? 'password' : 'email' }}',
            email: '{{ session('login_email', old('email', '')) }}'
        }"
    >
        <div class="max-w-md w-full space-y-8">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8">
                <div class="flex flex-col gap-6">
                    <!-- Step 1: Email -->
                    <div x-show="step === 'email'" x-cloak>
                        <x-auth-header
                            :title="__('Welcome')"
                            :description="__('Enter your email address to continue')"
                        />

                        <!-- Session Status -->
                        <x-auth-session-status class="text-center" :status="session('status')" />

                        <form method="POST" action="{{ route('login.check-email') }}" class="flex flex-col gap-6 mt-6">
                            @csrf

                            <flux:input
                                name="email"
                                :label="__('Email address')"
                                type="email"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="email@example.com"
                                x-model="email"
                                :value="old('email')"
                            />

                            <flux:button
                                variant="primary"
                                type="submit"
                                class="w-full"
                            >
                                {{ __('Continue') }}
                            </flux:button>
                        </form>
                    </div>

                    <!-- Step 2: Password -->
                    <div x-show="step === 'password'" x-cloak>
                        <x-auth-header
                            :title="__('Enter your password')"
                            :description="session('login_email', '')"
                        />

                        <!-- Session Status -->
                        <x-auth-session-status class="text-center" :status="session('status')" />

                        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6 mt-6">
                            @csrf

                            <input type="hidden" name="email" value="{{ session('login_email') }}" />

                            <!-- Password -->
                            <div class="relative">
                                <flux:input
                                    name="password"
                                    :label="__('Password')"
                                    type="password"
                                    required
                                    autofocus
                                    autocomplete="current-password"
                                    :placeholder="__('Password')"
                                    viewable
                                />

                                @if (Route::has('password.request'))
                                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')"
                                        wire:navigate>
                                        {{ __('Forgot your password?') }}
                                    </flux:link>
                                @endif
                            </div>

                            <!-- Remember Me -->
                            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                            <div class="flex flex-col gap-3">
                                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                                    {{ __('Log in') }}
                                </flux:button>

                                <flux:button
                                    variant="ghost"
                                    type="button"
                                    class="w-full"
                                    onclick="window.location.href='{{ route('login', ['clear' => 1]) }}'"
                                >
                                    {{ __('Use a different email') }}
                                </flux:button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @fluxScripts()
</x-layouts.modern>
