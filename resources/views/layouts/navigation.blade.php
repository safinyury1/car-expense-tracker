<nav x-data="{ open: false, showMenu: false, showPagesMenu: false, mobileMenuOpen: false }" class="bg-white dark:bg-[#222222] border-b border-gray-100 dark:border-gray-700">
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

                <!-- Десктопное меню (видно на экранах больше 1024px) -->
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

                <!-- Планшетное меню (видно на экранах от 768px до 1024px) -->
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

            <!-- Две круглые кнопки + бургер + аватар -->
            <div class="flex items-center gap-2">
                <!-- Первая кнопка: Добавить (плюс) -->
                <div class="relative">
                    <button @click="showMenu = !showMenu" 
                            class="w-10 h-10 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center shadow-lg transition duration-200 focus:outline-none">
                        <img src="{{ asset('images/icons/plus.png') }}" alt="Добавить" class="w-5 h-5">
                    </button>
                    
                    <div x-show="showMenu" 
                         @click.away="showMenu = false"
                         x-cloak
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#222222] rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 z-50"
                         style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('refuelings.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/zapravka.png') }}" alt="Заправка" class="w-4 h-4">
                                Добавить заправку
                            </a>
                            <a href="{{ route('expenses.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/money.png') }}" alt="Расход" class="w-4 h-4">
                                Добавить расход
                            </a>
                            <a href="{{ route('cars.create.form') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/car.png') }}" alt="Автомобиль" class="w-4 h-4">
                                Добавить автомобиль
                            </a>
                            <a href="{{ route('service.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/wrench.png') }}" alt="Обслуживание" class="w-4 h-4">
                                Добавить обслуживание
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <a href="{{ route('incomes.create') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/income1.png') }}" alt="Доход" class="w-4 h-4">
                                Добавить доход
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Вторая кнопка: Страницы (меню) -->
                <div class="relative">
                    <button @click="showPagesMenu = !showPagesMenu" 
                            class="w-10 h-10 rounded-full bg-gray-500 hover:bg-gray-600 text-white flex items-center justify-center shadow-lg transition duration-200 focus:outline-none">
                        <img src="{{ asset('images/icons/menu2.png') }}" alt="Меню" class="w-5 h-5">
                    </button>
                    
                    <div x-show="showPagesMenu" 
                         @click.away="showPagesMenu = false"
                         x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#222222] rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 z-50"
                         style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('cars.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/car2.png') }}" alt="Автомобиль" class="w-4 h-4">
                                Автомобили
                            </a>
                            <a href="{{ route('refuelings.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/zapravka1.png') }}" alt="Заправки" class="w-4 h-4">
                                Заправки
                            </a>
                            <a href="{{ route('expenses.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/consumption3.png') }}" alt="Расходы" class="w-4 h-4">
                                Расходы
                            </a>
                            <a href="{{ route('incomes-list.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-gray-700 transition">
                                <img src="{{ asset('images/icons/income2.png') }}" alt="Доходы" class="w-4 h-4">
                                Доходы
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Бургер-меню (видно только на экранах меньше 1024px, но для iPad показываем на < 768px) -->
                <div class="burger-button">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="rounded-md p-2 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Аватар пользователя (на ПК) -->
                <div class="hidden sm:flex sm:items-center ml-2">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-900 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                @if(Auth::user()->avatar)
                                    <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm mr-2">
                                        {{ substr(Auth::user()->name, 0, 1) }}
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

            <!-- Настройки и Выйти -->
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
        
        /* Десктопное меню - показывается на экранах >= 1024px */
        .desktop-menu {
            display: flex;
            gap: 2rem;
            margin-left: 2.5rem;
        }
        
        /* Планшетное меню - скрыто по умолчанию, показывается от 768px до 1023px */
        .tablet-menu {
            display: none;
            gap: 1rem;
            margin-left: 1.5rem;
        }
        
        /* Бургер-кнопка - скрыта на экранах >= 768px */
        .burger-button {
            display: none;
        }
        
        /* Планшеты (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .desktop-menu {
                display: none !important;
            }
            
            .tablet-menu {
                display: flex !important;
            }
            
            .burger-button {
                display: none !important;
            }
            
            /* Уменьшаем отступы для планшетов */
            .tablet-menu .nav-link {
                font-size: 0.9rem;
                padding: 0.25rem 0;
            }
        }
        
        /* Телефоны (до 767px) */
        @media (max-width: 767px) {
            .desktop-menu {
                display: none !important;
            }
            
            .tablet-menu {
                display: none !important;
            }
            
            .burger-button {
                display: block !important;
            }
        }
        
        /* Десктопы и ноутбуки (от 1024px) */
        @media (min-width: 1024px) {
            .desktop-menu {
                display: flex !important;
            }
            
            .tablet-menu {
                display: none !important;
            }
            
            .burger-button {
                display: none !important;
            }
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            color: #374151 !important;
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
        
        button:focus,
        button:active,
        a:focus,
        a:active {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
</nav>