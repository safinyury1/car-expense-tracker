<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('История операций') }}
            </h2>
            <button onclick="window.print()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Экспорт PDF</span>
            </button>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- ВСЯ СТРАНИЦА ОБЁРНУТА ДЛЯ PDF -->
            <div id="pdf-content">
                <!-- Заголовок для PDF с логотипом -->
                <div class="pdf-header" style="display: none;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ asset('images/logo.png') }}" alt="AutoCost" style="height: 40px; width: auto;">
                            <h1 style="font-size: 20px; margin: 0; color: #1f2937;">AutoCost</h1>
                        </div>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            {{ now()->format('d.m.Y H:i') }}
                        </p>
                    </div>
                    <div style="border-bottom: 2px solid #3b82f6; padding-bottom: 8px;">
                        <p style="font-size: 16px; font-weight: 600; color: #1f2937; margin: 0;">История операций</p>
                    </div>
                </div>

                <!-- Фильтры -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6 print-hide">
                    <div class="p-4 sm:p-5">
                        <form method="GET" action="{{ route('history.index') }}" class="space-y-4">
                            <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                                <!-- Автомобиль -->
                                <div class="flex-1 min-w-[140px]">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                        Автомобиль
                                    </label>
                                    <div class="relative">
                                        <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm text-sm pl-3 pr-8 py-2.5 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                                            <option value="all" {{ $selectedCarId === 'all' ? 'selected' : '' }}>Все автомобили</option>
                                            @foreach($cars as $car)
                                                <option value="{{ $car->id }}" {{ $selectedCarId == $car->id ? 'selected' : '' }}>
                                                    {{ $car->brand }} {{ $car->model }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Период -->
                                <div class="flex-1 min-w-[140px]">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                        Период
                                    </label>
                                    <div class="relative">
                                        <select name="period" id="period" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm text-sm pl-3 pr-8 py-2.5 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="toggleCustomDate()">
                                            <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Всё время</option>
                                            <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Сегодня</option>
                                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Последняя неделя</option>
                                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Последний месяц</option>
                                            <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Свой период</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Кнопки -->
                                <div class="flex gap-2 items-end">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Применить
                                    </button>

                                    @if($period != 'all' || $selectedCarId != 'all' || $categoryFilter != 'all')
                                        <a href="{{ route('history.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 flex items-center gap-1 transition px-3 py-2.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Сбросить
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Свой период -->
                            <div id="customDateRange" class="flex flex-col sm:flex-row items-start sm:items-center gap-3 pt-2 {{ $period != 'custom' ? 'hidden' : '' }}">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">с</span>
                                    <input type="date" name="date_from" value="{{ $dateFrom }}" 
                                           class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">по</span>
                                    <input type="date" name="date_to" value="{{ $dateTo }}" 
                                           class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Категории -->
                            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mr-1">Категория:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <a href="{{ route('history.index', array_merge(request()->except(['category', 'page']), ['category' => 'all', 'page' => 1])) }}" 
                                       class="px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 {{ $categoryFilter === 'all' ? 'bg-blue-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-[#6B727F] text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                        Все
                                    </a>
                                    @foreach($sortedCategories as $cat)
                                        <a href="{{ route('history.index', array_merge(request()->except(['category', 'page']), ['category' => $cat, 'page' => 1])) }}" 
                                           class="px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 {{ $categoryFilter === $cat ? 'bg-blue-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-[#6B727F] text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                            {{ $cat }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Список операций -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($operations as $operation)
                            @php
                                $routeMap = [
                                    'expense' => 'expenses.show',
                                    'refueling' => 'refuelings.show',
                                    'income' => 'incomes.show',
                                    'service' => 'service.show',
                                ];
                                $routeName = $routeMap[$operation['type']] ?? null;
                                $isIncome = $operation['type'] === 'income';
                                $isService = $operation['type'] === 'service';
                                $isRefueling = $operation['type'] === 'refueling';
                                $isExpense = $operation['type'] === 'expense';
                                
                                $iconMap = [
                                    'expense' => 'images/icons/consumption2.png',
                                    'refueling' => 'images/icons/gas_station2.png',
                                    'service' => 'images/icons/service.png',
                                    'income' => 'images/icons/income.png',
                                ];
                                $icon = $iconMap[$operation['type']] ?? null;
                            @endphp
                            
                            @if($routeName)
                                <a href="{{ route($routeName, $operation['id']) }}" 
                                   class="block p-4 sm:p-5 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition-all duration-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                                            <!-- Иконка -->
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0
                                                {{ $isIncome ? 'bg-green-100 dark:bg-green-900/30' : ($isService ? 'bg-blue-100 dark:bg-blue-900/30' : ($isRefueling ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-red-100 dark:bg-red-900/30')) }}">
                                                @if($icon)
                                                    <img src="{{ asset($icon) }}" alt="{{ $operation['type'] }}" class="w-5 h-5 sm:w-6 sm:h-6">
                                                @else
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            
                                            <!-- Информация -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                    <p class="font-semibold text-gray-800 dark:text-white text-sm sm:text-base">
                                                        {{ $operation['title'] }}
                                                    </p>
                                                    @if($selectedCarId === 'all' && $operation['car_name'])
                                                        <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">
                                                            {{ $operation['car_name'] }}
                                                        </span>
                                                    @endif
                                                    <!-- ТИП ОПЕРАЦИИ НА РУССКОМ -->
                                                    <span class="text-xs text-gray-400 dark:text-gray-500 px-1.5 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700">
                                                        @switch($operation['type'])
                                                            @case('expense') Расход @break
                                                            @case('refueling') Заправка @break
                                                            @case('income') Доход @break
                                                            @case('service') Обслуживание @break
                                                            @default {{ ucfirst($operation['type']) }}
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1 text-xs text-gray-400 dark:text-gray-500">
                                                    <span>{{ \Carbon\Carbon::parse($operation['date'])->format('d.m.Y') }}</span>
                                                    <span>•</span>
                                                    <span>{{ number_format($operation['odometer']) }} {{ $operation['distance_unit'] ?? 'км' }}</span>
                                                    @if($isRefueling && isset($operation['liters']))
                                                        <span>•</span>
                                                        <span>{{ number_format($operation['liters'], 1) }} {{ $operation['volume_unit'] ?? 'л' }}</span>
                                                    @endif
                                                </div>
                                                @if($operation['description'])
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5 line-clamp-2">{{ $operation['description'] }}</p>
                                                @endif
                                                @if($isRefueling && isset($operation['gas_station']) && $operation['gas_station'])
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        {{ $operation['gas_station'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Сумма -->
                                        <div class="text-left sm:text-right pl-13 sm:pl-0">
                                            <p class="text-lg sm:text-xl font-bold {{ $isIncome ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $isIncome ? '+' : '-' }}{{ number_format($operation['amount'], 2) }} {{ $operation['currency'] ?? '₽' }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="p-4 sm:p-5 opacity-60">
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $operation['title'] }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Неизвестный тип записи</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="p-12 text-center">
                                <svg class="w-20 h-20 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">Нет записей</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Добавьте расход, заправку, обслуживание или доход</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- ПАГИНАЦИЯ -->
                    @if($operations->hasPages())
                        <div class="border-t border-gray-100 dark:border-gray-700 px-4 sm:px-6 py-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Показано с {{ $operations->firstItem() }} по {{ $operations->lastItem() }} 
                                    из {{ $operations->total() }} записей
                                </div>
                                
                                <div class="flex items-center justify-center sm:justify-end gap-1">
                                    {{-- Назад --}}
                                    @if($operations->onFirstPage())
                                        <span class="px-3 py-2 rounded-lg text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                            Назад
                                        </span>
                                    @else
                                        <a href="{{ $operations->previousPageUrl() }}" 
                                           class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                            Назад
                                        </a>
                                    @endif
                                    
                                    {{-- Номера страниц --}}
                                    <div class="flex gap-1">
                                        @foreach($operations->getUrlRange(1, $operations->lastPage()) as $page => $url)
                                            @if($page == $operations->currentPage())
                                                <span class="px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-sm">
                                                    {{ $page }}
                                                </span>
                                            @elseif($page == 1 || $page == $operations->lastPage() || ($page >= $operations->currentPage() - 2 && $page <= $operations->currentPage() + 2))
                                                <a href="{{ $url }}" 
                                                   class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                                    {{ $page }}
                                                </a>
                                            @elseif($page == $operations->currentPage() - 3 || $page == $operations->currentPage() + 3)
                                                <span class="px-3 py-2 text-gray-500 dark:text-gray-400">...</span>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    {{-- Вперед --}}
                                    @if($operations->hasMorePages())
                                        <a href="{{ $operations->nextPageUrl() }}" 
                                           class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                            Вперед
                                            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="px-3 py-2 rounded-lg text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                            Вперед
                                            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCustomDate() {
            const period = document.getElementById('period').value;
            const customDateRange = document.getElementById('customDateRange');
            if (period === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        }
    </script>

    <style>
        /* Стили для печати и PDF */
        @media print {
            .print-hide,
            nav, header, footer, 
            .bg-blue-600, .bg-blue-500, button, .no-print,
            #filterForm {
                display: none !important;
            }
            
            body * {
                visibility: hidden;
            }
            #pdf-content, #pdf-content * {
                visibility: visible;
            }
            #pdf-content {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                background: white;
            }
            
            .pdf-header {
                display: block !important;
            }
            
            .hover\:bg-\[\#E5E7EB\]:hover {
                background-color: transparent !important;
            }
            .dark\:hover\:bg-\[\#1D1D1D\]:hover {
                background-color: transparent !important;
            }
            
            .dark .bg-\[\#222222\] {
                background-color: white !important;
            }
            .dark .text-white {
                color: #1f2937 !important;
            }
            .dark .text-gray-300 {
                color: #374151 !important;
            }
            .dark .text-gray-400 {
                color: #6b7280 !important;
            }
            .dark .text-gray-500 {
                color: #6b7280 !important;
            }
            .dark .text-gray-700 {
                color: #374151 !important;
            }
            .dark .border-gray-700 {
                border-color: #e5e7eb !important;
            }
            .dark .divide-gray-700 > * {
                border-color: #e5e7eb !important;
            }
        }
        
        .pdf-header {
            text-align: left;
            margin-bottom: 20px;
        }
        .pdf-header h1 {
            font-size: 20px;
            margin: 0;
            color: #1f2937;
        }
        .pdf-header p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
    </style>
</x-app-layout>