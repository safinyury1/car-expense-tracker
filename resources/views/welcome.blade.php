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

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Анимация для скриншота */
        .screenshot {
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }
        
        .screenshot:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px #3b82f6, 0 0 40px #3b82f6, 0 0 60px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="bg-[#EDEEF0] text-gray-900 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="AutoCost" class="h-10 w-auto">
                    </a>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Панель управления
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">
                            Войти
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Регистрация
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="flex-1 flex items-center justify-center" style="margin-top: -40px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Левая колонка -->
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        AutoCost
                    </h1>
                    <p class="text-lg text-gray-600 mb-6">
                        Управляйте расходами на автомобиль легко и удобно
                    </p>
                    
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Расходы" class="w-5 h-5">
                            <span class="text-gray-700">Учёт расходов и заправок</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Статистика" class="w-5 h-5">
                            <span class="text-gray-700">Статистика и графики</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Напоминания" class="w-5 h-5">
                            <span class="text-gray-700">Напоминания о ТО</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Сравнение" class="w-5 h-5">
                            <span class="text-gray-700">Сравнение автомобилей</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition shadow-md">
                                Перейти в приложение
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition shadow-md">
                                Начать отслеживать расходы
                            </a>
                            <a href="{{ route('login') }}" class="border border-gray-300 hover:border-blue-500 text-gray-700 font-semibold py-3 px-6 rounded-xl transition">
                                Уже есть аккаунт? Войти
                            </a>
                        @endauth
                    </div>
                </div>
                
                <!-- Правая колонка - скриншот приложения с анимацией -->
                <div class="relative">
                    <img src="{{ asset('images/screen.png') }}" 
                         alt="AutoCost приложение" 
                         class="screenshot w-full h-auto rounded-2xl shadow-2xl">
                    
                    <!-- Декоративные элементы -->
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-blue-400/20 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-purple-400/20 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                © 2026 AutoCost. Все права защищены.
            </p>
        </div>
    </footer>
</body>
</html>