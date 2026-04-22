<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактировать автомобиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('cars.update', $car) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="brand" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Марка <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand" id="brand" value="{{ old('brand', $car->brand) }}" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                                @error('brand')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="model" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Модель <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" id="model" value="{{ old('model', $car->model) }}" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                                @error('model')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="year" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Год выпуска</label>
                                <input type="number" name="year" id="year" value="{{ old('year', $car->year) }}" 
                                    min="1900" max="{{ date('Y') }}" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('year')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="vin" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">VIN-код</label>
                                <input type="text" name="vin" id="vin" value="{{ old('vin', $car->vin) }}" 
                                    maxlength="17" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('vin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="initial_odometer" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Начальный пробег (км)</label>
                                <input type="number" name="initial_odometer" id="initial_odometer" value="{{ old('initial_odometer', $car->initial_odometer) }}" 
                                    min="0" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('initial_odometer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Назад</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Обновить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>