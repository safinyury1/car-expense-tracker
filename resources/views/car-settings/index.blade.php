<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Настройки авто') }}
            </h2>
            <div class="flex gap-2 w-full sm:w-auto">
                <a href="{{ route('settings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm transition text-center whitespace-nowrap flex-1 sm:flex-none">
                    Назад
                </a>
                <div class="relative flex-1 sm:flex-none sm:w-64">
                    <select id="carSelect" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm text-xs sm:text-sm pl-2 sm:pl-3 pr-6 sm:pr-8 py-2 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer">
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ $selectedCar?->id == $car->id ? 'selected' : '' }}>
                                {{ $car->brand }} {{ $car->model }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-1.5 sm:pr-2 pointer-events-none">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            @if($selectedCar)
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    
                    <!-- Единица расстояния -->
                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col gap-3">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">Единица расстояния</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Километры (км) или Мили (mi)</p>
                            </div>
                            <form action="{{ route('car-settings.distance-unit') }}" method="POST" class="flex flex-wrap gap-2">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $selectedCar->id }}">
                                <button type="submit" name="distance_unit" value="km" class="px-4 py-1.5 rounded-lg text-sm font-medium transition {{ $selectedCar->distance_unit === 'km' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                    км
                                </button>
                                <button type="submit" name="distance_unit" value="miles" class="px-4 py-1.5 rounded-lg text-sm font-medium transition {{ $selectedCar->distance_unit === 'miles' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                    мили
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Единица объема -->
                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col gap-3">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">Единица объема</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Литры (л) или Галлоны (gal)</p>
                            </div>
                            <form action="{{ route('car-settings.volume-unit') }}" method="POST" class="flex flex-wrap gap-2">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $selectedCar->id }}">
                                <button type="submit" name="volume_unit" value="liters" class="px-4 py-1.5 rounded-lg text-sm font-medium transition {{ $selectedCar->volume_unit === 'liters' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                    литры
                                </button>
                                <button type="submit" name="volume_unit" value="gallons" class="px-4 py-1.5 rounded-lg text-sm font-medium transition {{ $selectedCar->volume_unit === 'gallons' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                    галлоны
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Валюта -->
                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col gap-3">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">Валюта</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Выберите валюту для отображения</p>
                            </div>
                            <form action="{{ route('car-settings.currency') }}" method="POST" class="flex flex-wrap gap-2">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $selectedCar->id }}">
                                <button type="submit" name="currency" value="RUB" class="px-4 py-1.5 rounded-lg text-sm font-medium transition {{ $selectedCar->currency === 'RUB' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                    RUB ₽
                                </button>
                                <button type="submit" name="currency" value="USD" class="px-4 py-1.5 rounded-lg text-sm font-medium transition {{ $selectedCar->currency === 'USD' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                    USD $
                                </button>
                                <button type="submit" name="currency" value="EUR" class="px-4 py-1.5 rounded-lg text-sm font-medium transition {{ $selectedCar->currency === 'EUR' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                    EUR €
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Категории -->
                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                        <a href="{{ route('categories.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">Категории</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Управление категориями расходов</p>
                            </div>
                            <span class="text-blue-600 dark:text-blue-400 text-sm font-medium">
                                Настроить
                            </span>
                        </a>
                    </div>

                    <!-- Удалить все данные -->
                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col gap-3">
                            <div>
                                <p class="font-medium text-red-600 dark:text-red-400">Удалить все данные</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Очистить расходы, заправки и напоминания</p>
                            </div>
                            <form action="{{ route('car-settings.delete-all') }}" method="POST" onsubmit="return confirm('Вы уверены? Все расходы, заправки и напоминания этого автомобиля будут удалены!')">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $selectedCar->id }}">
                                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition">
                                    Очистить все данные
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Удалить автомобиль -->
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col gap-3">
                            <div>
                                <p class="font-medium text-red-600 dark:text-red-400">Удалить автомобиль</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Полностью удалить автомобиль со всеми данными</p>
                            </div>
                            <form action="{{ route('car-settings.delete-car') }}" method="POST" onsubmit="return confirm('Вы уверены? Автомобиль и все его данные будут безвозвратно удалены!')">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $selectedCar->id }}">
                                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                                    Удалить автомобиль
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-12 text-center">
                        <p class="text-gray-500 dark:text-gray-400 font-medium mb-2">У вас нет автомобилей</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-6">Добавьте автомобиль в разделе "Мои автомобили"</p>
                        <a href="{{ route('cars.index') }}" class="inline-block px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                            Перейти в гараж
                        </a>
                    </div>
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
    </script>
</x-app-layout>