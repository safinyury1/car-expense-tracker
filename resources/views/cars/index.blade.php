<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои автомобили') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-4">
                        <a href="{{ route('cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Добавить автомобиль
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($cars->isEmpty())
                        <p class="text-gray-500">У вас пока нет добавленных автомобилей. Нажмите кнопку выше, чтобы добавить.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Марка</th>
                                    <th class="px-4 py-2 text-left">Модель</th>
                                    <th class="px-4 py-2 text-left">Год</th>
                                    <th class="px-4 py-2 text-left">Начальный пробег</th>
                                    <th class="px-4 py-2 text-left">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cars as $car)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $car->brand }}</td>
                                        <td class="px-4 py-2">{{ $car->model }}</td>
                                        <td class="px-4 py-2">{{ $car->year ?? '—' }}</td>
                                        <td class="px-4 py-2">{{ number_format($car->initial_odometer) }} км</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('cars.edit', $car) }}" class="text-blue-600 hover:text-blue-900 mr-3">✏️ Редактировать</a>
                                            <form action="{{ route('cars.destroy', $car) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить этот автомобиль?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">🗑️ Удалить</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>