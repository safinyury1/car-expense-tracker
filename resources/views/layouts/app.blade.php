<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Car Expense Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Загрузка сохранённой темы
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <style>
        /* Глобальные анимации для всех интерактивных элементов */
        a, button, .nav-link, .dropdown-item, tbody tr, .hover-transition, .rounded-full {
            transition: all 0.2s ease-in-out !important;
        }
        
        /* Убираем фоновую подсветку (квадраты) */
        a:hover,
        button:hover,
        .nav-link:hover,
        .dropdown-item:hover,
        tbody tr:hover,
        .responsive-nav-link:hover,
        .bg-blue-500:hover,
        .bg-green-500:hover,
        .bg-yellow-500:hover,
        .bg-red-500:hover,
        .bg-gray-500:hover,
        .rounded-full:hover {
            background-color: transparent !important;
        }
        
        /* НЕОН для светлой темы - только свечение, без фона */
        a:not(.no-hover):hover,
        button:not(.no-hover):hover,
        .nav-link:hover,
        .dropdown-item:hover,
        tbody tr:hover,
        .hover-light:hover,
        .responsive-nav-link:hover {
            box-shadow: 0 0 10px #017CFA, 0 0 20px #017CFA !important;
            text-shadow: 0 0 5px #017CFA !important;
        }
        
        /* НЕОН для тёмной темы */
        .dark a:not(.no-hover):hover,
        .dark button:not(.no-hover):hover,
        .dark .nav-link:hover,
        .dark .dropdown-item:hover,
        .dark tbody tr:hover,
        .dark .hover-dark:hover,
        .dark .responsive-nav-link:hover {
            box-shadow: 0 0 10px #017CFA, 0 0 20px #017CFA !important;
            text-shadow: 0 0 5px #017CFA !important;
        }
        
        /* Неон для синей кнопки */
        .bg-blue-500:hover {
            box-shadow: 0 0 15px #3b82f6, 0 0 25px #3b82f6 !important;
        }
        
        /* Неон для зелёной кнопки */
        .bg-green-500:hover {
            box-shadow: 0 0 15px #10b981, 0 0 25px #10b981 !important;
        }
        
        /* Неон для жёлтой кнопки */
        .bg-yellow-500:hover {
            box-shadow: 0 0 15px #eab308, 0 0 25px #eab308 !important;
        }
        
        /* Неон для красной кнопки */
        .bg-red-500:hover {
            box-shadow: 0 0 15px #ef4444, 0 0 25px #ef4444 !important;
        }
        
        /* Неон для серой кнопки */
        .bg-gray-500:hover {
            box-shadow: 0 0 15px #6b7280, 0 0 25px #6b7280 !important;
        }
        
        /* Неон для круглых кнопок */
        .rounded-full:hover {
            box-shadow: 0 0 10px currentColor, 0 0 20px currentColor !important;
            transform: scale(1.05);
        }
        
        /* Неон для ссылок в навигации - только свечение текста */
        .nav-link:hover {
            text-shadow: 0 0 5px #017CFA, 0 0 10px #017CFA !important;
        }
        
        /* Убираем белый квадрат при клике */
        button:focus,
        button:active,
        a:focus,
        a:active,
        .nav-link:focus,
        .nav-link:active,
        .dropdown-item:focus,
        .dropdown-item:active,
        .rounded-full:focus,
        .rounded-full:active,
        *:focus {
            outline: none !important;
            box-shadow: none !important;
            ring: none !important;
            -webkit-tap-highlight-color: transparent !important;
        }
        
        /* Убираем фон у строк таблицы при наведении */
        tbody tr:hover td,
        tbody tr:hover {
            background-color: transparent !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#EDEEF0] dark:bg-[#141414]">
    <div class="min-h-screen bg-[#EDEEF0] dark:bg-[#141414]">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-[#222222] shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>