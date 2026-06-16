<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutoCost') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">

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
        
        /* Адаптивные стили */
        @media (max-width: 640px) {
            .hero-section {
                margin-top: 0 !important;
            }
            .screenshot:hover {
                transform: scale(1.01);
            }
            /* На мобильных картинка выше */
            .mobile-order {
                order: -1;
            }
        }
        
        @media (min-width: 1024px) {
            .desktop-order {
                order: 0;
            }
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
                        <img src="{{ asset('images/logo.png') }}" alt="AutoCost" class="h-8 sm:h-10 w-auto">
                    </a>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">
                            Панель управления
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition text-sm sm:text-base">
                            Войти
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">
                            Регистрация
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="flex-1 flex items-center justify-center" style="margin-top: -20px; margin-bottom: -20px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
            <div class="grid lg:grid-cols-2 gap-6 lg:gap-12 items-center">
                <!-- Правая колонка - скриншот (на мобильных будет сверху) -->
                <div class="relative mobile-order lg:order-none mt-0 lg:mt-0">
                    <img src="{{ asset('images/screen.png') }}" 
                         alt="AutoCost приложение" 
                         class="screenshot w-full h-auto rounded-xl sm:rounded-2xl shadow-2xl">
                    
                    <!-- Декоративные элементы (скрыты на мобильных) -->
                    <div class="hidden sm:block absolute -top-4 -right-4 w-20 sm:w-24 h-20 sm:h-24 bg-blue-400/20 rounded-full blur-2xl"></div>
                    <div class="hidden sm:block absolute -bottom-4 -left-4 w-28 sm:w-32 h-28 sm:h-32 bg-purple-400/20 rounded-full blur-2xl"></div>
                </div>
                
                <!-- Левая колонка (текст и кнопки) -->
                <div class="text-center lg:text-left">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                        AutoCost
                    </h1>
                    <p class="text-base sm:text-lg text-gray-600 mb-5 sm:mb-6">
                        Управляйте расходами на автомобиль легко и удобно
                    </p>
                    
                    <div class="space-y-2 sm:space-y-3 mb-6 sm:mb-8">
                        <div class="flex items-center justify-center lg:justify-start gap-2 sm:gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Расходы" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="text-sm sm:text-base text-gray-700">Учёт расходов и заправок</span>
                        </div>
                        <div class="flex items-center justify-center lg:justify-start gap-2 sm:gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Статистика" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="text-sm sm:text-base text-gray-700">Статистика и графики</span>
                        </div>
                        <div class="flex items-center justify-center lg:justify-start gap-2 sm:gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Напоминания" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="text-sm sm:text-base text-gray-700">Напоминания о ТО</span>
                        </div>
                        <div class="flex items-center justify-center lg:justify-start gap-2 sm:gap-3">
                            <img src="{{ asset('images/icons/tick.png') }}" alt="Сравнение" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="text-sm sm:text-base text-gray-700">Сравнение автомобилей</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition shadow-md text-center text-sm sm:text-base">
                                Перейти в приложение
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition shadow-md text-center text-sm sm:text-base">
                                Начать отслеживать расходы
                            </a>
                            <a href="{{ route('login') }}" class="border border-gray-300 hover:border-blue-500 text-gray-700 font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition text-center text-sm sm:text-base">
                                Войти
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-4 sm:py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs sm:text-sm text-gray-500">
                © {{ date('Y') }} AutoCost. Все права защищены.
            </p>
        </div>
    </footer>
</body>
</html>