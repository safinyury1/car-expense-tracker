<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Админ-панель') }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('d.m.Y H:i') }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- СТАТИСТИКА ПОСЕЩЕНИЙ (РАСКРЫВАЮЩИЙСЯ БЛОК) -->
            <div class="mb-6">
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden">
                    
                    <button onclick="toggleVisits()" 
                            class="w-full px-6 py-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">Статистика посещений</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">всего: {{ $totalVisits ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex gap-4 text-right text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">уникальных:</span>
                                    <span class="font-bold text-green-600 dark:text-green-400 ml-1">{{ $uniqueVisitors ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">IP:</span>
                                    <span class="font-bold text-purple-600 dark:text-purple-400 ml-1">{{ $uniqueIps ?? 0 }}</span>
                                </div>
                            </div>
                            <svg id="visitsArrow" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    
                    <div id="visitsDetails" class="hidden border-t border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Всего посещений</p>
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Уникальных пользователей</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $uniqueVisitors ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Уникальных IP</p>
                                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $uniqueIps ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">В среднем в день</p>
                                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $avgVisitsPerDay ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-4">Посещения по дням (последние 7 дней)</h4>
                            <div class="flex items-end gap-2 h-40">
                                @php
                                    $maxVisits = max(array_column($visitsByDay, 'count') ?: [1]);
                                @endphp
                                @foreach($visitsByDay as $day)
                                    @php
                                        $height = $day['count'] > 0 ? ($day['count'] / max($maxVisits, 1)) * 100 : 0;
                                    @endphp
                                    <div class="flex-1 text-center">
                                        <div class="relative h-32 flex items-end justify-center">
                                            <div class="w-full max-w-12 bg-blue-500 rounded-t transition-all" style="height: {{ $height }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $day['date'] }}</p>
                                        <p class="text-xs font-bold {{ $day['count'] > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}">{{ number_format($day['count']) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-4">По периодам</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Сегодня</p>
                                    <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $todayVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Вчера</p>
                                    <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ $yesterdayVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Неделя</p>
                                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $weekVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Месяц</p>
                                    <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $monthVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-[#374151] rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Лучший день</p>
                                    <p class="text-xl font-bold text-pink-600 dark:text-yellow-400">{{ $bestDayVisits ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Последние посещения</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Показаны последние 20 записей</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-gray-700">
                                            <th class="text-left py-2 text-gray-500 dark:text-gray-400 font-medium">Дата и время</th>
                                            <th class="text-left py-2 text-gray-500 dark:text-gray-400 font-medium">Пользователь</th>
                                            <th class="text-left py-2 text-gray-500 dark:text-gray-400 font-medium">IP адрес</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentVisits as $visit)
                                            <tr class="border-b border-gray-50 dark:border-gray-800">
                                                <td class="py-2 text-gray-600 dark:text-gray-400">
                                                    {{ $visit->created_at->format('d.m.Y H:i:s') }}
                                                </td>
                                                <td class="py-2">
                                                    <span class="font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $visit->user?->name ?? 'Гость' }}
                                                    </span>
                                                    @if($visit->user)
                                                        <span class="text-xs text-gray-400 ml-1">({{ $visit->user->email }})</span>
                                                    @endif
                                                </td>
                                                <td class="py-2">
                                                    <code class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-2 py-1 rounded">{{ $visit->ip }}</code>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">Нет данных о посещениях</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ОСНОВНАЯ СТАТИСТИКА -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['users'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Пользователей</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['cars'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Автомобилей</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['expenses'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Расходов</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['refuelings'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Заправок</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-teal-600 dark:text-teal-400">{{ $stats['incomes'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Доходов</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['services'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Обслуживаний</div>
                </div>
            </div>
            
            <!-- ПОСЛЕДНИЕ ПОЛЬЗОВАТЕЛИ И АВТОМОБИЛИ -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Последние пользователи -->
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200">Последние пользователи</h3>
                    </div>
                    <div class="flex-1 divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentUsers as $user)
                            <div class="px-6 py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Нет пользователей</div>
                        @endforelse
                    </div>
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.users') }}" class="text-sm text-blue-500 hover:text-blue-600">Все пользователи</a>
                    </div>
                </div>
                
                <!-- Последние автомобили (кнопка внизу) -->
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200">Последние автомобили</h3>
                    </div>
                    <div class="flex-1 divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentCars as $car)
                            <div class="px-6 py-3">
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $car->brand }} {{ $car->model }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Владелец: {{ $car->user->name }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $car->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Нет автомобилей</div>
                        @endforelse
                    </div>
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.cars') }}" class="text-sm text-blue-500 hover:text-blue-600">Все автомобили</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleVisits() {
            const details = document.getElementById('visitsDetails');
            const arrow = document.getElementById('visitsArrow');
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                details.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</x-app-layout>