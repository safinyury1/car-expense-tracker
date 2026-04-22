<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('История') }}
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

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Фильтры -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('history.index') }}" class="space-y-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2">
                                <label class="font-medium text-gray-700 dark:text-gray-300">Фильтр авто:</label>
                                <select name="car_id" class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm text-sm" onchange="this.form.submit()">
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
                            
                            @if($period != 'all' || $selectedCarId != 'all' || $categoryFilter != 'all')
                                <a href="{{ route('history.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Сбросить</a>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-2">
                            <label class="font-medium text-gray-700 dark:text-gray-300">Категория:</label>
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('history.index', array_merge(request()->all(), ['category' => 'all'])) }}" 
                                   class="px-3 py-1 rounded-full text-sm {{ $categoryFilter === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-[#6B727F] text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    Все
                                </a>
                                @foreach($sortedCategories as $cat)
                                    <a href="{{ route('history.index', array_merge(request()->all(), ['category' => $cat])) }}" 
                                       class="px-3 py-1 rounded-full text-sm {{ $categoryFilter === $cat ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-[#6B727F] text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                        {{ $cat }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Список операций (кликабельный) -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden">
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
                        @endphp
                        @if($routeName)
                            <a href="{{ route($routeName, $operation['id']) }}" 
                               class="block p-4 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $operation['type'] === 'income' ? 'bg-green-100 dark:bg-green-900/50' : ($operation['type'] === 'service' ? 'bg-blue-100 dark:bg-blue-900/50' : 'bg-red-100 dark:bg-red-900/50') }}">
                                            @if($operation['type'] === 'expense')
                                                <img src="{{ asset('images/icons/consumption2.png') }}" alt="Расход" class="w-5 h-5">
                                            @elseif($operation['type'] === 'refueling')
                                                <img src="{{ asset('images/icons/gas_station2.png') }}" alt="Заправка" class="w-5 h-5">
                                            @elseif($operation['type'] === 'service')
                                                <img src="{{ asset('images/icons/service.png') }}" alt="Обслуживание" class="w-5 h-5">
                                            @else
                                                <img src="{{ asset('images/icons/income.png') }}" alt="Доход" class="w-5 h-5">
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium text-gray-800 dark:text-white">{{ $operation['title'] }}</p>
                                                @if($selectedCarId === 'all' && $operation['car_name'])
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">• {{ $operation['car_name'] }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($operation['date'])->format('d.m.Y') }}</span>
                                                <span class="text-xs text-gray-300 dark:text-gray-600">•</span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($operation['odometer']) }} {{ $operation['distance_unit'] }}</span>
                                                @if($operation['type'] === 'refueling' && isset($operation['liters']))
                                                    <span class="text-xs text-gray-300 dark:text-gray-600">•</span>
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $operation['liters'] }} {{ $operation['volume_unit'] }}</span>
                                                @endif
                                            </div>
                                            @if($operation['description'])
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $operation['description'] }}</p>
                                            @endif
                                            @if($operation['type'] === 'refueling' && isset($operation['gas_station']) && $operation['gas_station'])
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">📍 {{ $operation['gas_station'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-lg font-bold {{ $operation['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            @if($operation['type'] === 'income')
                                                +{{ number_format($operation['amount'], 2) }} {{ $operation['currency'] }}
                                            @else
                                                -{{ number_format($operation['amount'], 2) }} {{ $operation['currency'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center">
                                            <span class="text-red-500 text-sm">⚠️</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $operation['title'] }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Неизвестный тип записи</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="p-8 text-center text-gray-400 dark:text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>Нет записей</p>
                            <p class="text-sm mt-1">Добавьте расход, заправку, обслуживание или доход</p>
                        </div>
                    @endforelse
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
</x-app-layout>