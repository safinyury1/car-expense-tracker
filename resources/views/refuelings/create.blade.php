<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить заправку') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('refuelings.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="car_id" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Автомобиль *</label>
                            <select name="car_id" id="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
                                <option value="">Выберите автомобиль</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $selectedCar == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Дата *</label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
                            @error('date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="liters" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Литры *</label>
                            <input type="number" name="liters" id="liters" value="{{ old('liters') }}" step="0.01" min="0" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
                            @error('liters')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="price_per_liter" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Цена за литр (₽) *</label>
                            <input type="number" name="price_per_liter" id="price_per_liter" value="{{ old('price_per_liter') }}" step="0.01" min="0" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
                            @error('price_per_liter')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="odometer" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Пробег (км) *</label>
                            <input type="number" name="odometer" id="odometer" value="{{ old('odometer') }}" min="0" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
                            @if(isset($maxOdometer) && $maxOdometer > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Последний зафиксированный пробег: {{ number_format($maxOdometer) }} км
                                </p>
                            @endif
                            @error('odometer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gas_station" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">АЗС</label>
                            <input type="text" name="gas_station" id="gas_station" value="{{ old('gas_station') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                            @error('gas_station')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('refuelings.index', ['car_id' => $selectedCar]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Назад</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>