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

<body class="min-h-screen bg-gray-50 dark:bg-gray-900" style="transition: none;">
    <x-navbar :current="$current" />

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Enable transitions after initial load -->
    <script>
        // Enable transitions after everything is loaded
        document.addEventListener('DOMContentLoaded', function() {
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
