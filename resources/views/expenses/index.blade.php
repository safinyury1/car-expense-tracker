<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Расходы') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Фильтр по автомобилям -->
                    <div class="mb-4 flex justify-between items-center">
                        <form method="GET" action="{{ route('expenses.index') }}" class="flex gap-2">
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
                        
                        <div class="flex gap-2">
                            <a href="{{ route('expenses.export-csv', ['car_id' => $carId]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                📥 Экспорт CSV
                            </a>
                            <a href="{{ route('expenses.create', ['car_id' => $carId]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Добавить расход
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($expenses->isEmpty())
                        <p class="text-gray-500">У вас пока нет добавленных расходов. Нажмите кнопку выше, чтобы добавить.</p>
                    @else
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Дата</th>
                                    <th class="px-4 py-2 text-left">Автомобиль</th>
                                    <th class="px-4 py-2 text-left">Категория</th>
                                    <th class="px-4 py-2 text-left">Сумма</th>
                                    <th class="px-4 py-2 text-left">Пробег</th>
                                    <th class="px-4 py-2 text-left">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $expense->date->format('d.m.Y') }}</td>
                                        <td class="px-4 py-2">{{ $expense->car->brand }} {{ $expense->car->model }}</td>
                                        <td class="px-4 py-2">{{ $expense->category->name }}</td>
                                        <td class="px-4 py-2">{{ number_format($expense->amount, 2) }} ₽</td>
                                        <td class="px-4 py-2">{{ number_format($expense->odometer) }} км</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ред.</a>
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Удалить</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        
                        <div class="mt-4">
                            {{ $expenses->appends(['car_id' => $carId])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>