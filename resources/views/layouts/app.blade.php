<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutoCost') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

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
        
        // Смена логотипа при переключении темы
        function updateLogo() {
            const isDark = document.documentElement.classList.contains('dark');
            const logos = document.querySelectorAll('.theme-logo');
            logos.forEach(logo => {
                if (isDark) {
                    logo.src = logo.dataset.darkSrc;
                } else {
                    logo.src = logo.dataset.lightSrc;
                }
            });
        }
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateLogo();
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true });
        document.addEventListener('DOMContentLoaded', updateLogo);
    </script>
    
    <style>
        /* Убираем синие квадраты при наведении и клике */
        * {
            -webkit-tap-highlight-color: transparent !important;
        }

        *:focus,
        *:active,
        *:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            ring: none !important;
        }

        button:focus,
        button:active,
        a:focus,
        a:active,
        .nav-link:focus,
        .nav-link:active,
        .rounded-full:focus,
        .rounded-full:active,
        [x-nav-link]:focus,
        [x-nav-link]:active {
            outline: none !important;
            box-shadow: none !important;
            ring: 0 !important;
            ring-offset: 0 !important;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
            box-shadow: none !important;
            ring: 0 !important;
        }
        
        /* Неон для круглых кнопок */
        .rounded-full {
            transition: all 0.2s ease-in-out !important;
        }
        
        .rounded-full:hover {
            box-shadow: 0 0 10px currentColor, 0 0 20px currentColor !important;
            transform: scale(1.05);
        }
        
        .bg-blue-500.rounded-full:hover {
            box-shadow: 0 0 15px #3b82f6, 0 0 25px #3b82f6 !important;
        }
        
        .bg-gray-500.rounded-full:hover {
            box-shadow: 0 0 15px #6b7280, 0 0 25px #6b7280 !important;
        }
        
        .bg-blue-500:not(.rounded-full):hover {
            box-shadow: 0 0 15px #3b82f6, 0 0 25px #3b82f6 !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .bg-green-500:hover {
            box-shadow: 0 0 15px #10b981, 0 0 25px #10b981 !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .bg-yellow-500:hover {
            box-shadow: 0 0 15px #eab308, 0 0 25px #eab308 !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .bg-red-500:hover {
            box-shadow: 0 0 15px #ef4444, 0 0 25px #ef4444 !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .bg-gray-500:not(.rounded-full):hover {
            box-shadow: 0 0 15px #6b7280, 0 0 25px #6b7280 !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .nav-link:hover {
            text-shadow: 0 0 5px #017CFA, 0 0 10px #017CFA !important;
            color: #017CFA !important;
            transition: all 0.2s ease-in-out !important;
            background-color: transparent !important;
        }
        
        .dropdown-item:hover {
            text-shadow: 0 0 5px #017CFA, 0 0 10px #017CFA !important;
            color: #017CFA !important;
            transition: all 0.2s ease-in-out !important;
            background-color: transparent !important;
        }
        
        .dark .nav-link:hover {
            text-shadow: 0 0 5px #017CFA, 0 0 10px #017CFA !important;
            color: #60a5fa !important;
        }
        
        /* Неоновая подсветка снизу карточки */
        .neon-glow-bottom {
            position: relative;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, #3b82f6, #3b82f6, #3b82f6, #3b82f6, #3b82f6, transparent);
            border-radius: 4px;
            filter: blur(2px);
            box-shadow: 0 0 10px #3b82f6, 0 0 20px #3b82f6;
            animation: neonPulse 1.5s ease-in-out infinite alternate;
        }
        
        @keyframes neonPulse {
            0% {
                box-shadow: 0 0 5px #3b82f6, 0 0 10px #3b82f6;
                opacity: 0.8;
            }
            100% {
                box-shadow: 0 0 15px #3b82f6, 0 0 30px #3b82f6, 0 0 40px #3b82f6;
                opacity: 1;
            }
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