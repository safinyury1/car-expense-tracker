<nav x-data="{ open: false, showMainMenu: false, mobileMenuOpen: false }" class="bg-white dark:bg-[#222222] border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Левая часть -->
            <div class="flex items-center">
                <!-- Логотип -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('overview.index') }}">
                        <img class="theme-logo block h-9 w-auto"
                             data-light-src="{{ asset('images/logo.png') }}"
                             data-dark-src="{{ asset('images/logo1.png') }}"
                             src="{{ asset('images/logo.png') }}"
                             alt="Logo">
                    </a>
                </div>

                <!-- Десктопное меню -->
                <div class="desktop-menu">
                    <x-nav-link :href="route('overview.index')" :active="request()->routeIs('overview.*')">
                        {{ __('Обзор') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->cars->isNotEmpty())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Статистика') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('history.index')" :active="request()->routeIs('history.*')">
                            {{ __('История') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('reminders.index')" :active="request()->routeIs('reminders.*')">
                            {{ __('Напоминания') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                            {{ __('Категории') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('compare.index')" :active="request()->routeIs('compare.*')">
                            {{ __('Сравнение') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')">
                            {{ __('Мои автомобили') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                            {{ __('Админка') }}
                        </x-nav-link>
                    @endif
                </div>

                <!-- Планшетное меню -->
                <div class="tablet-menu">
                    <x-nav-link :href="route('overview.index')" :active="request()->routeIs('overview.*')">
                        {{ __('Обзор') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->cars->isNotEmpty())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Статистика') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('history.index')" :active="request()->routeIs('history.*')">
                            {{ __('История') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('reminders.index')" :active="request()->routeIs('reminders.*')">
                            {{ __('Напоминания') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                            {{ __('Категории') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('compare.index')" :active="request()->routeIs('compare.*')">
                            {{ __('Сравнение') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')">
                            {{ __('Мои автомобили') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                            {{ __('Админка') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- ОДНА КНОПКА + бургер + аватар -->
            <div class="flex items-center gap-3">
                <!-- Единая прямоугольная кнопка "Действия" -->
                <div class="relative">
                    <button @click="showMainMenu = !showMainMenu" 
                            class="h-9 px-4 rounded-lg bg-blue-500 hover:bg-blue-600 text-white flex items-center gap-2 transition duration-200 focus:outline-none text-sm font-medium shadow-sm">
                        Действия
                    </button>
                    
                    <!-- Выпадающее меню -->
                    <div x-show="showMainMenu" 
                         @click.away="showMainMenu = false"
                         x-cloak
                         class="absolute right-0 mt-2 w-64 bg-white dark:bg-[#222222] rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Раздел: Добавить -->
                        <div class="py-1">
                            <div class="px-3 py-1.5 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                Добавить
                            </div>
                            <a href="{{ route('refuelings.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/zapravka.png') }}" alt="Заправка" class="w-4 h-4">
                                Заправку
                            </a>
                            <a href="{{ route('expenses.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/money.png') }}" alt="Расход" class="w-4 h-4">
                                Расход
                            </a>
                            <a href="{{ route('cars.create.form') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/car.png') }}" alt="Автомобиль" class="w-4 h-4">
                                Автомобиль
                            </a>
                            <a href="{{ route('service.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/wrench.png') }}" alt="Обслуживание" class="w-4 h-4">
                                Обслуживание
                            </a>
                            <a href="{{ route('incomes.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/income1.png') }}" alt="Доход" class="w-4 h-4">
                                Доход
                            </a>
                        </div>
                        
                        <!-- Разделитель -->
                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                        
                        <!-- Раздел: Перейти к -->
                        <div class="py-1">
                            <div class="px-3 py-1.5 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                Перейти к
                            </div>
                            <a href="{{ route('cars.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/car2.png') }}" alt="Автомобили" class="w-4 h-4">
                                Автомобилям
                            </a>
                            <a href="{{ route('refuelings.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/zapravka1.png') }}" alt="Заправки" class="w-4 h-4">
                                Заправкам
                            </a>
                            <a href="{{ route('expenses.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/consumption3.png') }}" alt="Расходы" class="w-4 h-4">
                                Расходам
                            </a>
                            <a href="{{ route('incomes-list.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <img src="{{ asset('images/icons/income2.png') }}" alt="Доходы" class="w-4 h-4">
                                Доходам
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Бургер-меню (мобильная версия) -->
                <div class="burger-button">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="rounded-md p-2 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Аватар пользователя -->
                <div class="hidden sm:flex sm:items-center ml-2">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-900 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                @if(Auth::user()->avatar)
                                    <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center mr-2">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="text-gray-900 dark:text-gray-300">{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4 text-gray-900 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('settings.index')">
                                {{ __('Настройки') }}
                            </x-dropdown-link>

                            <!-- Ссылка на поддержку (только для админа) -->
                            @if(Auth::user()->role === 'admin')
                                <x-dropdown-link :href="route('admin.support.index')">
                                    {{ __('Поддержка') }}
                                    @php $pending = \App\Models\SupportMessage::pending()->count(); @endphp
                                    @if($pending > 0)
                                        <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pending }}</span>
                                    @endif
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Выйти') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>

    <!-- Мобильное меню (бургер) -->
    <div x-show="mobileMenuOpen" 
         x-cloak
         class="mobile-menu bg-white dark:bg-[#222222] border-t border-gray-100 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('overview.index') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
               @click="mobileMenuOpen = false">
                Обзор
            </a>
            
            @if(Auth::user()->cars->isNotEmpty())
                <a href="{{ route('dashboard') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    Статистика
                </a>
                <a href="{{ route('history.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    История
                </a>
                <a href="{{ route('reminders.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    Напоминания
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    Категории
                </a>
                <a href="{{ route('compare.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    Сравнение
                </a>
                <a href="{{ route('cars.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    Мои автомобили
                </a>
            @endif
            
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    Админка
                </a>
            @endif

            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('settings.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                   @click="mobileMenuOpen = false">
                    Настройки
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                            @click="mobileMenuOpen = false">
                        Выйти
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        
        .desktop-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-left: 2.5rem;
        }
        
        .tablet-menu {
            display: none;
            align-items: center;
            gap: 1rem;
            margin-left: 1.5rem;
        }
        
        .burger-button {
            display: none;
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            color: #374151 !important;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            height: 100%;
            line-height: 1;
        }
        
        .nav-link:hover {
            color: #1f2937 !important;
        }
        
        .nav-link-active {
            color: #1f2937 !important;
        }
        
        .nav-link-active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #017CFA;
            border-radius: 2px;
        }
        
        .dark .nav-link {
            color: #9ca3af !important;
        }
        
        .dark .nav-link:hover {
            color: #f3f4f6 !important;
        }
        
        .dark .nav-link-active {
            color: #f3f4f6 !important;
        }
        
        @media (min-width: 768px) and (max-width: 1023px) {
            .desktop-menu { display: none !important; }
            .tablet-menu { display: flex !important; gap: 0.75rem !important; }
            .burger-button { display: none !important; }
            .tablet-menu .nav-link { font-size: 0.9rem; padding: 0.25rem 0; }
        }
        
        @media (max-width: 767px) {
            .desktop-menu { display: none !important; }
            .tablet-menu { display: none !important; }
            .burger-button { display: block !important; }
        }
        
        @media (min-width: 1024px) {
            .desktop-menu { display: flex !important; gap: 1.75rem !important; }
            .tablet-menu { display: none !important; }
            .burger-button { display: none !important; }
        }
        
        button:focus,
        button:active,
        a:focus,
        a:active {
            outline: none !important;
            box-shadow: none !important;
        }
        
        .flex.items-center {
            align-items: center;
        }
        
        .desktop-menu,
        .tablet-menu {
            height: 100%;
        }
    </style>
</nav>