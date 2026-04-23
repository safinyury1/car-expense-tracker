<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Обзор') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-[#EDEEF0] dark:bg-[#141414]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Карточка автомобиля с фото на весь блок и неоном снизу -->
            <div class="relative rounded-xl shadow-md overflow-hidden mb-8 min-h-[200px]">
                <!-- Фоновая фотография -->
                @if($selectedCar->photo)
                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                         style="background-image: url('{{ Storage::url($selectedCar->photo) }}');">
                    </div>
                    <div class="absolute inset-0 bg-black/40"></div>
                @else
                    <div class="absolute inset-0 bg-gray-500 dark:bg-[#6B727F]"></div>
                @endif
                
                <!-- Контент поверх фона -->
                <div class="relative p-5 z-10 h-full flex flex-col justify-between min-h-[200px]">
                    <!-- Верхняя часть: название и год (правый верхний угол) -->
                    <div class="flex justify-end">
                        <div class="text-right">
                            <h3 class="text-xl font-bold text-white drop-shadow-lg">{{ $selectedCar->brand }} {{ $selectedCar->model }}</h3>
                            @if($selectedCar->year)
                                <p class="text-sm text-white/80 drop-shadow">{{ $selectedCar->year }} г.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Нижняя часть: кнопки (левый нижний угол) -->
                    <div class="flex justify-between items-end">
                        <!-- Кнопка фотоаппарата (левый нижний угол) -->
                        <button onclick="document.getElementById('photoInput').click()" 
                                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full p-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        
                        <!-- Кнопки (правый нижний угол) -->
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                <button onclick="toggleCarDropdown()" 
                                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-lg px-3 py-1.5 text-sm flex items-center gap-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    Сменить
                                </button>
                                <div id="carDropdown" class="hidden absolute right-0 bottom-full mb-2 w-48 bg-white dark:bg-[#222222] rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                    @foreach($cars as $car)
                                        <a href="{{ route('overview.index', ['car_id' => $car->id]) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] {{ $selectedCarId == $car->id ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : '' }}">
                                            {{ $car->brand }} {{ $car->model }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <a href="{{ route('cars.edit', $selectedCar) }}" 
                               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-lg p-1.5 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <form id="photoForm" action="{{ route('cars.update.photo', $selectedCar) }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PATCH')
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
            
            <!-- Неоновая подсветка снизу карточки -->
            <div class="relative -mt-4 mb-6 flex justify-center">
                <div class="w-3/4 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent rounded-full blur-sm"></div>
                <div class="absolute w-1/2 h-2 bg-blue-500/50 rounded-full blur-md"></div>
            </div>

            <!-- Отдельная карточка пробега -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Текущий пробег</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($convertedOdometer) }} <span class="text-sm font-normal">{{ $distanceUnit }}</span></p>
                            @if($lastUpdate)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">обновлено {{ $lastUpdate->diffForHumans() }}</p>
                            @endif
                        </div>
                        <button onclick="document.getElementById('odometerForm').classList.toggle('hidden')" 
                                class="bg-gray-100 dark:bg-[#6B727F] hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg p-1.5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                    </div>
                    
                    <form id="odometerForm" action="{{ route('cars.update.odometer', $selectedCar) }}" method="POST" class="hidden mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-2">
                            <input type="number" name="odometer" value="{{ $convertedOdometer }}" 
                                   class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm text-sm" 
                                   placeholder="Новый пробег" required>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm">Сохранить</button>
                            <button type="button" onclick="this.closest('form').classList.add('hidden')" 
                                    class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-[#1D1D1D] text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-md text-sm">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Две колонки -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <!-- Напоминания -->
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <img src="{{ asset('images/icons/notification.png') }}" alt="Напоминания" class="w-5 h-5">
                            Напоминания
                        </h3>
                        <a href="{{ route('reminders.index', ['car_id' => $selectedCarId]) }}" class="text-sm text-blue-500 hover:underline">Все</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($activeReminders as $reminder)
                            <a href="{{ route('reminders.show', $reminder) }}" class="block px-5 py-3 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $reminder->title }}</p>
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
                            <div class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">
                                Нет активных напоминаний
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Быстрые действия -->
                <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <img src="{{ asset('images/icons/fast_action.png') }}" alt="Быстрые действия" class="w-5 h-5">
                            Быстрые действия
                        </h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('expenses.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition">
                            <img src="{{ asset('images/icons/consumption.png') }}" alt="Расход" class="w-5 h-5">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Добавить расход</span>
                        </a>
                        <a href="{{ route('refuelings.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-lg transition">
                            <img src="{{ asset('images/icons/gas_station.png') }}" alt="Заправка" class="w-5 h-5">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Добавить заправку</span>
                        </a>
                        <a href="{{ route('reminders.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 rounded-lg transition">
                            <img src="{{ asset('images/icons/reminder.png') }}" alt="Напоминание" class="w-5 h-5">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Добавить напоминание</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Лента событий -->
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-md overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300">📋 Последние события</h3>
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
                               class="block px-5 py-3 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $event['type'] === 'income' ? 'bg-green-100 dark:bg-green-900/50' : ($event['type'] === 'service' ? 'bg-blue-100 dark:bg-blue-900/50' : 'bg-red-100 dark:bg-red-900/50') }}">
                                            @if($event['type'] === 'expense')
                                                <img src="{{ asset('images/icons/consumption2.png') }}" alt="Расход" class="w-5 h-5">
                                            @elseif($event['type'] === 'refueling')
                                                <img src="{{ asset('images/icons/gas_station2.png') }}" alt="Заправка" class="w-5 h-5">
                                            @elseif($event['type'] === 'income')
                                                <img src="{{ asset('images/icons/income.png') }}" alt="Доход" class="w-5 h-5">
                                            @else
                                                <img src="{{ asset('images/icons/service.png') }}" alt="Обслуживание" class="w-5 h-5">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $event['title'] }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($event['date'])->format('d.m.Y') }} • {{ number_format($event['odometer']) }} {{ $event['distance_unit'] }}</p>
                                            @if(isset($event['liters']))
                                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $event['liters'] }} {{ $event['volume_unit'] }}</p>
                                            @endif
                                            @if(isset($event['description']) && $event['description'] && $event['description'] !== 'Ручное обновление пробега')
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $event['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold {{ $event['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
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
                        <div class="px-5 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">
                            Нет событий. Добавьте расход, заправку или доход.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Неоновая подсветка снизу карточки */
        .neon-glow {
            position: relative;
        }
        
        .neon-glow::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 10%;
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #3b82f6, #3b82f6, #3b82f6, transparent);
            border-radius: 50%;
            filter: blur(3px);
            box-shadow: 0 0 10px #3b82f6, 0 0 20px #3b82f6;
        }
    </style>

    <script>
        function toggleCarDropdown() {
            const dropdown = document.getElementById('carDropdown');
            dropdown.classList.toggle('hidden');
        }
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('carDropdown');
            const button = event.target.closest('button');
            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleCarDropdown') === -1) {
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        });
    </script>
</x-app-layout>