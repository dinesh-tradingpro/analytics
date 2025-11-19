<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.modern', ['current' => 'settings'])] class extends Component {
    public $theme = 'system';

    public function mount()
    {
        // Get current theme from localStorage (will be set via JavaScript)
        $this->theme = 'system'; // Default to system
    }

    public function updatedTheme()
    {
        // Theme will be handled by JavaScript
        $this->dispatch('theme-changed', theme: $this->theme);
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <section class="w-full">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
            <div x-data="{
                theme: @entangle('theme'),
                init() {
                    // Get current theme from localStorage
                    const savedTheme = localStorage.getItem('theme') || 'system';
                    this.theme = savedTheme;
                    this.$wire.theme = savedTheme;
                },
                updateTheme(value) {
                    this.theme = value;
                    this.$wire.theme = value;

                    // Disable transitions temporarily to prevent flashing
                    const htmlElement = document.documentElement;
                    htmlElement.style.transition = 'none';

                    if (value === 'system') {
                        localStorage.removeItem('theme');
                        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                            htmlElement.classList.add('dark');
                        } else {
                            htmlElement.classList.remove('dark');
                        }
                    } else {
                        localStorage.setItem('theme', value);
                        if (value === 'dark') {
                            htmlElement.classList.add('dark');
                        } else {
                            htmlElement.classList.remove('dark');
                        }
                    }

                    // Re-enable transitions after a short delay
                    setTimeout(() => {
                        htmlElement.style.transition = '';
                    }, 50);

                    // Update navbar theme toggle icons
                    const moonIcon = document.getElementById('moonIcon');
                    const sunIcon = document.getElementById('sunIcon');
                    if (moonIcon && sunIcon) {
                        if (value === 'dark' || (value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                            moonIcon.classList.add('hidden');
                            sunIcon.classList.remove('hidden');
                        } else {
                            moonIcon.classList.remove('hidden');
                            sunIcon.classList.add('hidden');
                        }
                    }
                }
            }">
                <flux:radio.group variant="segmented" x-model="theme" @change="updateTheme($event.target.value)">
                    <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                    <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                    <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
                </flux:radio.group>
            </div>
        </x-settings.layout>
    </section>
</div>