<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Добавить заправку') }}
            </h2>
            <a href="{{ route('refuelings.index', ['car_id' => old('car_id', $selectedCar)]) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 sm:px-5 py-2.5 rounded-lg text-sm sm:text-base flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Список истории заправок</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    <form action="{{ route('refuelings.store') }}" method="POST" class="space-y-6 sm:space-y-7">
                        @csrf

                        <!-- Автомобиль -->
                        <div>
                            <label for="car_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Автомобиль <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="car_id" id="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm text-base pl-4 pr-10 py-3 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('car_id') border-red-500 @enderror" required>
                                    <option value="">Выберите автомобиль</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}" {{ (old('car_id', $selectedCar) == $car->id) ? 'selected' : '' }}
                                                data-odometer="{{ $lastOdometerByCar[$car->id] ?? '' }}">
                                            {{ $car->brand }} {{ $car->model }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('car_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Дата -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Дата <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror" required>
                            @error('date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <!-- Литры -->
                            <div>
                                <label for="liters" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Литры <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="liters" id="liters" value="{{ old('liters') }}" step="0.01" min="0" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('liters') border-red-500 @enderror" required
                                       placeholder="0.00">
                                @error('liters')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Цена за литр -->
                            <div>
                                <label for="price_per_liter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Цена за литр <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="price_per_liter" id="price_per_liter" value="{{ old('price_per_liter') }}" step="0.01" min="0" 
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price_per_liter') border-red-500 @enderror" required
                                           placeholder="0.00">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">₽</span>
                                </div>
                                @error('price_per_liter')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Пробег (необязательный) - АВТОМАТИЧЕСКИ ЗАПОЛНЯЕТСЯ -->
                        <div>
                            <label for="odometer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Пробег <span class="text-gray-400 text-xs font-normal">(необязательно)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="odometer" id="odometer" value="{{ old('odometer', $lastOdometer ?? '') }}" min="0" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('odometer') border-red-500 @enderror"
                                       placeholder="0">
                                <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">{{ $selectedCar ? ($cars->firstWhere('id', $selectedCar)->distance_unit ?? 'км') : 'км' }}</span>
                            </div>
                            @if(isset($maxOdometer) && $maxOdometer > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" id="lastOdometerText">
                                    Последний зафиксированный пробег: <span class="font-medium">{{ number_format($maxOdometer) }}</span> {{ $selectedCar ? ($cars->firstWhere('id', $selectedCar)->distance_unit ?? 'км') : 'км' }}
                                </p>
                            @endif
                            @error('odometer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- АЗС -->
                        <div>
                            <label for="gas_station" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                АЗС
                            </label>
                            <input type="text" name="gas_station" id="gas_station" value="{{ old('gas_station') }}" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gas_station') border-red-500 @enderror"
                                   placeholder="Название АЗС">
                            @error('gas_station')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопки -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('refuelings.index', ['car_id' => old('car_id', $selectedCar)]) }}" 
                               class="px-5 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                                Отмена
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                Сохранить заправку
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carSelect = document.getElementById('car_id');
            const odometerInput = document.getElementById('odometer');
            const lastOdometerText = document.getElementById('lastOdometerText');
            
            function updateOdometer() {
                const selectedOption = carSelect.options[carSelect.selectedIndex];
                const odometerValue = selectedOption.getAttribute('data-odometer');
                
                if (odometerValue && odometerValue !== '') {
                    odometerInput.value = odometerValue;
                    if (lastOdometerText) {
                        const formatted = new Intl.NumberFormat('ru-RU').format(odometerValue);
                        lastOdometerText.innerHTML = 'Последний зафиксированный пробег: <span class="font-medium">' + formatted + '</span> км';
                    }
                } else {
                    odometerInput.value = '';
                    if (lastOdometerText) {
                        lastOdometerText.innerHTML = 'Последний зафиксированный пробег: <span class="font-medium">0</span> км';
                    }
                }
            }
            
            carSelect.addEventListener('change', updateOdometer);
            
            if (carSelect.value) {
                updateOdometer();
            }
        });
    </script>
</x-app-layout>