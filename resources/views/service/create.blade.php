<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Добавить обслуживание') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <form action="{{ route('service.store') }}" method="POST" class="space-y-0">
                    @csrf
                    
                    <div class="p-5 sm:p-7 md:p-8 space-y-5 sm:space-y-6">
                        <!-- Выбор автомобиля -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Автомобиль <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none cursor-pointer" required>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}" {{ $selectedCar?->id == $car->id ? 'selected' : '' }}>
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
                        </div>
                        
                        <!-- Тип обслуживания -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Тип обслуживания <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                   placeholder="Например: Замена масла, ТО-15, Шиномонтаж..."
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <!-- Дата -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Дата <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="service_date" value="{{ old('service_date', date('Y-m-d')) }}" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>
                            
                            <!-- Сумма -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Сумма
                                </label>
                                <div class="relative">
                                    <input type="number" name="cost" value="{{ old('cost') }}" step="0.01"
                                           placeholder="0.00"
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">₽</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Пробег -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Пробег <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="odometer" value="{{ old('odometer') }}" 
                                       placeholder="Текущий пробег"
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('odometer') border-red-500 @enderror" required>
                                <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">км</span>
                            </div>
                            @if(isset($maxOdometer) && $maxOdometer > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Последний зафиксированный пробег: <span class="font-medium">{{ number_format($maxOdometer) }}</span> км
                                </p>
                            @endif
                            @error('odometer')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Примечания -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Примечания
                            </label>
                            <textarea name="notes" rows="3" placeholder="Дополнительная информация..."
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('notes') }}</textarea>
                        </div>
                        
                        <!-- Следующее ТО (синий блок) -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 sm:p-5 border border-blue-200 dark:border-blue-800">
                            <h3 class="font-semibold text-blue-800 dark:text-blue-300 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Следующее ТО
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-blue-700 dark:text-blue-400 mb-1">
                                        Пробег для следующего ТО
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="next_due_odometer" 
                                               placeholder="Например: 15000"
                                               class="w-full border-blue-200 dark:border-blue-700 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <span class="absolute right-4 top-2.5 text-gray-500 dark:text-gray-400 text-base">км</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-blue-700 dark:text-blue-400 mb-1">
                                        Дата следующего ТО
                                    </label>
                                    <input type="date" name="next_due_date" 
                                           class="w-full border-blue-200 dark:border-blue-700 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-3">
                                Заполните, чтобы создать напоминание о следующем обслуживании
                            </p>
                        </div>
                    </div>
                    
                    <div class="px-5 sm:px-7 md:px-8 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('overview.index') }}" 
                           class="px-5 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                            Отмена
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                            Сохранить обслуживание
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>