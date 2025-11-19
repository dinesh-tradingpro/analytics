<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <flux:main class="min-h-screen">
        {{ $slot }}
    </flux:main>
</body>

</html>