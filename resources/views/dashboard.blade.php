<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Статистика') }}
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
                        <p style="font-size: 16px; font-weight: 600; color: #1f2937; margin: 0;">Статистика</p>
                    </div>
                </div>

                <!-- Фильтры (видны только на странице, скрыты в PDF) -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6 print-hide">
                    <div class="p-4 sm:p-5">
                        <form method="GET" action="{{ route('dashboard') }}" class="space-y-4" id="filterForm">
                            <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                                <div class="flex-1 min-w-[140px]">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                        Автомобиль
                                    </label>
                                    <div class="relative">
                                        <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm text-sm pl-3 pr-8 py-2.5 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                                            <option value="all" {{ $selectedCarId === 'all' ? 'selected' : '' }}>
                                                Все автомобили
                                            </option>
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

                                <div class="flex gap-2 items-end">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Применить
                                    </button>

                                    @if($period != 'all' || $selectedCarId != 'all')
                                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 flex items-center gap-1 transition px-3 py-2.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Сбросить
                                        </a>
                                    @endif
                                </div>
                            </div>

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
                        </form>
                    </div>
                </div>
                
                <!-- Карточки с показателями -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6">
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Общие расходы</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-800 dark:text-white truncate">{{ number_format($data['totalExpenses'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                    </div>
                    
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Затраты на топливо</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-800 dark:text-white truncate">{{ number_format($data['totalFuelCost'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                    </div>
                    
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Средний расход топлива</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-800 dark:text-white">{{ $data['avgFuelConsumption'] }} <span class="text-xs sm:text-sm font-normal">{{ $data['fuel_unit'] ?? 'л/100 км' }}</span></p>
                    </div>
                    
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Стоимость 1 км</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($data['costPerKm'], 2) }} {{ $data['currency'] ?? '₽' }}<span class="text-xs sm:text-sm font-normal"> /{{ $data['distance_unit'] ?? 'км' }}</span></p>
                    </div>
                </div>

                <!-- Графики -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Структура расходов</h3>
                        </div>
                        <div class="p-4">
                            <canvas id="expensesChart" height="280"></canvas>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Динамика расходов по месяцам</h3>
                        </div>
                        <div class="p-4">
                            <canvas id="trendChart" height="280"></canvas>
                        </div>
                    </div>
                </div>

                <!-- График расхода топлива -->
                @if(count($fuelHistory) >= 2)
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300">История расхода топлива (л/100 км)</h3>
                    </div>
                    <div class="p-4">
                        <canvas id="fuelChart" height="280"></canvas>
                    </div>
                </div>
                @endif

                <!-- ИНСАЙТЫ -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <img src="{{ asset('images/icons/schedule.png') }}" alt="Расходы" class="w-5 h-5">
                                Расходы в день
                            </h3>
                        </div>
                        <div class="p-4">
                            <p class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($insights['dailyAverage'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">в среднем за день</p>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                           <h3 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <img src="{{ asset('images/icons/consumption3.png') }}" alt="Средняя трата" class="w-5 h-5">
                                Средняя трата
                            </h3>
                        </div>
                        <div class="p-4">
                            <p class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($insights['averageExpense'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">за одну операцию</p>
                        </div>
                    </div>
                </div>

                <!-- ТОП-3 расходов -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300">Топ-3 самых больших расходов</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($topExpenses as $index => $expense)
                            <div class="p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm 
                                        {{ $index == 0 ? 'bg-yellow-500' : ($index == 1 ? 'bg-gray-400' : 'bg-orange-500') }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm sm:text-base text-gray-800 dark:text-white">{{ $expense['title'] }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $expense['date'] }} • {{ number_format($expense['odometer']) }} {{ $expense['distance_unit'] ?? 'км' }}</p>
                                        @if($selectedCarId === 'all' && isset($expense['car']))
                                            <p class="text-xs text-blue-500 mt-0.5">{{ $expense['car'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-base sm:text-xl font-bold text-red-600 dark:text-red-400">{{ number_format($expense['amount'], 2) }} {{ $expense['currency'] ?? '₽' }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $expense['category'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400 dark:text-gray-500">
                                Нет данных о расходах
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Структура расходов
        const ctx1 = document.getElementById('expensesChart').getContext('2d');
        let expensesChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: @json($chartData['categories']),
                datasets: [{
                    data: @json($chartData['amounts']),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw.toLocaleString('ru-RU') + ' ₽';
                            }
                        }
                    }
                }
            }
        });
        
        // Динамика расходов
        const ctx2 = document.getElementById('trendChart').getContext('2d');
        let trendChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: @json($monthlyData['months']),
                datasets: [{
                    label: 'Расходы',
                    data: @json($monthlyData['totals']),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString('ru-RU') + ' ₽';
                            }
                        }
                    },
                    legend: { labels: { font: { size: 11 } } }
                },
                scales: {
                    y: { 
                        ticks: { 
                            callback: function(value) { 
                                return value.toLocaleString('ru-RU') + ' ₽';
                            },
                            font: { size: 10 }
                        },
                        title: { display: false }
                    },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
        
        // Расход топлива
        @if(count($fuelHistory) >= 2)
        const ctx3 = document.getElementById('fuelChart').getContext('2d');
        let fuelChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: @json(array_column($fuelHistory, 'date')),
                datasets: [{
                    label: 'Расход топлива',
                    data: @json(array_column($fuelHistory, 'consumption')),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + ' {{ $data["fuel_unit"] ?? "л/100 км" }}';
                            }
                        }
                    },
                    legend: { labels: { font: { size: 11 } } }
                },
                scales: {
                    y: { 
                        title: { display: true, text: '{{ $data["fuel_unit"] ?? "л/100 км" }}', font: { size: 10 } },
                        ticks: { font: { size: 10 } }
                    },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
        @endif
        
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
            
            canvas {
                max-width: 100% !important;
                height: auto !important;
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
            
            .dark canvas {
                filter: none !important;
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