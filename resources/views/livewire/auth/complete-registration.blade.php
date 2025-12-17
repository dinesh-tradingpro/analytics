<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Complete Your Registration')"
            :description="__('Your email is authorized. Please set up your account by providing your name and password.')"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            {{ __('There were some errors with your submission') }}
                        </h3>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('complete-registration.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email (read-only) -->
            <flux:input 
                name="email" 
                :label="__('Email address')" 
                type="email" 
                value="{{ session('registration_email') }}"
                readonly
                disabled
            />
            
            <input type="hidden" name="email" value="{{ session('registration_email') }}" />

            <!-- Full Name -->
            <div>
                <flux:input 
                    name="name" 
                    :label="__('Full Name')" 
                    type="text" 
                    required 
                    autofocus
                    autocomplete="name" 
                    placeholder="John Doe"
                    :value="old('name')"
                />
                @error('name')
                    <flux:text color="red" class="mt-1 text-sm">
                        {{ $message }}
                    </flux:text>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <flux:input 
                    name="password" 
                    :label="__('Password')" 
                    type="password" 
                    required
                    autocomplete="new-password" 
                    :placeholder="__('Password')"
                    viewable
                />
                @error('password')
                    <flux:text color="red" class="mt-1 text-sm">
                        {{ $message }}
                    </flux:text>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <flux:input 
                    name="password_confirmation" 
                    :label="__('Confirm Password')" 
                    type="password" 
                    required
                    autocomplete="new-password" 
                    :placeholder="__('Confirm Password')"
                    viewable
                />
                @error('password_confirmation')
                    <flux:text color="red" class="mt-1 text-sm">
                        {{ $message }}
                    </flux:text>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Complete Registration') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
