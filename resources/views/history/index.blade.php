<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('history.index') }}" class="space-y-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2">
                                <label class="font-medium text-gray-700">Фильтр авто:</label>
                                <select name="car_id" class="border-gray-300 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
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
                                <label class="font-medium text-gray-700">📅 Период:</label>
                                <select name="period" id="period" class="border-gray-300 rounded-md shadow-sm" onchange="toggleCustomDate()">
                                    <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Всё время</option>
                                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Сегодня</option>
                                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Последняя неделя</option>
                                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Последний месяц</option>
                                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Свой период</option>
                                </select>
                            </div>
                            
                            <div id="customDateRange" class="flex items-center gap-2 {{ $period != 'custom' ? 'hidden' : '' }}">
                                <input type="date" name="date_from" value="{{ $dateFrom }}" class="border-gray-300 rounded-md shadow-sm" placeholder="Дата от">
                                <span class="text-gray-500">—</span>
                                <input type="date" name="date_to" value="{{ $dateTo }}" class="border-gray-300 rounded-md shadow-sm" placeholder="Дата до">
                            </div>
                            
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition shadow-sm">
                                Применить
                            </button>
                            
                            @if($period != 'all' || $selectedCarId != 'all' || $categoryFilter != 'all')
                                <a href="{{ route('history.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Сбросить</a>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-2">
                            <label class="font-medium text-gray-700">Категория:</label>
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('history.index', array_merge(request()->all(), ['category' => 'all'])) }}" 
                                   class="px-3 py-1 rounded-full text-sm {{ $categoryFilter === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Все
                                </a>
                                @foreach($sortedCategories as $cat)
                                    <a href="{{ route('history.index', array_merge(request()->all(), ['category' => $cat])) }}" 
                                       class="px-3 py-1 rounded-full text-sm {{ $categoryFilter === $cat ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        {{ $cat }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Список операций -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-100">
                    @forelse($operations as $operation)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <!-- Иконка в зависимости от типа -->
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $operation['type'] === 'expense' ? 'bg-red-100' : 'bg-green-100' }}">
                                        @if($operation['type'] === 'expense')
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium text-gray-800">{{ $operation['title'] }}</p>
                                            @if($selectedCarId === 'all' && $operation['car_name'])
                                                <span class="text-xs text-gray-400">• {{ $operation['car_name'] }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($operation['date'])->format('d.m.Y') }}</span>
                                            <span class="text-xs text-gray-300">•</span>
                                            <span class="text-xs text-gray-400">{{ number_format($operation['odometer']) }} км</span>
                                            @if($operation['type'] === 'refueling' && $operation['liters'])
                                                <span class="text-xs text-gray-300">•</span>
                                                <span class="text-xs text-gray-400">{{ $operation['liters'] }} л</span>
                                            @endif
                                        </div>
                                        @if($operation['description'])
                                            <p class="text-sm text-gray-500 mt-1">{{ $operation['description'] }}</p>
                                        @endif
                                        @if($operation['type'] === 'refueling' && $operation['gas_station'])
                                            <p class="text-xs text-gray-400 mt-1">📍 {{ $operation['gas_station'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-lg font-bold {{ $operation['type'] === 'expense' ? 'text-red-600' : 'text-green-600' }}">
                                        -{{ number_format($operation['amount'], 2) }} ₽
                                    </p>
                                    <form action="{{ route('history.destroy', ['type' => $operation['type'], 'id' => $operation['id']]) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Вы уверены, что хотите удалить эту запись?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition mt-1">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>Нет записей</p>
                            <p class="text-sm mt-1">Добавьте расход или заправку</p>
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