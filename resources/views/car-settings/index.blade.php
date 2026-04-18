<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Настройки авто') }}
            </h2>
            <select id="carSelect" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm text-sm">
                @foreach($cars as $car)
                    <option value="{{ $car->id }}" {{ $selectedCar?->id == $car->id ? 'selected' : '' }}>
                        {{ $car->brand }} {{ $car->model }}
                    </option>
                @endforeach
            </select>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if($selectedCar)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                    <!-- Единица расстояния -->
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">Единица расстояния</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Километры (км) или Мили (mi)</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="updateDistanceUnit('km')" 
                                    class="distance-unit-btn px-4 py-1.5 rounded-lg text-sm transition {{ $selectedCar->distance_unit === 'km' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                км
                            </button>
                            <button onclick="updateDistanceUnit('miles')" 
                                    class="distance-unit-btn px-4 py-1.5 rounded-lg text-sm transition {{ $selectedCar->distance_unit === 'miles' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                мили
                            </button>
                        </div>
                    </div>

                    <!-- Единица объема -->
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">Единица объема</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Литры (л) или Галлоны (gal)</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="updateVolumeUnit('liters')" 
                                    class="volume-unit-btn px-4 py-1.5 rounded-lg text-sm transition {{ $selectedCar->volume_unit === 'liters' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                литры
                            </button>
                            <button onclick="updateVolumeUnit('gallons')" 
                                    class="volume-unit-btn px-4 py-1.5 rounded-lg text-sm transition {{ $selectedCar->volume_unit === 'gallons' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                галлоны
                            </button>
                        </div>
                    </div>

                    <!-- Валюта -->
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">Валюта</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Выберите валюту для отображения</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="updateCurrency('RUB')" 
                                    class="currency-btn px-4 py-1.5 rounded-lg text-sm transition {{ $selectedCar->currency === 'RUB' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                RUB ₽
                            </button>
                            <button onclick="updateCurrency('USD')" 
                                    class="currency-btn px-4 py-1.5 rounded-lg text-sm transition {{ $selectedCar->currency === 'USD' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                USD $
                            </button>
                            <button onclick="updateCurrency('EUR')" 
                                    class="currency-btn px-4 py-1.5 rounded-lg text-sm transition {{ $selectedCar->currency === 'EUR' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                EUR €
                            </button>
                        </div>
                    </div>

                    <!-- Категории (ссылка) -->
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">Категории</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Управление категориями расходов</p>
                        </div>
                        <a href="{{ route('categories.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                            Настроить →
                        </a>
                    </div>

                    <!-- Удалить все данные -->
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-red-600 dark:text-red-400">Удалить все данные</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Очистить расходы, заправки и напоминания</p>
                        </div>
                        <button onclick="confirmDeleteData()" class="text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 text-sm font-medium">
                            Очистить →
                        </button>
                    </div>

                    <!-- Удалить автомобиль -->
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-red-600 dark:text-red-400">Удалить автомобиль</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Полностью удалить автомобиль со всеми данными</p>
                        </div>
                        <button onclick="confirmDeleteCar()" class="text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 text-sm font-medium">
                            Удалить →
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden p-8 text-center text-gray-500 dark:text-gray-400">
                    У вас нет автомобилей. Добавьте автомобиль в разделе "Мои автомобили"
                </div>
            @endif
        </div>
    </div>

    <script>
        const carSelect = document.getElementById('carSelect');
        if (carSelect) {
            carSelect.addEventListener('change', function() {
                window.location.href = '{{ route("car-settings.index") }}?car_id=' + this.value;
            });
        }
        
        function updateDistanceUnit(unit) {
            fetch('{{ route("car-settings.distance-unit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    car_id: {{ $selectedCar?->id ?? 0 }},
                    unit: unit
                })
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    location.reload();
                }
            }).catch(error => {
                alert('Ошибка при сохранении');
            });
        }
        
        function updateVolumeUnit(unit) {
            fetch('{{ route("car-settings.volume-unit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    car_id: {{ $selectedCar?->id ?? 0 }},
                    unit: unit
                })
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    location.reload();
                }
            }).catch(error => {
                alert('Ошибка при сохранении');
            });
        }
        
        function updateCurrency(currency) {
            fetch('{{ route("car-settings.currency") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    car_id: {{ $selectedCar?->id ?? 0 }},
                    currency: currency
                })
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    location.reload();
                }
            }).catch(error => {
                alert('Ошибка при сохранении');
            });
        }
        
        function confirmDeleteData() {
            if (confirm('Вы уверены? Все расходы, заправки и напоминания этого автомобиля будут удалены!')) {
                fetch('{{ route("car-settings.delete-all") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        car_id: {{ $selectedCar?->id ?? 0 }}
                    })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    }
                }).catch(error => {
                    alert('Ошибка при удалении данных');
                });
            }
        }
        
        function confirmDeleteCar() {
            if (confirm('Вы уверены? Автомобиль и все его данные будут безвозвратно удалены!')) {
                fetch('{{ route("car-settings.delete-car") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        car_id: {{ $selectedCar?->id ?? 0 }}
                    })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '{{ route("car-settings.index") }}';
                    }
                }).catch(error => {
                    alert('Ошибка при удалении автомобиля');
                });
            }
        }
    </script>
</x-app-layout>