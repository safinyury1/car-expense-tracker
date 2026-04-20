<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Информация о пользователе') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">Email: {{ $user->email }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Роль: {{ $user->role === 'admin' ? 'Администратор' : 'Пользователь' }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Дата регистрации: {{ $user->created_at->format('d.m.Y H:i') }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Количество автомобилей: {{ $user->cars->count() }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Автомобилей</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $user->cars->count() }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Расходов</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalExpenses, 2) }} ₽</p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Заправок</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($totalRefuelings, 2) }} ₽</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Доходов</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalIncomes, 2) }} ₽</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Обслуживаний</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $totalServices }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Всего расходов</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($allExpenses, 2) }} ₽</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Чистая прибыль</p>
                            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ number_format($netProfit, 2) }} ₽
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Автомобили пользователя</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-3 py-2 text-left">ID</th>
                                        <th class="px-3 py-2 text-left">Автомобиль</th>
                                        <th class="px-3 py-2 text-left">Год</th>
                                        <th class="px-3 py-2 text-left">Пробег</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->cars as $car)
                                        <tr class="border-b dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700" onclick="window.location.href='{{ route('admin.car.show', $car->id) }}'">
                                            <td class="px-3 py-2">{{ $car->id }}</td>
                                            <td class="px-3 py-2 text-blue-600 dark:text-blue-400">{{ $car->brand }} {{ $car->model }}</td>
                                            <td class="px-3 py-2">{{ $car->year ?? '—' }}</td>
                                            <td class="px-3 py-2">{{ number_format($car->initial_odometer) }} км</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-4 text-center text-gray-500">Нет автомобилей</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-6 pt-4 border-t dark:border-gray-700">
                        <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                            Назад к списку
                        </a>
                        @if(Auth::id() !== $user->id)
                            <div class="flex gap-2">
                                @if($user->role !== 'admin')
                                    <a href="{{ route('admin.make.admin', $user->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">Сделать админом</a>
                                @else
                                    <a href="{{ route('admin.make.user', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">Снять права</a>
                                @endif
                                <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Удалить пользователя? Все его данные будут удалены!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">Удалить</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>