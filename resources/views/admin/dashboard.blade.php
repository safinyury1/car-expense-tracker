<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Админ-панель') }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">
                {{ now()->format('d.m.Y H:i') }}
            </span>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <!-- СТАТИСТИКА ПОСЕЩЕНИЙ -->
            <div class="mb-6">
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                    
                    <button onclick="toggleVisits()" 
                            class="w-full px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">Статистика посещений</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">всего: {{ $totalVisits ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex gap-3 text-right text-xs sm:text-sm">
                                <div class="bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-lg">
                                    <span class="text-gray-500 dark:text-gray-400">сегодня:</span>
                                    <span class="font-bold text-green-600 dark:text-green-400 ml-1">{{ $todayVisits ?? 0 }}</span>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 px-2 py-1 rounded-lg">
                                    <span class="text-gray-500 dark:text-gray-400">активных (7д):</span>
                                    <span class="font-bold text-purple-600 dark:text-purple-400 ml-1">{{ $activeUsers ?? 0 }}</span>
                                </div>
                            </div>
                            <svg id="visitsArrow" class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    
                    <div id="visitsDetails" class="hidden border-t border-gray-100 dark:border-gray-700">
                        <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Всего посещений</p>
                                    <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">За сегодня</p>
                                    <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $todayVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">За неделю</p>
                                    <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $weekVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">За месяц</p>
                                    <p class="text-xl sm:text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $monthVisits ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                                <h4 class="font-medium text-gray-700 dark:text-gray-300">Посещения по дням (последние 7 дней)</h4>
                                <span class="text-xs text-gray-400 dark:text-gray-500">Всего посещений</span>
                            </div>
                            <div class="flex items-end gap-1 sm:gap-2 h-32 sm:h-40">
                                @php
                                    $maxVisits = max(array_column($visitsByDay, 'count') ?: [1]);
                                @endphp
                                @foreach($visitsByDay as $day)
                                    @php
                                        $height = $day['count'] > 0 ? ($day['count'] / max($maxVisits, 1)) * 100 : 0;
                                    @endphp
                                    <div class="flex-1 text-center">
                                        <div class="relative h-24 sm:h-32 flex items-end justify-center">
                                            <div class="w-full max-w-10 bg-gradient-to-t from-blue-400 to-blue-600 rounded-t transition-all" style="height: {{ $height }}%; min-height: {{ $day['count'] > 0 ? '4px' : '0' }}"></div>
                                            @if($day['count'] > 0)
                                                <span class="absolute -top-5 text-[10px] font-medium text-blue-600 dark:text-blue-400">{{ $day['count'] }}</span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $day['date'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Лучший день</p>
                                    <p class="text-xl font-bold text-pink-600 dark:text-pink-400">{{ $bestDayVisits ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">В среднем в день</p>
                                    <p class="text-xl font-bold text-orange-600 dark:text-orange-400">{{ $avgVisitsPerDay ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Активных (7 дней)</p>
                                    <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $activeUsers ?? 0 }}</p>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Уникальных всего</p>
                                    <p class="text-xl font-bold text-teal-600 dark:text-teal-400">{{ $totalUniqueVisitors ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ОСНОВНАЯ СТАТИСТИКА -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-3 sm:p-4 text-center hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['users'] }}</div>
                    <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Пользователей</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-3 sm:p-4 text-center hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['cars'] }}</div>
                    <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Автомобилей</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-3 sm:p-4 text-center hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['expenses'] }}</div>
                    <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Расходов</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-3 sm:p-4 text-center hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['refuelings'] }}</div>
                    <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Заправок</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-3 sm:p-4 text-center hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div class="text-xl sm:text-2xl font-bold text-teal-600 dark:text-teal-400">{{ $stats['incomes'] }}</div>
                    <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Доходов</div>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm p-3 sm:p-4 text-center hover:shadow-md transition border border-gray-100 dark:border-gray-700">
                    <div class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['services'] }}</div>
                    <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Обслуживаний</div>
                </div>
            </div>
            
            <!-- АНАЛИТИКА ПО КАТЕГОРИЯМ -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 text-base sm:text-lg">Расходы по категориям</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">{{ $categoryStats->count() }} категорий</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-8">На что пользователи тратят больше всего</p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <div class="flex-1">
                            <canvas id="categoryChart" height="280"></canvas>
                        </div>
                        <div class="flex-1">
                            <div class="space-y-2">
                                @php $total = $categoryStats->sum('total'); @endphp
                                @foreach($categoryStats as $index => $category)
                                    <div class="flex justify-between items-center p-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 rounded-full shadow-sm" style="background: {{ $categoryColors[$index % count($categoryColors)] }}"></div>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ number_format($category->total, 2) }} ₽</span>
                                            <span class="text-xs text-gray-400 ml-2 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">{{ $total > 0 ? round(($category->total / $total) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Итого:</span>
                                    <span class="text-base font-bold text-blue-600 dark:text-blue-400">{{ number_format($total, 2) }} ₽</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ПОСЛЕДНИЕ ПОЛЬЗОВАТЕЛИ С ПАГИНАЦИЕЙ -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 text-base sm:text-lg">Последние пользователи</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">всего: {{ $recentUsers->total() }}</span>
                    </div>
                    <a href="{{ route('admin.users') }}" class="text-sm text-blue-500 hover:text-blue-600 transition inline-flex items-center gap-1">
                        Все пользователи
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentUsers as $user)
                        <div class="px-4 sm:px-6 py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Нет пользователей</div>
                    @endforelse
                </div>
                
                <!-- ПАГИНАЦИЯ -->
                @if($recentUsers->hasPages())
                    <div class="border-t border-gray-100 dark:border-gray-700 px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-800/30">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                                Показано с {{ $recentUsers->firstItem() }} по {{ $recentUsers->lastItem() }} 
                                из {{ $recentUsers->total() }} пользователей
                            </div>
                            
                            <div class="flex items-center justify-center gap-1 overflow-x-auto pb-1 sm:pb-0">
                                @if($recentUsers->onFirstPage())
                                    <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">Назад</span>
                                @else
                                    <a href="{{ $recentUsers->previousPageUrl() }}" 
                                       class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">Назад</a>
                                @endif
                                
                                <div class="flex gap-0.5 sm:gap-1">
                                    @foreach($recentUsers->getUrlRange(1, $recentUsers->lastPage()) as $page => $url)
                                        @php $currentPage = $recentUsers->currentPage(); $lastPage = $recentUsers->lastPage(); @endphp
                                        @if($page == $currentPage)
                                            <span class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium bg-blue-600 text-white shadow-sm text-center">{{ $page }}</span>
                                        @elseif($page == 1 || $page == $lastPage || ($page >= $currentPage - 2 && $page <= $currentPage + 2))
                                            <a href="{{ $url }}" class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">{{ $page }}</a>
                                        @elseif($page == $currentPage - 3 || $page == $currentPage + 3)
                                            <span class="px-1 sm:px-2 py-1.5 sm:py-2 text-gray-500 dark:text-gray-400 text-xs sm:text-sm">...</span>
                                        @endif
                                    @endforeach
                                </div>
                                
                                @if($recentUsers->hasMorePages())
                                    <a href="{{ $recentUsers->nextPageUrl() }}" 
                                       class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">Вперед</a>
                                @else
                                    <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed whitespace-nowrap">Вперед</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // График категорий
        const categoryData = @json($categoryStats);
        const colors = @json($categoryColors);

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.name),
                datasets: [{
                    data: categoryData.map(item => item.total),
                    backgroundColor: colors.slice(0, categoryData.length),
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            boxWidth: 12,
                            font: { size: 11 },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return context.label + ': ' + context.raw.toLocaleString('ru-RU') + ' ₽ (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>