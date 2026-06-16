<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Информация о пользователе') }}
            </h2>
            <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm transition">
                Назад
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 sm:p-4 rounded-lg mb-4 text-sm sm:text-base">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 sm:p-4 rounded-lg mb-4 text-sm sm:text-base">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-5 sm:p-6">
                    
                    <!-- Информация о пользователе -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $user->email }}</p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Роль: <span class="{{ $user->role === 'admin' ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300' }}">{{ $user->role === 'admin' ? 'Администратор' : 'Пользователь' }}</span></p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Регистрация: {{ $user->created_at->format('d.m.Y H:i') }}</p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Автомобилей: <span class="font-medium">{{ $user->cars->count() }}</span></p>
                        </div>
                        <div class="flex flex-wrap gap-2 justify-start md:justify-end items-start">
                            @if(Auth::id() !== $user->id)
                                @if($user->role !== 'admin')
                                    <a href="{{ route('admin.make.admin', $user->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition">Сделать админом</a>
                                @else
                                    <a href="{{ route('admin.make.user', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition">Снять права</a>
                                @endif
                                <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Удалить пользователя? Все его данные будут удалены!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">Удалить</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Статистика -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Автомобилей</p>
                            <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $user->cars->count() }}</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Расходов</p>
                            <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalExpenses, 2) }} ₽</p>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Заправок</p>
                            <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($totalRefuelings, 2) }} ₽</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Доходов</p>
                            <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalIncomes, 2) }} ₽</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Обслуживаний</p>
                            <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $totalServices }}</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Всего расходов</p>
                            <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($allExpenses, 2) }} ₽</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Чистая прибыль</p>
                            <p class="text-xl sm:text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ number_format($netProfit, 2) }} ₽
                            </p>
                        </div>
                    </div>

                    <!-- Управление пользователем (пароль и email) -->
                    @if(Auth::id() !== $user->id)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Сброс пароля -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-3">Сброс пароля</h4>
                            <form action="{{ route('admin.reset.password', $user->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="password" name="new_password" placeholder="Новый пароль" 
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="new_password_confirmation" placeholder="Подтвердите пароль" 
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition w-full">
                                    Сменить пароль
                                </button>
                            </form>
                        </div>

                        <!-- Смена email -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-3">Смена Email</h4>
                            <form action="{{ route('admin.update.email', $user->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="email" name="new_email" placeholder="Новый Email" value="{{ $user->email }}"
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition w-full">
                                    Сменить Email
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Автомобили пользователя -->
                    <div>
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-white mb-3">Автомобили пользователя</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Автомобиль</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Год</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($user->cars as $car)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer" onclick="window.location.href='{{ route('admin.car.show', $car->id) }}'">
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $car->id }}</td>
                                            <td class="px-3 py-2 text-sm text-blue-600 dark:text-blue-400 font-medium">{{ $car->brand }} {{ $car->model }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $car->year ?? '—' }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-300">{{ number_format($car->initial_odometer) }} км</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">Нет автомобилей</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>