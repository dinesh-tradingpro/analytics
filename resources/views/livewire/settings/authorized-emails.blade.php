<?php

use App\Models\AuthorizedEmail;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.modern', ['current' => 'settings'])] class extends Component {
    public string $email = '';
    public string $notes = '';
    public array $authorizedEmails = [];

    public function mount(): void
    {
        $this->loadAuthorizedEmails();
    }

    public function loadAuthorizedEmails(): void
    {
        $this->authorizedEmails = AuthorizedEmail::orderBy('email')
            ->get()
            ->map(function ($email) {
                return [
                    'id' => $email->id,
                    'email' => $email->email,
                    'added_by_email' => $email->added_by_email,
                    'notes' => $email->notes,
                    'is_active' => $email->is_active,
                    'created_at' => $email->created_at?->format('M j, Y g:i A'),
                ];
            })
            ->toArray();
    }

    public function addEmail(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'unique:authorized_emails,email'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        AuthorizedEmail::create([
            'email' => $this->email,
            'notes' => $this->notes,
            'added_by_email' => auth()->user()->email,
            'is_active' => true,
        ]);

        $this->reset('email', 'notes');
        $this->loadAuthorizedEmails();

        $this->dispatch('email-added');
    }

    public function toggleStatus(int $id): void
    {
        $authorizedEmail = AuthorizedEmail::findOrFail($id);
        $authorizedEmail->update(['is_active' => !$authorizedEmail->is_active]);

        $this->loadAuthorizedEmails();
    }

    public function deleteEmail(int $id): void
    {
        $authorizedEmail = AuthorizedEmail::findOrFail($id);

        // Prevent deletion of admin emails
        $adminEmails = config('authorized_emails.admin_emails', []);
        if (in_array($authorizedEmail->email, $adminEmails)) {
            $this->addError('delete', 'Admin emails cannot be deleted.');
            return;
        }

        $authorizedEmail->delete();
        $this->loadAuthorizedEmails();
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <section class="w-full">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Authorized Emails')" :subheading="__('Manage which email addresses can register for this application')">

            <!-- Add New Email Form -->
            <form wire:submit="addEmail" class="mb-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add New Authorized Email</h3>

                <div class="space-y-4">
                    <flux:input wire:model="email" :label="__('Email Address')" type="email" required
                        placeholder="user@example.com" />

                    <flux:textarea wire:model="notes" :label="__('Notes (Optional)')" rows="3"
                        placeholder="Add any notes about this email authorization..." />

                    <div class="flex items-center gap-4">
                        <flux:button variant="primary" type="submit">
                            {{ __('Add Email') }}
                        </flux:button>

                        <x-action-message class="ms-3" on="email-added">
                            {{ __('Email added successfully.') }}
                        </x-action-message>
                    </div>
                </div>
            </form>

            <!-- Authorized Emails List -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Authorized Emails ({{ count($authorizedEmails) }})
                </h3>

                @if(count($authorizedEmails) > 0)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($authorizedEmails as $authorizedEmail)
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $authorizedEmail['email'] }}
                                            </span>

                                            @if(!$authorizedEmail['is_active'])
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Inactive
                                                </span>
                                            @endif

                                            @if(in_array($authorizedEmail['email'], config('authorized_emails.admin_emails', [])))
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    Admin
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Added by {{ $authorizedEmail['added_by_email'] }} •
                                            {{ $authorizedEmail['created_at'] }}
                                        </div>

                                        @if($authorizedEmail['notes'])
                                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                {{ $authorizedEmail['notes'] }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <button wire:click="toggleStatus({{ $authorizedEmail['id'] }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                            {{ $authorizedEmail['is_active'] ? 'Deactivate' : 'Activate' }}
                                        </button>

                                        @if(!in_array($authorizedEmail['email'], config('authorized_emails.admin_emails', [])))
                                            <button wire:click="deleteEmail({{ $authorizedEmail['id'] }})"
                                                onclick="return confirm('Are you sure you want to delete this email?')"
                                                class="inline-flex items-center px-3 py-1.5 border border-red-300 dark:border-red-600 shadow-sm text-xs font-medium rounded-md text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>No authorized emails found.</p>
                        <p class="text-sm mt-1">Add email addresses to control who can register.</p>
                    </div>
                @endif

                @error('delete')
                    <div
                        class="mt-4 p-3 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 rounded-md">
                        <p class="text-sm text-red-700 dark:text-red-300">{{ $message }}</p>
                    </div>
                @enderror
            </div>

        </x-settings.layout>
    </section>
</div>