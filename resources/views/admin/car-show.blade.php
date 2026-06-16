<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Информация об автомобиле') }}
            </h2>
            <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm transition">
                Назад к пользователям
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-5 sm:p-6">
                    
                    <!-- Информация об автомобиле -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">{{ $car->brand }} {{ $car->model }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Владелец: <a href="{{ route('admin.user.show', $car->user->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $car->user->name }}</a></p>
                            @if($car->year)
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Год выпуска: {{ $car->year }}</p>
                            @endif
                            @if($car->vin)
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">VIN: {{ $car->vin }}</p>
                            @endif
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Начальный пробег: {{ number_format($car->initial_odometer) }} км</p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Дата добавления: {{ $car->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 justify-start md:justify-end items-start">
                            @if($car->photo)
                                <img src="{{ Storage::url($car->photo) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-32 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                            @else
                                <div class="w-32 h-32 bg-gray-100 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">Нет фото</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Статистика -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Всего расходов</p>
                            <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalExpenses, 2) }} ₽</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Доходов</p>
                            <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalIncome, 2) }} ₽</p>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Количество записей</p>
                            <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $car->expenses->count() + $car->refuelings->count() + $car->incomes->count() + $car->reminders->count() }}</p>
                        </div>
                    </div>
                    
                    <!-- Расходы -->
                    @if($car->expenses->count() > 0)
                    <div class="mb-4">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Расходы</h4>
                        
                        <!-- Десктопная таблица -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Категория</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сумма</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($car->expenses->take(10) as $expense)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $expense->date->format('d.m.Y') }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $expense->category->name }}</td>
                                            <td class="px-3 py-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ number_format($expense->amount, 2) }} ₽</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ number_format($expense->odometer) }} км</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Мобильные карточки -->
                        <div class="block md:hidden space-y-3">
                            @foreach($car->expenses->take(10) as $expense)
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $expense->date->format('d.m.Y') }}</span>
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($expense->amount, 2) }} ₽</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $expense->category->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Пробег: {{ number_format($expense->odometer) }} км</p>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($car->expenses->count() > 10)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Показаны последние 10 из {{ $car->expenses->count() }} записей</p>
                        @endif
                    </div>
                    @endif

                    <!-- Заправки -->
                    @if($car->refuelings->count() > 0)
                    <div class="mb-4">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Заправки</h4>
                        
                        <!-- Десктопная таблица -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Литры</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сумма</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($car->refuelings->take(10) as $refueling)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $refueling->date->format('d.m.Y') }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ number_format($refueling->liters, 2) }} л</td>
                                            <td class="px-3 py-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ number_format($refueling->total_amount, 2) }} ₽</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ number_format($refueling->odometer) }} км</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Мобильные карточки -->
                        <div class="block md:hidden space-y-3">
                            @foreach($car->refuelings->take(10) as $refueling)
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $refueling->date->format('d.m.Y') }}</span>
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($refueling->total_amount, 2) }} ₽</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Литры: {{ number_format($refueling->liters, 2) }} л</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Пробег: {{ number_format($refueling->odometer) }} км</p>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($car->refuelings->count() > 10)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Показаны последние 10 из {{ $car->refuelings->count() }} записей</p>
                        @endif
                    </div>
                    @endif

                    <!-- Доходы -->
                    @if($car->incomes->count() > 0)
                    <div class="mb-4">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Доходы</h4>
                        
                        <!-- Десктопная таблица -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Название</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($car->incomes->take(10) as $income)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $income->date->format('d.m.Y') }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $income->title }}</td>
                                            <td class="px-3 py-2 text-sm text-green-600 dark:text-green-400 font-medium">+{{ number_format($income->amount, 2) }} ₽</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Мобильные карточки -->
                        <div class="block md:hidden space-y-3">
                            @foreach($car->incomes->take(10) as $income)
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $income->date->format('d.m.Y') }}</span>
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">+{{ number_format($income->amount, 2) }} ₽</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $income->title }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($car->incomes->count() > 10)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Показаны последние 10 из {{ $car->incomes->count() }} записей</p>
                        @endif
                    </div>
                    @endif

                    <!-- Напоминания -->
                    @if($car->reminders->count() > 0)
                    <div>
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Напоминания</h4>
                        
                        <!-- Десктопная таблица -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Название</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Статус</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($car->reminders->take(10) as $reminder)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $reminder->title }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ number_format($reminder->due_odometer) }} км</td>
                                            <td class="px-3 py-2 text-sm">
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $reminder->is_completed ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                                    {{ $reminder->is_completed ? 'Выполнено' : 'Ожидает' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Мобильные карточки -->
                        <div class="block md:hidden space-y-3">
                            @foreach($car->reminders->take(10) as $reminder)
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $reminder->title }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Пробег: {{ number_format($reminder->due_odometer) }} км</p>
                                    <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-xs font-medium {{ $reminder->is_completed ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                        {{ $reminder->is_completed ? 'Выполнено' : 'Ожидает' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($car->reminders->count() > 10)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Показаны последние 10 из {{ $car->reminders->count() }} записей</p>
                        @endif
                    </div>
                    @endif

                    @if($car->expenses->count() == 0 && $car->refuelings->count() == 0 && $car->incomes->count() == 0 && $car->reminders->count() == 0)
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            Нет записей для этого автомобиля
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>