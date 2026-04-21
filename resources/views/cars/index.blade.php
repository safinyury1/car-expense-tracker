<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Мои автомобили') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-4 flex justify-end">
    <a href="{{ route('cars.create.form') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Добавить автомобиль
    </a>
</div>

                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($cars->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">У вас пока нет добавленных автомобилей. Нажмите кнопку выше, чтобы добавить.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Фото</th>
                                        <th class="px-4 py-2 text-left">Марка</th>
                                        <th class="px-4 py-2 text-left">Модель</th>
                                        <th class="px-4 py-2 text-left">Год</th>
                                        <th class="px-4 py-2 text-left">Начальный пробег</th>
                                        <th class="px-4 py-2 text-left">Текущий пробег</th>
                                        <th class="px-4 py-2 text-left">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
    @foreach($cars as $car)
        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-4 py-2">
                @if($car->photo)
                    <img src="{{ Storage::url($car->photo) }}" class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 013 0m-3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 013 0m-3 0h-9m0-3H4.5m16.5-3h-9m-6 0H3m9-9a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 013 0m-3 0h-9m-6 0H3" />
                        </svg>
                    </div>
                @endif
            </td>
            <td class="px-4 py-2">{{ $car->brand }}</td>
            <td class="px-4 py-2">{{ $car->model }}</td>
            <td class="px-4 py-2">{{ $car->year ?? '—' }}</td>
            <td class="px-4 py-2">{{ number_format($car->converted_initial_odometer) }} {{ $car->distance_unit }}</td>
            <td class="px-4 py-2 font-semibold">{{ number_format($car->converted_current_odometer) }} {{ $car->distance_unit }}</td>
            <td class="px-4 py-2">
                <a href="{{ route('cars.edit', $car) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition mr-2">
                    Редактировать
                </a>
                <form action="{{ route('cars.destroy', $car) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить этот автомобиль?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition cursor-pointer">
                        Удалить
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>