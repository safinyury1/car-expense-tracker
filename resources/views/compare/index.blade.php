<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Сравнение автомобилей') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Форма выбора автомобилей для сравнения -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <form method="GET" action="{{ route('compare.index') }}" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Выберите автомобили для сравнения (максимум 4)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @foreach($cars as $car)
                                        <label class="flex items-center space-x-2 p-2 border dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                            <input type="checkbox" name="cars[]" value="{{ $car->id }}" 
                                                {{ in_array($car->id, $selectedCarIds) ? 'checked' : '' }}
                                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                                            <span class="text-gray-700 dark:text-gray-300">{{ $car->brand }} {{ $car->model }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                    Сравнить выбранные
                                </button>
                            </div>
                        </form>
                    </div>

                    @if($selectedCars->isEmpty())
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <p class="text-lg">Выберите автомобили для сравнения</p>
                            <p class="text-sm mt-2">Отметьте один или несколько автомобилей выше и нажмите "Сравнить"</p>
                        </div>
                    @else
                        <!-- Сравнительная таблица -->
                        <div class="overflow-x-auto mb-8">
                            <table class="min-w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-4 py-3 text-left border dark:border-gray-600">Показатель</th>
                                        @foreach($selectedCars as $car)
                                            <th class="px-4 py-3 text-center border dark:border-gray-600">
                                                <div class="font-bold text-gray-800 dark:text-gray-200">{{ $car->brand }} {{ $car->model }}</div>
                                                @if($car->year)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $car->year }} г.</div>
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-3 font-medium border dark:border-gray-600">Общие расходы</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border dark:border-gray-600">
                                                {{ number_format($comparisonData[$car->id]['totalExpenses'], 2) }} {{ $comparisonData[$car->id]['currency'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                        <td class="px-4 py-3 font-medium border dark:border-gray-600">Затраты на топливо</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border dark:border-gray-600">
                                                {{ number_format($comparisonData[$car->id]['totalFuelCost'], 2) }} {{ $comparisonData[$car->id]['currency'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-3 font-medium border dark:border-gray-600">Средний расход топлива</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border dark:border-gray-600">
                                                {{ $comparisonData[$car->id]['avgFuelConsumption'] }} {{ $comparisonData[$car->id]['fuel_unit'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                        <td class="px-4 py-3 font-medium border dark:border-gray-600">Стоимость 1 км</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border dark:border-gray-600">
                                                {{ number_format($comparisonData[$car->id]['costPerKm'], 2) }} {{ $comparisonData[$car->id]['currency'] }} / {{ $comparisonData[$car->id]['distance_unit'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-3 font-medium border dark:border-gray-600">Общий пробег</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border dark:border-gray-600">
                                                {{ number_format($comparisonData[$car->id]['totalDistance']) }} {{ $comparisonData[$car->id]['distance_unit'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                        <td class="px-4 py-3 font-medium border dark:border-gray-600">Количество расходов</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border dark:border-gray-600">
                                                {{ $comparisonData[$car->id]['expensesCount'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-3 font-medium border dark:border-gray-600">Количество заправок</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border dark:border-gray-600">
                                                {{ $comparisonData[$car->id]['refuelingsCount'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- График сравнения расходов (столбчатая диаграмма) -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Сравнение расходов</h3>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700">
                                <div id="comparisonChart" style="height: 400px;"></div>
                            </div>
                        </div>

                        <!-- Динамика расходов по месяцам -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Динамика расходов по месяцам</h3>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700">
                                <div id="trendChart" style="height: 400px;"></div>
                            </div>
                        </div>

                        <!-- Круговые диаграммы для каждого автомобиля -->
                        <div>
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Структура расходов</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($selectedCars as $car)
                                    <div class="border dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                                        <h4 class="font-medium text-center mb-2 text-gray-800 dark:text-gray-200">{{ $car->brand }} {{ $car->model }}</h4>
                                        <div id="pieChart_{{ $car->id }}" style="height: 350px;"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Подключаем ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @if(!$selectedCars->isEmpty())
    <script>
        // График сравнения расходов (столбчатая диаграмма)
        const comparisonData = @json($chartData);
        
        const comparisonChartOptions = {
            series: [
                {
                    name: 'Все расходы',
                    data: comparisonData.map(item => item.expenses),
                    color: '#3b82f6'
                },
                {
                    name: 'Из них топливо',
                    data: comparisonData.map(item => item.fuel),
                    color: '#10b981'
                }
            ],
            chart: {
                type: 'bar',
                height: 400,
                toolbar: { show: true },
                background: 'transparent'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: comparisonData.map(item => item.name),
                title: { text: 'Автомобиль' },
                labels: { style: { colors: '#6b7280' } }
            },
            yaxis: {
                title: { text: 'Сумма' },
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ' + (comparisonData[0]?.currency || '₽');
                    },
                    style: { colors: '#6b7280' }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ' + (comparisonData[0]?.currency || '₽');
                    }
                }
            },
            theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
        };
        
        const comparisonChart = new ApexCharts(document.querySelector("#comparisonChart"), comparisonChartOptions);
        comparisonChart.render();
        
        // Динамика расходов по месяцам
        const trendData = @json($monthlyTrendData);
        
        const trendChartOptions = {
            series: trendData.series,
            chart: {
                type: 'line',
                height: 400,
                toolbar: { show: true },
                background: 'transparent'
            },
            xaxis: {
                categories: trendData.months,
                title: { text: 'Месяц' },
                labels: { style: { colors: '#6b7280' } }
            },
            yaxis: {
                title: { text: 'Сумма' },
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ' + (trendData.series[0]?.currency || '₽');
                    },
                    style: { colors: '#6b7280' }
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 5
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ' + (trendData.series[0]?.currency || '₽');
                    }
                }
            },
            theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
        };
        
        const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendChartOptions);
        trendChart.render();
        
        // Круговые диаграммы для каждого автомобиля
        const expenseDistribution = @json($expenseDistributionData);
        
        @foreach($selectedCars as $car)
            const pieData{{ $car->id }} = expenseDistribution[{{ $car->id }}] || [];
            const pieChartOptions{{ $car->id }} = {
                series: pieData{{ $car->id }}.map(item => item.amount),
                chart: { 
                    type: 'donut', 
                    height: 350,
                    background: 'transparent'
                },
                labels: pieData{{ $car->id }}.map(item => item.name),
                responsive: [{
                    breakpoint: 480,
                    options: { chart: { width: 300 }, legend: { position: 'bottom' } }
                }],
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString('ru-RU') + ' ' + (comparisonData[0]?.currency || '₽');
                        }
                    }
                },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
            };
            const pieChart{{ $car->id }} = new ApexCharts(document.querySelector("#pieChart_{{ $car->id }}"), pieChartOptions{{ $car->id }});
            pieChart{{ $car->id }}.render();
        @endforeach
    </script>
    @endif
</x-app-layout>