<x-app-layout>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Карточка автомобиля -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <!-- Верхняя часть: фото + марка/модель + кнопки -->
                    <div class="flex items-center gap-5">
                        <!-- Фото ровно по центру -->
                        <div class="relative shrink-0">
                            @if($selectedCar->photo)
                                <img src="{{ Storage::url($selectedCar->photo) }}" 
                                     class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 013 0m-3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 013 0m-3 0h-9m0-3H4.5m16.5-3h-9m-6 0H3m9-9a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 013 0m-3 0h-9m-6 0H3" />
                                    </svg>
                                </div>
                            @endif
                            <button onclick="document.getElementById('photoInput').click()" 
                                    class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 text-white rounded-full p-1.5 shadow-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $selectedCar->brand }} {{ $selectedCar->model }}</h3>
                                    @if($selectedCar->year)
                                        <p class="text-sm text-gray-500">{{ $selectedCar->year }} г.</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <!-- Кнопка смены автомобиля -->
                                    <div class="relative">
                                        <button onclick="toggleCarDropdown()" 
                                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg px-3 py-1.5 text-sm flex items-center gap-1 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                            Сменить
                                        </button>
                                        <div id="carDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                            @foreach($cars as $car)
                                                <a href="{{ route('overview.index', ['car_id' => $car->id]) }}" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $selectedCarId == $car->id ? 'bg-blue-50 text-blue-600' : '' }}">
                                                    {{ $car->brand }} {{ $car->model }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Кнопка редактирования авто -->
                                    <a href="{{ route('cars.edit', $selectedCar) }}" 
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg p-1.5 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Форма загрузки фото (скрытая) -->
                    <form id="photoForm" action="{{ route('cars.update.photo', $selectedCar) }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PATCH')
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
                    </form>
                </div>
            </div>

            <!-- Отдельная карточка пробега -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Текущий пробег</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($maxOdometer) }} <span class="text-base font-normal">км</span></p>
                            @if($lastUpdate)
                                <p class="text-xs text-gray-400 mt-1">обновлено {{ $lastUpdate->diffForHumans() }}</p>
                            @endif
                        </div>
                        <button onclick="document.getElementById('odometerForm').classList.toggle('hidden')" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg p-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Форма редактирования пробега (скрытая) -->
                    <form id="odometerForm" action="{{ route('cars.update.odometer', $selectedCar) }}" method="POST" class="hidden mt-4 pt-4 border-t border-gray-100">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-2">
                            <input type="number" name="odometer" value="{{ $maxOdometer }}" 
                                   class="flex-1 border-gray-300 rounded-md shadow-sm text-sm" 
                                   placeholder="Новый пробег" required>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm">Сохранить</button>
                            <button type="button" onclick="this.closest('form').classList.add('hidden')" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-1.5 rounded-md text-sm">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Две колонки -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <!-- Напоминания -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-700">🔔 Напоминания</h3>
                        <a href="{{ route('reminders.index', ['car_id' => $selectedCarId]) }}" class="text-sm text-blue-500 hover:underline">Все</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($activeReminders as $reminder)
                            <div class="px-5 py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $reminder->title }}</p>
                                    <p class="text-xs text-gray-400">
                                        @php
                                            $diff = $reminder->due_odometer - $maxOdometer;
                                        @endphp
                                        @if($diff > 0)
                                            через {{ number_format($diff) }} км
                                        @elseif($diff < 0)
                                            {{ number_format(abs($diff)) }} км назад
                                        @else
                                            требуется сейчас
                                        @endif
                                    </p>
                                </div>
                                <form action="{{ route('reminders.toggle', $reminder) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-500 hover:text-green-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-gray-400 text-sm">
                                Нет активных напоминаний
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Быстрые действия -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-700">⚡ Быстрые действия</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('expenses.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            <span class="text-blue-500">💰</span>
                            <span class="text-sm text-gray-700">Добавить расход</span>
                        </a>
                        <a href="{{ route('refuelings.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 bg-green-50 hover:bg-green-100 rounded-lg transition">
                            <span class="text-green-500">⛽</span>
                            <span class="text-sm text-gray-700">Добавить заправку</span>
                        </a>
                        <a href="{{ route('reminders.create', ['car_id' => $selectedCarId]) }}" 
                           class="flex items-center gap-3 p-2 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                            <span class="text-yellow-500">⏰</span>
                            <span class="text-sm text-gray-700">Добавить напоминание</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Лента событий -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">📋 Последние события</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($events as $event)
                        <div class="px-5 py-3 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $event['type'] == 'expense' ? 'bg-red-100' : 'bg-green-100' }}">
                                    @if($event['type'] == 'expense')
                                        <span class="text-red-500 text-sm">💰</span>
                                    @else
                                        <span class="text-green-500 text-sm">⛽</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $event['title'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $event['date']->format('d.m.Y') }} • {{ number_format($event['odometer']) }} км</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold {{ $event['type'] == 'expense' ? 'text-red-600' : 'text-green-600' }}">
                                -{{ number_format($event['amount'], 2) }} ₽
                            </p>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-400 text-sm">
                            Нет событий. Добавьте расход или заправку.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCarDropdown() {
            const dropdown = document.getElementById('carDropdown');
            dropdown.classList.toggle('hidden');
        }
        // Закрыть dropdown при клике вне
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