<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Обзор') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 bg-[#EDEEF0] dark:bg-[#141414]">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <!-- Карточка автомобиля с фоном -->
            <div class="relative rounded-xl shadow-md overflow-hidden mb-6 min-h-[140px] sm:min-h-[200px]">
                @if($selectedCar->photo)
                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                         style="background-image: url('{{ Storage::url($selectedCar->photo) }}');">
                    </div>
                    <div class="absolute inset-0 bg-black/40"></div>
                @else
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-800 dark:from-[#6B727F] dark:to-[#4B5563]"></div>
                @endif
                <div class="relative p-4 sm:p-5 z-10 h-full flex flex-col justify-between min-h-[140px] sm:min-h-[200px]">
                    <div class="flex justify-end">
                        <div class="text-right">
                            <h3 class="text-base sm:text-xl font-bold text-white drop-shadow-lg">{{ $selectedCar->brand }} {{ $selectedCar->model }}</h3>
                            @if($selectedCar->year)
                                <p class="text-xs sm:text-sm text-white/80 drop-shadow">{{ $selectedCar->year }} г.</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-end">
                        <button onclick="document.getElementById('photoInput').click()" 
                                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-1.5 sm:p-2 transition w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <button onclick="openCarModal()" 
                                    class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-1.5 sm:p-2 transition w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </button>
                            
                            <a href="{{ route('cars.edit', $selectedCar) }}" 
                               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-1.5 sm:p-2 transition w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- МОДАЛЬНОЕ ОКНО -->
            <div id="carModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                <div class="bg-white dark:bg-[#222222] rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden">
                    <div class="px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Выберите автомобиль</h3>
                        <button onclick="closeCarModal()" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-2 overflow-y-auto max-h-[60vh]">
                        @foreach($cars as $car)
                            <a href="{{ route('overview.index', ['car_id' => $car->id]) }}" 
                               onclick="closeCarModal()"
                               class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition {{ $selectedCarId == $car->id ? 'bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                        @if($car->photo)
                                            <img src="{{ Storage::url($car->photo) }}" alt="{{ $car->brand }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $car->brand }} {{ $car->model }}</p>
                                        @if($car->year)
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $car->year }} г.</p>
                                        @endif
                                    </div>
                                </div>
                                @if($selectedCarId == $car->id)
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                    
                    <div class="px-5 sm:px-6 py-3 border-t border-gray-100 dark:border-gray-700">
                        <button onclick="closeCarModal()" 
                                class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2.5 rounded-xl transition">
                            Закрыть
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- НЕОНОВАЯ ПОДСВЕТКА (ЯРКАЯ) -->
            <!-- ========================================== -->
            <div class="relative -mt-6 mb-5 flex justify-center overflow-visible">
                <div class="neon-line-wrapper" style="width: 85%; max-width: 550px;">
                    <!-- Основная линия -->
                    <div class="neon-line"></div>
                    <!-- Свечение 1 -->
                    <div class="neon-glow glow-1"></div>
                    <!-- Свечение 2 (более широкое) -->
                    <div class="neon-glow glow-2"></div>
                    <!-- Свечение 3 (самое широкое) -->
                    <div class="neon-glow glow-3"></div>
                    <!-- Дополнительное свечение (голубое) -->
                    <div class="neon-glow glow-blue"></div>
                </div>
            </div>

            <!-- Карточка пробега -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden mb-5 sm:mb-6">
                <div class="p-3 sm:p-4">
                    <div class="flex justify-between items-center flex-wrap gap-2 sm:gap-3">
                        <div>
                            <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">Текущий пробег</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($convertedOdometer) }} <span class="text-xs sm:text-sm font-normal">{{ $distanceUnit }}</span></p>
                            @if($lastUpdate)
                                <p class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500 mt-0.5">обновлено {{ $lastUpdate->diffForHumans() }}</p>
                            @endif
                        </div>
                        <button onclick="document.getElementById('odometerForm').classList.toggle('hidden')" 
                                class="bg-gray-100 dark:bg-[#6B727F] hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full p-1.5 transition w-7 h-7 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                    </div>
                    
                    <form id="odometerForm" action="{{ route('cars.update.odometer', $selectedCar) }}" method="POST" class="hidden mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        @csrf
                        @method('PATCH')
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="number" name="odometer" value="{{ $convertedOdometer }}" 
                                   class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-md shadow-sm text-sm px-2 py-1.5 sm:py-2" 
                                   placeholder="Новый пробег" required>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm">Сохранить</button>
                                <button type="button" onclick="this.closest('form').classList.add('hidden')" 
                                        class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-[#1D1D1D] text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-md text-sm">Отмена</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Две колонки -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-5 sm:mb-6">
                
                <!-- Напоминания -->
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden">
                    <div class="px-4 sm:px-5 py-2.5 sm:py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <img src="{{ asset('images/icons/notification.png') }}" alt="Напоминания" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span>Напоминания</span>
                        </h3>
                        <a href="{{ route('reminders.index', ['car_id' => $selectedCarId]) }}" class="text-xs sm:text-sm text-blue-500 hover:underline">Все</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($activeReminders as $reminder)
                            <a href="{{ route('reminders.show', $reminder) }}" class="block px-4 sm:px-5 py-2.5 sm:py-3 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                <p class="text-sm sm:text-base font-medium text-gray-800 dark:text-white">{{ $reminder->title }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    @php
                                        $diff = $reminder->due_odometer - $maxOdometerKm;
                                    @endphp
                                    @if($diff > 0)
                                        через {{ number_format($diff) }} км
                                    @elseif($diff < 0)
                                        {{ number_format(abs($diff)) }} км назад
                                    @else
                                        требуется сейчас
                                    @endif
                                </p>
                            </a>
                        @empty
                            <div class="px-4 sm:px-5 py-6 sm:py-8 text-center text-gray-400 dark:text-gray-500 text-xs sm:text-sm">
                                Нет активных напоминаний
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Быстрые действия -->
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden">
                    <div class="px-4 sm:px-5 py-2.5 sm:py-3 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <img src="{{ asset('images/icons/fast_action.png') }}" alt="Быстрые действия" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span>Быстрые действия</span>
                        </h3>
                    </div>
                    <div class="p-3 sm:p-4 space-y-2">
                        <a href="{{ route('expenses.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 sm:p-2.5 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition">
                            <img src="{{ asset('images/icons/consumption.png') }}" alt="Расход" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Добавить расход</span>
                        </a>
                        <a href="{{ route('refuelings.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 sm:p-2.5 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-lg transition">
                            <img src="{{ asset('images/icons/gas_station.png') }}" alt="Заправка" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Добавить заправку</span>
                        </a>
                        <a href="{{ route('reminders.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 sm:p-2.5 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 rounded-lg transition">
                            <img src="{{ asset('images/icons/reminder.png') }}" alt="Напоминание" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Добавить напоминание</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Лента событий -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden">
                <div class="px-4 sm:px-5 py-2.5 sm:py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <img src="{{ asset('images/icons/history.png') }}" alt="События" class="w-4 h-4 sm:w-5 sm:h-5">
                        <span>Последние события</span>
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($events as $event)
                        @if($event['title'] !== 'Прочее' || ($event['title'] === 'Прочее' && $event['description'] !== 'Ручное обновление пробега'))
                            @php
                                $routeName = match($event['type']) {
                                    'expense' => 'expenses.show',
                                    'refueling' => 'refuelings.show',
                                    'income' => 'incomes.show',
                                    'service' => 'service.show',
                                    default => '#',
                                };
                            @endphp
                            <a href="{{ $routeName !== '#' ? route($routeName, $event['id']) : '#' }}" 
                               class="block px-4 sm:px-5 py-3 sm:py-3.5 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center {{ $event['type'] === 'income' ? 'bg-green-100 dark:bg-green-900/50' : ($event['type'] === 'service' ? 'bg-blue-100 dark:bg-blue-900/50' : 'bg-red-100 dark:bg-red-900/50') }}">
                                            @if($event['type'] === 'expense')
                                                <img src="{{ asset('images/icons/consumption2.png') }}" alt="Расход" class="w-4 h-4 sm:w-5 sm:h-5">
                                            @elseif($event['type'] === 'refueling')
                                                <img src="{{ asset('images/icons/gas_station2.png') }}" alt="Заправка" class="w-4 h-4 sm:w-5 sm:h-5">
                                            @elseif($event['type'] === 'income')
                                                <img src="{{ asset('images/icons/income.png') }}" alt="Доход" class="w-4 h-4 sm:w-5 sm:h-5">
                                            @else
                                                <img src="{{ asset('images/icons/service.png') }}" alt="Обслуживание" class="w-4 h-4 sm:w-5 sm:h-5">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm sm:text-base font-medium text-gray-800 dark:text-white">{{ $event['title'] }}</p>
                                            <p class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500">
                                                {{ \Carbon\Carbon::parse($event['date'])->format('d.m.Y') }} • {{ number_format($event['odometer']) }} {{ $event['distance_unit'] }}
                                                @if(isset($event['liters']))
                                                    • {{ $event['liters'] }} {{ $event['volume_unit'] }}
                                                @endif
                                            </p>
                                            @if(isset($event['description']) && $event['description'] && $event['description'] !== 'Ручное обновление пробега')
                                                <p class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ \Illuminate\Support\Str::limit($event['description'], 40) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="text-sm sm:text-base font-bold {{ $event['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            @if($event['type'] === 'income')
                                                +{{ number_format($event['amount'], 2) }} {{ $event['currency'] }}
                                            @elseif($event['amount'] != 0)
                                                -{{ number_format($event['amount'], 2) }} {{ $event['currency'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @empty
                        <div class="px-4 sm:px-5 py-6 sm:py-8 text-center text-gray-400 dark:text-gray-500 text-xs sm:text-sm">
                            Нет событий. Добавьте расход, заправку или доход.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <form id="photoForm" action="{{ route('cars.update.photo', $selectedCar) }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        @method('PATCH')
        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
    </form>

    <script>
        function openCarModal() {
            document.getElementById('carModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeCarModal() {
            document.getElementById('carModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCarModal();
            }
        });
        
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('carModal');
            if (event.target === modal) {
                closeCarModal();
            }
        });
    </script>

    <style>
        /* ========================================== */
        /* НЕОНОВАЯ ПОДСВЕТКА (МАКСИМАЛЬНО ЯРКАЯ) */
        /* ========================================== */
        .neon-line-wrapper {
            position: relative;
            height: 4px;
            margin: 0 auto;
        }

        /* Основная линия */
        .neon-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                #3b82f6 20%,
                #60a5fa 40%,
                #93c5fd 50%,
                #60a5fa 60%,
                #3b82f6 80%,
                transparent 100%
            );
            border-radius: 3px;
            animation: neonPulse 2s ease-in-out infinite;
            z-index: 3;
        }

        /* Свечение 1 (ближнее) */
        .neon-glow.glow-1 {
            position: absolute;
            top: -6px;
            left: 5%;
            width: 90%;
            height: 400%;
            background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.8) 0%, transparent 70%);
            filter: blur(8px);
            animation: glowPulse 2.5s ease-in-out infinite;
            z-index: 2;
            opacity: 0.9;
        }

        /* Свечение 2 (среднее) */
        .neon-glow.glow-2 {
            position: absolute;
            top: -12px;
            left: 0;
            width: 100%;
            height: 600%;
            background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.5) 0%, transparent 70%);
            filter: blur(20px);
            animation: glowPulse2 3s ease-in-out infinite;
            z-index: 1;
            opacity: 0.8;
        }

        /* Свечение 3 (дальнее) */
        .neon-glow.glow-3 {
            position: absolute;
            top: -25px;
            left: -10%;
            width: 120%;
            height: 800%;
            background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.2) 0%, transparent 70%);
            filter: blur(40px);
            animation: glowPulse3 3.5s ease-in-out infinite;
            z-index: 0;
            opacity: 0.6;
        }

        /* Дополнительное голубое свечение */
        .neon-glow.glow-blue {
            position: absolute;
            top: -3px;
            left: 15%;
            width: 70%;
            height: 300%;
            background: radial-gradient(ellipse at center, rgba(147, 197, 253, 0.6) 0%, transparent 70%);
            filter: blur(10px);
            animation: glowBlue 2.8s ease-in-out infinite;
            z-index: 2;
            opacity: 0.7;
        }

        /* Анимации */
        @keyframes neonPulse {
            0%, 100% {
                opacity: 1;
                transform: scaleX(1);
            }
            25% {
                opacity: 1;
                transform: scaleX(1.03);
            }
            50% {
                opacity: 0.9;
                transform: scaleX(0.97);
            }
            75% {
                opacity: 1;
                transform: scaleX(1.02);
            }
        }

        @keyframes glowPulse {
            0%, 100% {
                opacity: 0.9;
                transform: scale(1);
            }
            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }
        }

        @keyframes glowPulse2 {
            0%, 100% {
                opacity: 0.8;
                transform: scale(1);
            }
            50% {
                opacity: 0.4;
                transform: scale(1.3);
            }
        }

        @keyframes glowPulse3 {
            0%, 100% {
                opacity: 0.6;
                transform: scale(1);
            }
            50% {
                opacity: 0.3;
                transform: scale(1.4);
            }
        }

        @keyframes glowBlue {
            0%, 100% {
                opacity: 0.7;
                transform: scale(1);
            }
            50% {
                opacity: 0.4;
                transform: scale(1.15);
            }
        }

        /* ========================================== */
        /* ТЁМНАЯ ТЕМА — УСИЛЕННОЕ СВЕЧЕНИЕ */
        /* ========================================== */
        .dark .neon-line {
            background: linear-gradient(90deg, 
                transparent 0%, 
                #60a5fa 20%,
                #93c5fd 40%,
                #bfdbfe 50%,
                #93c5fd 60%,
                #60a5fa 80%,
                transparent 100%
            );
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.5), 0 0 60px rgba(59, 130, 246, 0.3);
        }

        .dark .neon-glow.glow-1 {
            background: radial-gradient(ellipse at center, rgba(96, 165, 250, 0.9) 0%, transparent 70%);
            filter: blur(10px);
        }

        .dark .neon-glow.glow-2 {
            background: radial-gradient(ellipse at center, rgba(96, 165, 250, 0.6) 0%, transparent 70%);
            filter: blur(25px);
        }

        .dark .neon-glow.glow-3 {
            background: radial-gradient(ellipse at center, rgba(96, 165, 250, 0.3) 0%, transparent 70%);
            filter: blur(50px);
        }

        .dark .neon-glow.glow-blue {
            background: radial-gradient(ellipse at center, rgba(147, 197, 253, 0.7) 0%, transparent 70%);
            filter: blur(12px);
        }

        /* ========================================== */
        /* СВЕТЛАЯ ТЕМА — КОНТРАСТНАЯ ЛИНИЯ */
        /* ========================================== */
        .neon-line-wrapper {
            --shadow-color: rgba(59, 130, 246, 0.15);
        }

        .neon-line {
            box-shadow: 
                0 0 15px rgba(59, 130, 246, 0.3),
                0 0 30px rgba(59, 130, 246, 0.15),
                0 0 60px rgba(59, 130, 246, 0.08);
        }

        @media (max-width: 640px) {
            .neon-line-wrapper {
                width: 92% !important;
            }
            .neon-glow.glow-3 {
                display: none;
            }
            .neon-glow.glow-2 {
                top: -8px;
                height: 400%;
                filter: blur(15px);
            }
        }
    </style>
</x-app-layout>