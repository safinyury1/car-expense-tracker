<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Дашборд') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Выбор автомобиля -->
            @if($cars->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-4">
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-4">
                            <label class="font-medium text-gray-700">Автомобиль:</label>
                            <select name="car_id" class="border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $selectedCarId == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            @endif

            @if(!$selectedCar)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        <p class="mb-4">У вас пока нет добавленных автомобилей.</p>
                        <a href="{{ route('cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Добавить автомобиль
                        </a>
                    </div>
                </div>
            @else
                <!-- Карточки с ключевыми показателями -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Общие расходы</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalExpenses, 2) }} ₽</p>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Затраты на топливо</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalFuelCost, 2) }} ₽</p>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Средний расход топлива</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $avgFuelConsumption }} л/100 км</p>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Стоимость 1 км</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($costPerKm, 2) }} ₽</p>
                        </div>
                    </div>
                </div>

                <!-- Графики -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Круговая диаграмма: структура расходов -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-gray-700">Структура расходов</h3>
                        </div>
                        <div class="p-4" id="expensesChart"></div>
                    </div>
                    
                    <!-- Линейная диаграмма: динамика расходов -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-gray-700">Динамика расходов по месяцам</h3>
                        </div>
                        <div class="p-4" id="trendChart"></div>
                    </div>
                </div>

                <!-- График расхода топлива -->
                @if(count($fuelConsumptionHistory) >= 2)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-gray-700">История расхода топлива (л/100 км)</h3>
                        </div>
                        <div class="p-4" id="fuelChart"></div>
                    </div>
                @endif

                <!-- Последние операции -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Последние расходы -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-gray-700">Последние расходы</h3>
                        </div>
                        <div class="p-4">
                            @if($recentExpenses->isEmpty())
                                <p class="text-gray-500 text-center">Нет данных</p>
                            @else
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="text-left text-sm text-gray-500">
                                            <th class="pb-2">Дата</th>
                                            <th class="pb-2">Категория</th>
                                            <th class="pb-2">Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentExpenses as $expense)
                                            <tr class="border-t">
                                                <td class="py-2 text-sm">{{ $expense->date->format('d.m.Y') }}</td>
                                                <td class="py-2 text-sm">{{ $expense->category->name }}</td>
                                                <td class="py-2 text-sm font-medium">{{ number_format($expense->amount, 2) }} ₽</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 text-right">
                                    <a href="{{ route('expenses.index', ['car_id' => $selectedCarId]) }}" class="text-sm text-blue-600 hover:text-blue-800">Все расходы →</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Последние заправки -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-gray-700">Последние заправки</h3>
                        </div>
                        <div class="p-4">
                            @if($recentRefuelings->isEmpty())
                                <p class="text-gray-500 text-center">Нет данных</p>
                            @else
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="text-left text-sm text-gray-500">
                                            <th class="pb-2">Дата</th>
                                            <th class="pb-2">Литры</th>
                                            <th class="pb-2">Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentRefuelings as $refueling)
                                            <tr class="border-t">
                                                <td class="py-2 text-sm">{{ $refueling->date->format('d.m.Y') }}</td>
                                                <td class="py-2 text-sm">{{ number_format($refueling->liters, 2) }} л</td>
                                                <td class="py-2 text-sm font-medium">{{ number_format($refueling->total_amount, 2) }} ₽</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 text-right">
                                    <a href="{{ route('refuelings.index', ['car_id' => $selectedCarId]) }}" class="text-sm text-blue-600 hover:text-blue-800">Все заправки →</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Подключаем ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @if($selectedCar)
    <script>
        // Структура расходов (круговая диаграмма)
        const expensesData = {{ Illuminate\Support\Js::from($expensesByCategory) }};
        const expensesChartOptions = {
            series: expensesData.map(function(item) { return item.amount; }),
            chart: { type: 'donut', height: 350 },
            labels: expensesData.map(function(item) { return item.name; }),
            responsive: [{
                breakpoint: 480,
                options: { chart: { width: 200 }, legend: { position: 'bottom' } }
            }]
        };
        const expensesChart = new ApexCharts(document.querySelector("#expensesChart"), expensesChartOptions);
        expensesChart.render();
        
        // Динамика расходов (линейная диаграмма)
        const trendData = {{ Illuminate\Support\Js::from($expensesByMonth) }};
        const trendChartOptions = {
            series: [{
                name: 'Расходы',
                data: trendData.map(function(item) { return item.total; })
            }],
            chart: { type: 'line', height: 350, toolbar: { show: true } },
            xaxis: { categories: trendData.map(function(item) { return item.month; }), title: { text: 'Месяц' } },
            yaxis: { title: { text: 'Сумма (₽)' } },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#3b82f6']
        };
        const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendChartOptions);
        trendChart.render();
        
        // Расход топлива
        @if(count($fuelConsumptionHistory) >= 2)
        const fuelData = {{ Illuminate\Support\Js::from($fuelConsumptionHistory) }};
        const fuelChartOptions = {
            series: [{
                name: 'Расход топлива',
                data: fuelData.map(function(item) { return item.consumption; })
            }],
            chart: { type: 'line', height: 350, toolbar: { show: true } },
            xaxis: { categories: fuelData.map(function(item) { return item.date; }), title: { text: 'Дата' } },
            yaxis: { title: { text: 'л/100 км' }, min: 0 },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#10b981']
        };
        const fuelChart = new ApexCharts(document.querySelector("#fuelChart"), fuelChartOptions);
        fuelChart.render();
        @endif
    </script>
    @endif
</x-app-layout>