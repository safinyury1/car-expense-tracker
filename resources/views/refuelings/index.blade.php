<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Заправки') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-4 flex justify-between items-center">
                        <form method="GET" action="{{ route('refuelings.index') }}" class="flex gap-2">
                            <select name="car_id" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Все автомобили</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $carId == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Фильтр</button>
                        </form>
                        
                        <a href="{{ route('refuelings.create', ['car_id' => $carId]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Добавить заправку
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($refuelings->isEmpty())
                        <p class="text-gray-500">У вас пока нет добавленных заправок. Нажмите кнопку выше, чтобы добавить.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Дата</th>
                                    <th class="px-4 py-2 text-left">Автомобиль</th>
                                    <th class="px-4 py-2 text-left">Литры</th>
                                    <th class="px-4 py-2 text-left">Цена/л</th>
                                    <th class="px-4 py-2 text-left">Сумма</th>
                                    <th class="px-4 py-2 text-left">Пробег</th>
                                    <th class="px-4 py-2 text-left">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($refuelings as $refueling)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $refueling->date->format('d.m.Y') }}</td>
                                        <td class="px-4 py-2">{{ $refueling->car->brand }} {{ $refueling->car->model }}</td>
                                        <td class="px-4 py-2">{{ number_format($refueling->liters, 2) }} л</td>
                                        <td class="px-4 py-2">{{ number_format($refueling->price_per_liter, 2) }} ₽</td>
                                        <td class="px-4 py-2">{{ number_format($refueling->total_amount, 2) }} ₽</td>
                                        <td class="px-4 py-2">{{ number_format($refueling->odometer) }} км</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('refuelings.edit', $refueling) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ред.</a>
                                            <form action="{{ route('refuelings.destroy', $refueling) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Удалить</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="mt-4">
                            {{ $refuelings->appends(['car_id' => $carId])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>