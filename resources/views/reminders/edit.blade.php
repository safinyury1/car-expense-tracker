<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Редактировать напоминание') }}
            </h2>
            <a href="{{ route('reminders.index', ['car_id' => $reminder->car_id]) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Список напоминаний</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    
                    <form action="{{ route('reminders.update', $reminder) }}" method="POST" class="space-y-5 sm:space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Автомобиль -->
                        <div>
                            <label for="car_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Автомобиль <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="car_id" id="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm text-base px-4 py-3 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('car_id') border-red-500 @enderror" required>
                                    <option value="">Выберите автомобиль</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}" {{ old('car_id', $reminder->car_id) == $car->id ? 'selected' : '' }}>
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

                        <!-- Название -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Что нужно сделать? <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $reminder->title) }}" 
                                   placeholder="Например: Замена масла, ТО-15, Шиномонтаж..."
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <!-- Пробег -->
                            <div>
                                <label for="due_odometer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Пробег для напоминания <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="due_odometer" id="due_odometer" value="{{ old('due_odometer', $reminder->due_odometer) }}" min="0" 
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('due_odometer') border-red-500 @enderror" required
                                           placeholder="0">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">{{ $reminder->car->distance_unit ?? 'км' }}</span>
                                </div>
                                @error('due_odometer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Дата -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Дата <span class="text-gray-400 text-xs font-normal">(необязательно)</span>
                                </label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $reminder->due_date ? $reminder->due_date->format('Y-m-d') : '') }}" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('due_date') border-red-500 @enderror">
                                @error('due_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('reminders.index', ['car_id' => $reminder->car_id]) }}" 
                               class="px-5 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                                Отмена
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>