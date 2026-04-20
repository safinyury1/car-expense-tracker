<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Заправки') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <form method="GET" action="{{ route('refuelings.index') }}" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Поиск</label>
                                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="АЗС, автомобиль..." class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Автомобиль</label>
                                    <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm">
                                        <option value="">Все автомобили</option>
                                        @foreach($cars as $car)
                                            <option value="{{ $car->id }}" {{ ($carId ?? '') == $car->id ? 'selected' : '' }}>
                                                {{ $car->brand }} {{ $car->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сортировка</label>
                                    <select name="sort_by" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm">
                                        <option value="date" {{ ($sortBy ?? '') == 'date' ? 'selected' : '' }}>По дате</option>
                                        <option value="liters" {{ ($sortBy ?? '') == 'liters' ? 'selected' : '' }}>По литрам</option>
                                        <option value="total_amount" {{ ($sortBy ?? '') == 'total_amount' ? 'selected' : '' }}>По сумме</option>
                                        <option value="odometer" {{ ($sortBy ?? '') == 'odometer' ? 'selected' : '' }}>По пробегу</option>
                                    </select>
                                </div>
                                
                                <div></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата от</label>
                                    <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата до</label>
                                    <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm">
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        🔍 Применить фильтры
                                    </button>
                                    <a href="{{ route('refuelings.index', ['car_id' => $carId ?? '']) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                        🗑️ Сбросить
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Найдено: {{ $refuelings->total() }} записей
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="mb-4 flex justify-end gap-2">
                        <a href="{{ route('refuelings.export-csv', request()->all()) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        </a>
                        <a href="{{ route('refuelings.create', ['car_id' => $carId ?? '']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Добавить заправку
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($refuelings->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">Нет данных по заданным фильтрам.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Дата</th>
                                        <th class="px-4 py-2 text-left">Автомобиль</th>
                                        <th class="px-4 py-2 text-left">Литры</th>
                                        <th class="px-4 py-2 text-left">Цена/л</th>
                                        <th class="px-4 py-2 text-left">Сумма</th>
                                        <th class="px-4 py-2 text-left">Пробег</th>
                                        <th class="px-4 py-2 text-left">АЗС</th>
                                        <th class="px-4 py-2 text-left">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refuelings as $refueling)
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-2">{{ $refueling->date->format('d.m.Y') }}</td>
                                            <td class="px-4 py-2">{{ $refueling->car->brand }} {{ $refueling->car->model }}</td>
                                            <td class="px-4 py-2">{{ number_format($refueling->converted_liters, 2) }} {{ $refueling->volume_unit }}</td>
                                            <td class="px-4 py-2">{{ number_format($refueling->converted_price, 2) }} {{ $refueling->currency }}/{{ $refueling->volume_unit }}</td>
                                            <td class="px-4 py-2 font-medium">{{ number_format($refueling->converted_amount, 2) }} {{ $refueling->currency }}</td>
                                            <td class="px-4 py-2">{{ number_format($refueling->converted_odometer) }} {{ $refueling->distance_unit }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $refueling->gas_station ?: '—' }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('refuelings.edit', $refueling) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">✏️</a>
                                                <form action="{{ route('refuelings.destroy', $refueling) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">🗑️</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $refuelings->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>