@props(['current' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    <!-- Theme initialization script - runs immediately to prevent flash -->
    <script>
        (function () {
            const isDark = localStorage.getItem('theme') === 'dark' ||
                (localStorage.getItem('theme') === null && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Store the current theme state for other scripts
            window.__themeState = { isDark };
        })();
    </script>
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col" style="transition: none;">
    <x-navbar :current="$current" />

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer
        class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-t border-gray-200/20 dark:border-gray-700/30 transition-colors duration-200">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    © 2026 TradingPRO. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Enable transitions after initial load -->
    <script>
        // Enable transitions after everything is loaded
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.transition = 'background-color 200ms, color 200ms';
        });
    </script>

    <!-- Livewire navigation theme persistence -->
    <script>
        document.addEventListener('livewire:navigating', function () {
            // Disable transitions during navigation to prevent flash
            document.body.style.transition = 'none';
        });

        document.addEventListener('livewire:navigated', function () {
            // Reapply theme after Livewire navigation
            const isDark = localStorage.getItem('theme') === 'dark' ||
                (localStorage.getItem('theme') === null && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Update global theme state
            if (!window.__themeState) {
                window.__themeState = {};
            }
            window.__themeState.isDark = isDark;

            // Re-enable transitions after theme is set
            requestAnimationFrame(() => {
                document.body.style.transition = 'background-color 200ms, color 200ms';
            });
        });
    </script>

    @fluxScripts
</body>

</html>