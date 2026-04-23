<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutoCost') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#EDEEF0] dark:bg-[#141414] text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-[#222222] rounded-2xl shadow-xl p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

    <footer class="py-4 text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            © {{ date('Y') }} AutoCost. Все права защищены.
        </p>
    </footer>
</body>
</html>