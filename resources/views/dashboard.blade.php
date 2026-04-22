<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Статистика') }}
            </h2>
            <button onclick="window.print()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Экспорт PDF
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Выбор автомобиля -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="font-medium text-gray-700 dark:text-gray-300">Автомобиль:</label>
                            <select name="car_id" class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" onchange="this.form.submit()">
                                <option value="all" {{ $selectedCarId === 'all' ? 'selected' : '' }}>
                                    Все автомобили
                                </option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $selectedCarId == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <label class="font-medium text-gray-700 dark:text-gray-300">Период:</label>
                            <select name="period" id="period" class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" onchange="toggleCustomDate()">
                                <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Всё время</option>
                                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Сегодня</option>
                                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Последняя неделя</option>
                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Последний месяц</option>
                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Свой период</option>
                            </select>
                        </div>
                        
                        <div id="customDateRange" class="flex items-center gap-2 {{ $period != 'custom' ? 'hidden' : '' }}">
                            <input type="date" name="date_from" value="{{ $dateFrom }}" class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                            <span class="text-gray-500 dark:text-gray-400">—</span>
                            <input type="date" name="date_to" value="{{ $dateTo }}" class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                        </div>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition shadow-sm">
                            Применить
                        </button>
                        
                        @if($period != 'all' || $selectedCarId != 'all')
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Сбросить</a>
                        @endif
                    </form>
                </div>
            </div>
            
            <!-- Карточки с показателями -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Общие расходы</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($data['totalExpenses'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Затраты на топливо</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($data['totalFuelCost'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Средний расход топлива</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $data['avgFuelConsumption'] }} <span class="text-sm font-normal">{{ $data['fuel_unit'] ?? 'л/100 км' }}</span></p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Стоимость 1 км</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($data['costPerKm'], 2) }} {{ $data['currency'] ?? '₽' }} / {{ $data['distance_unit'] ?? 'км' }}</p>
                    </div>
                </div>
            </div>

            <!-- Графики -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300">Структура расходов</h3>
                    </div>
                    <div class="p-4">
                        <canvas id="expensesChart" height="300"></canvas>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300">Динамика расходов по месяцам</h3>
                    </div>
                    <div class="p-4">
                        <canvas id="trendChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- График расхода топлива -->
            @if(count($fuelHistory) >= 2)
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300">История расхода топлива</h3>
                </div>
                <div class="p-4">
                    <canvas id="fuelChart" height="300"></canvas>
                </div>
            </div>
            @endif

            <!-- ИНСАЙТЫ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <img src="{{ asset('images/icons/schedule.png') }}" alt="Расходы" class="w-5 h-5">
                            Расходы в день
                        </h3>
                    </div>
                    <div class="p-4">
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($insights['dailyAverage'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">в среднем за день</p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                       <h3 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <img src="{{ asset('images/icons/consumption3.png') }}" alt="Средняя трата" class="w-5 h-5">
                            Средняя трата
                        </h3>
                    </div>
                    <div class="p-4">
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($insights['averageExpense'], 2) }} {{ $data['currency'] ?? '₽' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">за одну операцию</p>
                    </div>
                </div>
            </div>

            <!-- ТОП-3 расходов -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300">Топ-3 самых больших расходов</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($topExpenses as $index => $expense)
                        <div class="p-4 flex items-center justify-between hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm 
                                    {{ $index == 0 ? 'bg-yellow-500' : ($index == 1 ? 'bg-gray-400' : 'bg-orange-500') }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $expense['title'] }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $expense['date'] }} • {{ number_format($expense['odometer']) }} {{ $expense['distance_unit'] ?? 'км' }}</p>
                                    @if($selectedCarId === 'all' && isset($expense['car']))
                                        <p class="text-xs text-blue-500 mt-1">{{ $expense['car'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ number_format($expense['amount'], 2) }} {{ $expense['currency'] ?? '₽' }}</p>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Структура расходов
        const ctx1 = document.getElementById('expensesChart').getContext('2d');
        new Chart(ctx1, {
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
                    legend: { position: 'bottom' },
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
        new Chart(ctx2, {
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
                    pointRadius: 5,
                    pointHoverRadius: 7
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
                    }
                },
                scales: {
                    y: { 
                        ticks: { 
                            callback: function(value) { 
                                return value.toLocaleString('ru-RU') + ' ₽'; 
                            }
                        }
                    }
                }
            }
        });
        
        // Расход топлива
        @if(count($fuelHistory) >= 2)
        const ctx3 = document.getElementById('fuelChart').getContext('2d');
        new Chart(ctx3, {
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
                    pointRadius: 5,
                    pointHoverRadius: 7
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
                    }
                },
                scales: {
                    y: { title: { display: true, text: '{{ $data["fuel_unit"] ?? "л/100 км" }}' } }
                }
            }
        });
        @endif
    </script>
    
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
</x-app-layout>