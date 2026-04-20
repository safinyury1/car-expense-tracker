<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Управление автомобилями') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <form method="GET" class="mb-4 flex gap-2 flex-wrap">
                        <select name="user_id" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                            <option value="">Все пользователи</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Фильтр</button>
                        <a href="{{ route('admin.cars') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Сбросить</a>
                    </form>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">ID</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Автомобиль</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Владелец</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Год</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Пробег</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cars as $car)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location.href='{{ route('admin.car.show', $car->id) }}'">
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ $car->id }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600 font-medium text-blue-600 dark:text-blue-400">{{ $car->brand }} {{ $car->model }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ $car->user->name }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ $car->year ?? '—' }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ number_format($car->initial_odometer) }} км</td>
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ $car->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $cars->appends(['user_id' => $userId])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>