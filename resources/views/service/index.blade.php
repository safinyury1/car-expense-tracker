<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Обслуживание') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <form method="GET" action="{{ route('service.index') }}" class="flex flex-col sm:flex-row gap-2">
                            <select name="car_id" class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                <option value="">Все автомобили</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ ($carId ?? '') == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">Применить</button>
                                <a href="{{ route('service.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">Сбросить</a>
                            </div>
                        </form>
                        
                        <a href="{{ route('service.create', ['car_id' => $carId ?? '']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            + Добавить обслуживание
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($services->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">Нет данных об обслуживании.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-[#6B727F]">
                                        <th class="px-4 py-2 text-left">Дата</th>
                                        <th class="px-4 py-2 text-left">Автомобиль</th>
                                        <th class="px-4 py-2 text-left">Название</th>
                                        <th class="px-4 py-2 text-left">Стоимость</th>
                                        <th class="px-4 py-2 text-left">Пробег</th>
                                        <th class="px-4 py-2 text-left">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-[#1D1D1D] dark:hover:bg-[#1D1D1D]">
                                            <td class="px-4 py-2">{{ $service->service_date ? \Carbon\Carbon::parse($service->service_date)->format('d.m.Y') : '—' }}</td>
                                            <td class="px-4 py-2">{{ $service->car->brand }} {{ $service->car->model }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('service.show', $service) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $service->title }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-2">{{ number_format($service->converted_cost, 2) }} {{ $service->currency }}</td>
                                            <td class="px-4 py-2">{{ number_format($service->converted_odometer) }} {{ $service->distance_unit }}</td>
                                            <td class="px-4 py-2">
                                                <div class="flex gap-2">
                                                    <a href="{{ route('service.show', $service) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                                        Просмотр
                                                    </a>
                                                    <form action="{{ route('service.destroy', $service) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition cursor-pointer">
                                                            Удалить
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $services->appends(['car_id' => $carId ?? ''])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>