@props(['current' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <x-navbar :current="$current" />

    <main class="min-h-screen">
        {{ $slot }}
    </main>
</body>

</html>