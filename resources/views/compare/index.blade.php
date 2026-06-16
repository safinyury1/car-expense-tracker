<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Сравнение автомобилей') }}
            </h2>
            @if(!$selectedCars->isEmpty())
                <button onclick="window.print()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                    <span>Экспорт PDF</span>
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
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
                        <p style="font-size: 16px; font-weight: 600; color: #1f2937; margin: 0;">Сравнение автомобилей</p>
                    </div>
                </div>

                <!-- Форма выбора автомобилей (скрыта в PDF) -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6 print-hide">
                    <div class="p-4 sm:p-5">
                        <form method="GET" action="{{ route('compare.index') }}" class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                                    Выберите автомобили для сравнения (максимум 4)
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    @foreach($cars as $car)
                                        <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] cursor-pointer transition-all duration-200">
                                            <input type="checkbox" name="cars[]" value="{{ $car->id }}" 
                                                {{ in_array($car->id, $selectedCarIds) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $car->brand }} {{ $car->model }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-center sm:justify-start">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    Сравнить выбранные
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($selectedCars->isEmpty())
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="p-12 text-center">
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Выберите автомобили для сравнения</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Отметьте один или несколько автомобилей выше и нажмите "Сравнить"</p>
                        </div>
                    </div>
                @else
                    <!-- ДЕСКТОПНАЯ ТАБЛИЦА (скрыта на мобильных) -->
                    <div class="hidden md:block bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-200">Сравнение показателей</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-[#1a1a1a]">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Показатель</th>
                                        @foreach($selectedCars as $car)
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $car->brand }} {{ $car->model }}
                                                @if($car->year)
                                                    <span class="block text-xs text-gray-500 dark:text-gray-400 font-normal">{{ $car->year }} г.</span>
                                                @endif
                                            </th>
                                        @endforeach
                                     </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <tr class="hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Общие расходы</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                {{ number_format($comparisonData[$car->id]['totalExpenses'], 2) }} {{ $comparisonData[$car->id]['currency'] }}
                                            </td>
                                        @endforeach
                                     </tr>
                                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Затраты на топливо</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                {{ number_format($comparisonData[$car->id]['totalFuelCost'], 2) }} {{ $comparisonData[$car->id]['currency'] }}
                                            </td>
                                        @endforeach
                                     </tr>
                                    <tr class="hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Средний расход топлива</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                {{ $comparisonData[$car->id]['avgFuelConsumption'] }} {{ $comparisonData[$car->id]['fuel_unit'] }}
                                            </td>
                                        @endforeach
                                     </tr>
                                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Стоимость 1 км</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                {{ number_format($comparisonData[$car->id]['costPerKm'], 2) }} {{ $comparisonData[$car->id]['currency'] }} / {{ $comparisonData[$car->id]['distance_unit'] }}
                                            </td>
                                        @endforeach
                                     </tr>
                                    <tr class="hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Общий пробег</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                {{ number_format($comparisonData[$car->id]['totalDistance']) }} {{ $comparisonData[$car->id]['distance_unit'] }}
                                            </td>
                                        @endforeach
                                     </tr>
                                    <tr class="bg-gray-50 dark:bg-[#1a1a1a] hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Количество расходов</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                {{ $comparisonData[$car->id]['expensesCount'] }}
                                            </td>
                                        @endforeach
                                     </tr>
                                    <tr class="hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Количество заправок</td>
                                        @foreach($selectedCars as $car)
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                {{ $comparisonData[$car->id]['refuelingsCount'] }}
                                            </td>
                                        @endforeach
                                     </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- МОБИЛЬНЫЕ КАРТОЧКИ (видны на экранах < 768px) -->
                    <div class="block md:hidden space-y-4 mb-6">
                        @foreach($selectedCars as $car)
                            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                                <div class="p-4">
                                    <!-- Заголовок карточки -->
                                    <div class="flex justify-between items-start mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">
                                        <div>
                                            <h4 class="font-semibold text-gray-800 dark:text-white">{{ $car->brand }} {{ $car->model }}</h4>
                                            @if($car->year)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $car->year }} г.</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Показатели -->
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-800">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Общие расходы</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($comparisonData[$car->id]['totalExpenses'], 2) }} {{ $comparisonData[$car->id]['currency'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-800">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Затраты на топливо</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($comparisonData[$car->id]['totalFuelCost'], 2) }} {{ $comparisonData[$car->id]['currency'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-800">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Средний расход</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $comparisonData[$car->id]['avgFuelConsumption'] }} {{ $comparisonData[$car->id]['fuel_unit'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-800">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Стоимость 1 км</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($comparisonData[$car->id]['costPerKm'], 2) }} {{ $comparisonData[$car->id]['currency'] }}/{{ $comparisonData[$car->id]['distance_unit'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-800">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Общий пробег</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($comparisonData[$car->id]['totalDistance']) }} {{ $comparisonData[$car->id]['distance_unit'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1.5 border-b border-gray-50 dark:border-gray-800">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Количество расходов</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $comparisonData[$car->id]['expensesCount'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1.5">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Количество заправок</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $comparisonData[$car->id]['refuelingsCount'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- График сравнения расходов -->
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-200">Сравнение расходов</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Общие расходы и затраты на топливо</p>
                        </div>
                        <div class="p-4">
                            <div id="comparisonChart" style="height: 350px;"></div>
                        </div>
                    </div>

                    <!-- Динамика расходов по месяцам -->
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-200">Динамика расходов по месяцам</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Сравнение расходов за последние месяцы</p>
                        </div>
                        <div class="p-4">
                            <div id="trendChart" style="height: 350px;"></div>
                        </div>
                    </div>

                    <!-- Структура расходов -->
                    <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-200">Структура расходов</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Распределение расходов по категориям</p>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($selectedCars as $car)
                                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4">
                                        <h4 class="font-semibold text-center mb-3 text-gray-800 dark:text-gray-200">
                                            {{ $car->brand }} {{ $car->model }}
                                        </h4>
                                        <div id="pieChart_{{ $car->id }}" style="height: 300px;"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Подключаем ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @if(!$selectedCars->isEmpty())
    <script>
        // Сохраняем ссылки на графики
        let charts = [];

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
                height: 350,
                toolbar: { show: false },
                background: 'transparent'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 8
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: comparisonData.map(item => item.name),
                title: { text: 'Автомобиль', style: { fontSize: '12px', fontWeight: 500 } },
                labels: { style: { colors: '#6b7280', fontSize: '12px' } }
            },
            yaxis: {
                title: { text: 'Сумма', style: { fontSize: '12px', fontWeight: 500 } },
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ' + (comparisonData[0]?.currency || '₽');
                    },
                    style: { colors: '#6b7280', fontSize: '11px' }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center'
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
        charts.push(comparisonChart);
        
        // Динамика расходов по месяцам
        const trendData = @json($monthlyTrendData);
        
        const trendChartOptions = {
            series: trendData.series,
            chart: {
                type: 'line',
                height: 350,
                toolbar: { show: false },
                background: 'transparent'
            },
            xaxis: {
                categories: trendData.months,
                title: { text: 'Месяц', style: { fontSize: '12px', fontWeight: 500 } },
                labels: { style: { colors: '#6b7280', fontSize: '12px' } }
            },
            yaxis: {
                title: { text: 'Сумма', style: { fontSize: '12px', fontWeight: 500 } },
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('ru-RU') + ' ' + (trendData.series[0]?.currency || '₽');
                    },
                    style: { colors: '#6b7280', fontSize: '11px' }
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 5,
                hover: { size: 7 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center'
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
        charts.push(trendChart);
        
        // Круговые диаграммы для каждого автомобиля
        const expenseDistribution = @json($expenseDistributionData);
        
        @foreach($selectedCars as $car)
            const pieData{{ $car->id }} = expenseDistribution[{{ $car->id }}] || [];
            const pieChartOptions{{ $car->id }} = {
                series: pieData{{ $car->id }}.map(item => item.amount),
                chart: { 
                    type: 'donut', 
                    height: 300,
                    background: 'transparent'
                },
                labels: pieData{{ $car->id }}.map(item => item.name),
                responsive: [{
                    breakpoint: 480,
                    options: { chart: { width: 280 }, legend: { position: 'bottom' } }
                }],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '55%'
                        }
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
            const pieChart{{ $car->id }} = new ApexCharts(document.querySelector("#pieChart_{{ $car->id }}"), pieChartOptions{{ $car->id }});
            pieChart{{ $car->id }}.render();
            charts.push(pieChart{{ $car->id }});
        @endforeach

        // Перерисовываем графики перед печатью
        window.addEventListener('beforeprint', function() {
            setTimeout(function() {
                charts.forEach(chart => {
                    chart.render();
                });
            }, 100);
        });
    </script>
    @endif

    <style>
        /* Стили для печати и PDF */
        @media print {
            .print-hide,
            nav, header, footer, 
            .bg-blue-600, .bg-blue-500, button, .no-print {
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
            
            #comparisonChart, #trendChart, [id^="pieChart_"] {
                visibility: visible !important;
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
        
        @media print {
            .apexcharts-toolbar {
                display: none !important;
            }
        }
    </style>
</x-app-layout>