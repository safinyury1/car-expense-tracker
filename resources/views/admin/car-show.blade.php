<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Информация об автомобиле') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $car->brand }} {{ $car->model }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">Владелец: {{ $car->user->name }} ({{ $car->user->email }})</p>
                        @if($car->year)
                            <p class="text-gray-500 dark:text-gray-400">Год выпуска: {{ $car->year }}</p>
                        @endif
                        @if($car->vin)
                            <p class="text-gray-500 dark:text-gray-400">VIN: {{ $car->vin }}</p>
                        @endif
                        <p class="text-gray-500 dark:text-gray-400">Начальный пробег: {{ number_format($car->initial_odometer) }} км</p>
                        <p class="text-gray-500 dark:text-gray-400">Дата добавления: {{ $car->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Расходы</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalExpenses, 2) }} ₽</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Доходы</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalIncome, 2) }} ₽</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Чистая прибыль</p>
                            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ number_format($netProfit, 2) }} ₽
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Расходы</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-3 py-2 text-left">Дата</th>
                                        <th class="px-3 py-2 text-left">Категория</th>
                                        <th class="px-3 py-2 text-left">Сумма</th>
                                        <th class="px-3 py-2 text-left">Пробег</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($car->expenses as $expense)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="px-3 py-2">{{ $expense->date->format('d.m.Y') }}</td>
                                            <td class="px-3 py-2">{{ $expense->category->name }}</td>
                                            <td class="px-3 py-2 text-red-600">{{ number_format($expense->amount, 2) }} ₽</td>
                                            <td class="px-3 py-2">{{ number_format($expense->odometer) }} км</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-4 text-center text-gray-500">Нет расходов</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Заправки</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-3 py-2 text-left">Дата</th>
                                        <th class="px-3 py-2 text-left">Литры</th>
                                        <th class="px-3 py-2 text-left">Сумма</th>
                                        <th class="px-3 py-2 text-left">Пробег</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($car->refuelings as $refueling)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="px-3 py-2">{{ $refueling->date->format('d.m.Y') }}</td>
                                            <td class="px-3 py-2">{{ number_format($refueling->liters, 2) }} л</td>
                                            <td class="px-3 py-2 text-red-600">{{ number_format($refueling->total_amount, 2) }} ₽</td>
                                            <td class="px-3 py-2">{{ number_format($refueling->odometer) }} км</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-4 text-center text-gray-500">Нет заправок</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Доходы</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-3 py-2 text-left">Дата</th>
                                        <th class="px-3 py-2 text-left">Название</th>
                                        <th class="px-3 py-2 text-left">Сумма</th>
                                        <th class="px-3 py-2 text-left">Пробег</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($car->incomes as $income)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="px-3 py-2">{{ $income->date->format('d.m.Y') }}</td>
                                            <td class="px-3 py-2">{{ $income->title }}</td>
                                            <td class="px-3 py-2 text-green-600">+{{ number_format($income->amount, 2) }} ₽</td>
                                            <td class="px-3 py-2">{{ number_format($income->odometer ?? 0) }} км</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-4 text-center text-gray-500">Нет доходов</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-6 pt-4 border-t dark:border-gray-700">
                        <a href="{{ route('admin.cars') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                            Назад к списку
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('cars.edit', $car) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                                Редактировать
                            </a>
                            <form action="{{ route('cars.destroy', $car) }}" method="POST" onsubmit="return confirm('Удалить автомобиль?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>