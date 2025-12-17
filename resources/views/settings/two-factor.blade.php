<x-layouts.modern current="settings">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <section class="w-full">
            @include('partials.settings-heading')

            <x-settings.layout
                :heading="__('Two Factor Authentication')"
                :subheading="__('Manage your two-factor authentication settings')"
            >
                @php
                    $user = auth()->user();
                    $enabled = ! is_null($user->two_factor_secret);
                    $confirmed = ! is_null($user->two_factor_confirmed_at);
                    $requiresConfirmation = \Laravel\Fortify\Features::optionEnabled(\Laravel\Fortify\Features::twoFactorAuthentication(), 'confirm');
                    $qrCodeSvg = null;
                    $setupKey = null;
                    $recoveryCodes = [];

                    if ($enabled) {
                        try {
                            $qrCodeSvg = $user->twoFactorQrCodeSvg();
                            $setupKey = decrypt($user->two_factor_secret);
                            $recoveryCodes = $user->recoveryCodes() ?? [];
                        } catch (\Throwable $e) {
                            $qrCodeSvg = null;
                            $setupKey = null;
                        }
                    }
                @endphp

                @if (session('status'))
                    <flux:callout variant="success" icon="check-circle">{{ session('status') }}</flux:callout>
                @endif

                @if ($errors->any())
                    <flux:callout variant="danger" icon="x-circle">
                        {{ __('Something went wrong. Please check the details below.') }}
                    </flux:callout>
                @endif

                <div class="space-y-8 text-sm">
                    <div class="flex flex-col gap-3 rounded-2xl border border-gray-200/60 dark:border-gray-800 bg-white/90 dark:bg-gray-900/80 p-4 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                @if ($enabled && $confirmed)
                                    <flux:badge color="green">{{ __('Enabled') }}</flux:badge>
                                    <flux:text class="text-xs sm:text-sm">{{ __('Two-factor is protecting this account.') }}</flux:text>
                                @elseif($enabled && ! $confirmed)
                                    <flux:badge color="yellow">{{ __('Awaiting confirmation') }}</flux:badge>
                                    <flux:text class="text-xs sm:text-sm">{{ __('Finish by entering a 6-digit code from your authenticator.') }}</flux:text>
                                @else
                                    <flux:badge color="red">{{ __('Disabled') }}</flux:badge>
                                    <flux:text class="text-xs sm:text-sm">{{ __('Add a second factor to secure your login.') }}</flux:text>
                                @endif
                            </div>

                            @if (! $enabled)
                                <form method="POST" action="{{ route('two-factor.enable') }}">
                                    @csrf
                                    <flux:button variant="primary" icon="shield-check" icon:variant="outline" type="submit">
                                        {{ __('Enable 2FA') }}
                                    </flux:button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('two-factor.disable') }}">
                                    @csrf
                                    @method('delete')
                                    <flux:button variant="ghost" icon="shield-exclamation" icon:variant="outline" type="submit">
                                        {{ __('Disable 2FA') }}
                                    </flux:button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if (! $enabled)
                        <div class="rounded-2xl border border-gray-200/60 dark:border-gray-800 bg-white/90 dark:bg-gray-900/80 p-6 shadow-sm space-y-4">
                            <flux:heading size="md">{{ __('Why enable 2FA?') }}</flux:heading>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 list-disc list-inside">
                                <li>{{ __('Prevents account takeover with a second factor.') }}</li>
                                <li>{{ __('Works with any TOTP app like Authy, 1Password, or Google Authenticator.') }}</li>
                                <li>{{ __('Gives you recovery codes to regain access if you lose your device.') }}</li>
                            </ul>
                        </div>
                    @else
                        <div class="space-y-6">
                            {{-- Step 1: Scan QR Code --}}
                            <div class="rounded-2xl border border-gray-200/60 dark:border-gray-800 bg-white/90 dark:bg-gray-900/80 p-6 shadow-sm space-y-4">
                                <div class="space-y-2">
                                    <flux:heading size="md">{{ __('Step 1 · Scan the code') }}</flux:heading>
                                    <flux:text>{{ __('Scan with your authenticator app or use the setup key below.') }}</flux:text>
                                </div>

                                <div class="flex flex-col md:flex-row gap-6 items-start">
                                    <div class="flex justify-center w-full md:w-auto">
                                        <div class="relative w-64 aspect-square overflow-hidden border rounded-2xl border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-800 shadow-inner">
                                            @if ($qrCodeSvg)
                                                <div class="flex items-center justify-center h-full p-4">
                                                    <div class="bg-white p-3 rounded">
                                                        {!! $qrCodeSvg !!}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="absolute inset-0 flex items-center justify-center animate-pulse text-stone-400">
                                                    <flux:icon.loading />
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex-1 space-y-3 min-w-0">
                                        <flux:subheading>{{ __('Manual setup key') }}</flux:subheading>
                                        <div class="flex items-center w-full border rounded-xl dark:border-stone-700 overflow-hidden bg-gray-50/60 dark:bg-gray-800/60">
                                            <input
                                                type="text"
                                                readonly
                                                value="{{ $setupKey ?? '' }}"
                                                class="w-full p-3 bg-transparent outline-none text-stone-900 dark:text-stone-100 text-sm font-mono"
                                            />
                                            <button
                                                type="button"
                                                class="px-4 py-3 transition-colors border-l cursor-pointer border-stone-200 dark:border-stone-600 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                x-data="{ copied: false }"
                                                x-on:click="navigator.clipboard.writeText('{{ $setupKey ?? '' }}').then(() => { copied = true; setTimeout(() => copied = false, 1500); });"
                                            >
                                                <flux:icon.document-duplicate x-show="!copied" variant="outline" class="w-5 h-5" />
                                                <flux:icon.check x-show="copied" variant="solid" class="w-5 h-5 text-green-500" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 2: Confirm or Success --}}
                            @if ($requiresConfirmation && ! $confirmed)
                                <div class="rounded-2xl border border-amber-300 dark:border-amber-600 bg-amber-50 dark:bg-amber-900/30 p-6 shadow-sm space-y-4">
                                    <div class="flex items-center gap-2 text-amber-800 dark:text-amber-200">
                                        <flux:icon.shield-check class="h-5 w-5" />
                                        <flux:heading size="md" class="!text-amber-900 dark:!text-amber-100">{{ __('Step 2 · Confirm') }}</flux:heading>
                                    </div>
                                    <flux:text class="text-amber-900/90 dark:text-amber-100/90">
                                        {{ __('Open your authenticator app and enter the 6-digit code shown for TP Analytics.') }}
                                    </flux:text>
                                    <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                                        @csrf
                                        <div class="flex justify-center">
                                            <x-input-otp :digits="6" name="code" autocomplete="one-time-code" />
                                        </div>
                                        @error('code')
                                            <flux:text color="red" class="text-center">{{ $message }}</flux:text>
                                        @enderror
                                        <flux:button variant="primary" type="submit" class="w-full">
                                            {{ __('Confirm & Enable 2FA') }}
                                        </flux:button>
                                    </form>
                                </div>
                            @else
                                <div class="rounded-2xl border border-emerald-300 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 p-6 shadow-sm space-y-2">
                                    <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-200">
                                        <flux:icon.check class="h-5 w-5" />
                                        <flux:heading size="md" class="!text-emerald-900 dark:!text-emerald-100">{{ __('Device paired') }}</flux:heading>
                                    </div>
                                    <flux:text class="text-emerald-900/80 dark:text-emerald-100/80">{{ __('Your authenticator app is linked. Keep your recovery codes safe.') }}</flux:text>
                                </div>
                            @endif

                            {{-- Recovery Codes --}}
                            <div class="rounded-2xl border border-gray-200/60 dark:border-gray-800 bg-white/90 dark:bg-gray-900/80 p-6 shadow-sm space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="space-y-1">
                                        <flux:heading size="md">{{ __('Recovery codes') }}</flux:heading>
                                        <flux:text variant="subtle" class="text-sm">{{ __('Store these in a password manager. Each code can be used once.') }}</flux:text>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2" x-data="{ copied: false }">
                                        <button
                                            type="button"
                                            x-on:click="navigator.clipboard.writeText('{{ implode("\n", $recoveryCodes) }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); });"
                                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors w-[120px]"
                                        >
                                            <flux:icon.document-duplicate x-show="!copied" variant="mini" class="w-4 h-4" />
                                            <flux:icon.check x-show="copied" variant="mini" class="w-4 h-4 text-green-500" />
                                            <span x-text="copied ? '{{ __('Copied!') }}' : '{{ __('Copy All') }}'"></span>
                                        </button>
                                        <button
                                            type="button"
                                            x-on:click="
                                                const codes = @js($recoveryCodes);
                                                const blob = new Blob([codes.join('\n')], { type: 'text/plain' });
                                                const url = URL.createObjectURL(blob);
                                                const a = document.createElement('a');
                                                a.href = url;
                                                a.download = 'tp-analytics-recovery-codes.txt';
                                                document.body.appendChild(a);
                                                a.click();
                                                document.body.removeChild(a);
                                                URL.revokeObjectURL(url);
                                            "
                                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors w-[120px]"
                                        >
                                            <flux:icon.arrow-down-tray variant="mini" class="w-4 h-4" />
                                            <span>{{ __('Download') }}</span>
                                        </button>
                                        <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}" class="inline-block">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors w-[120px]"
                                            >
                                                <flux:icon.arrow-path variant="mini" class="w-4 h-4" />
                                                <span>{{ __('Regenerate') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    @forelse ($recoveryCodes as $code)
                                        <div class="px-3 py-2.5 text-sm font-mono rounded-lg border border-stone-200 dark:border-stone-700 bg-gray-50/80 dark:bg-gray-800/70 text-center">
                                            {{ $code }}
                                        </div>
                                    @empty
                                        <div class="col-span-2">
                                            <flux:text variant="subtle">{{ __('Recovery codes will appear here after enabling 2FA.') }}</flux:text>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </x-settings.layout>
        </section>
    </div>
</x-layouts.modern>
