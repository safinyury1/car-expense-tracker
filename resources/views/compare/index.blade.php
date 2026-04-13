<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Сравнение автомобилей') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Форма выбора автомобилей для сравнения -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <form method="GET" action="{{ route('compare.index') }}" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Выберите автомобили для сравнения (максимум 4)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @foreach($cars as $car)
                                        <label class="flex items-center space-x-2 p-2 border rounded-lg hover:bg-gray-100 cursor-pointer">
                                            <input type="checkbox" name="cars[]" value="{{ $car->id }}" 
                                                {{ in_array($car->id, $selectedCarIds) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-gray-700">{{ $car->brand }} {{ $car->model }}</span>
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
                        <div class="text-center py-12 text-gray-500">
                            <p class="text-lg">Выберите автомобили для сравнения</p>
                            <p class="text-sm mt-2">Отметьте один или несколько автомобилей выше и нажмите "Сравнить"</p>
                        </div>
                    @else
                        <!-- Сравнительная таблица -->
                        <div class="overflow-x-auto mb-8">
                            <table class="min-w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-3 text-left border">Показатель</th>
                                        @foreach($selectedCars as $car)
                                            <th class="px-4 py-3 text-center border">
                                                <div class="font-bold">{{ $car->brand }} {{ $car->model }}</div>
                                                @if($car->year)
                                                    <div class="text-sm text-gray-500">{{ $car->year }} г.</div>
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-medium border">Общие расходы</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border">
                                                {{ number_format($comparisonData[$car->id]['totalExpenses'], 2) }} ₽
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b bg-gray-50">
                                        <td class="px-4 py-3 font-medium border">Затраты на топливо</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border">
                                                {{ number_format($comparisonData[$car->id]['totalFuelCost'], 2) }} ₽
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-medium border">Средний расход топлива</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border">
                                                {{ $comparisonData[$car->id]['avgFuelConsumption'] }} л/100 км
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b bg-gray-50">
                                        <td class="px-4 py-3 font-medium border">Стоимость 1 км</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border">
                                                {{ number_format($comparisonData[$car->id]['costPerKm'], 2) }} ₽
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-medium border">Общий пробег</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border">
                                                {{ number_format($comparisonData[$car->id]['totalDistance']) }} км
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b bg-gray-50">
                                        <td class="px-4 py-3 font-medium border">Количество расходов</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border">
                                                {{ $comparisonData[$car->id]['expensesCount'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-medium border">Количество заправок</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-center border">
                                                {{ $comparisonData[$car->id]['refuelingsCount'] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- График сравнения расходов (столбчатая диаграмма) -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Сравнение расходов</h3>
                            <div class="bg-white p-4 rounded-lg border">
                                <div id="comparisonChart" style="height: 400px;"></div>
                            </div>
                        </div>

                        <!-- Динамика расходов по месяцам -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Динамика расходов по месяцам</h3>
                            <div class="bg-white p-4 rounded-lg border">
                                <div id="trendChart" style="height: 400px;"></div>
                            </div>
                        </div>

                        <!-- Круговые диаграммы для каждого автомобиля -->
                        <div>
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Структура расходов</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($selectedCars as $car)
                                    <div class="border rounded-lg p-4">
                                        <h4 class="font-medium text-center mb-2">{{ $car->brand }} {{ $car->model }}</h4>
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
                toolbar: { show: true }
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
                title: { text: 'Автомобиль' }
            },
            yaxis: {
                title: { text: 'Сумма (₽)' },
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ₽';
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ₽';
                    }
                }
            }
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
                toolbar: { show: true }
            },
            xaxis: {
                categories: trendData.months,
                title: { text: 'Месяц' }
            },
            yaxis: {
                title: { text: 'Сумма (₽)' },
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ₽';
                    }
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
                        return value.toLocaleString('ru-RU') + ' ₽';
                    }
                }
            }
        };
        
        const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendChartOptions);
        trendChart.render();
        
        // Круговые диаграммы для каждого автомобиля
        const expenseDistribution = @json($expenseDistributionData);
        
        @foreach($selectedCars as $car)
            const pieData{{ $car->id }} = expenseDistribution[{{ $car->id }}] || [];
            const pieChartOptions{{ $car->id }} = {
                series: pieData{{ $car->id }}.map(item => item.amount),
                chart: { type: 'donut', height: 350 },
                labels: pieData{{ $car->id }}.map(item => item.name),
                responsive: [{
                    breakpoint: 480,
                    options: { chart: { width: 300 }, legend: { position: 'bottom' } }
                }],
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString('ru-RU') + ' ₽';
                        }
                    }
                }
            };
            const pieChart{{ $car->id }} = new ApexCharts(document.querySelector("#pieChart_{{ $car->id }}"), pieChartOptions{{ $car->id }});
            pieChart{{ $car->id }}.render();
        @endforeach
    </script>
    @endif
</x-app-layout>