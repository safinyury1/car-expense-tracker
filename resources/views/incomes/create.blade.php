<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Добавить доход') }}
            </h2>
            <a href="{{ route('incomes-list.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 sm:px-5 py-2.5 rounded-xl text-sm sm:text-base flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Список истории доходов</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 md:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 md:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <form action="{{ route('incomes.store') }}" method="POST" class="space-y-0">
                    @csrf
                    
                    <div class="p-5 sm:p-7 md:p-8 space-y-5 sm:space-y-6">
                        <!-- Автомобиль -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Автомобиль <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none cursor-pointer" required>
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
                            @error('car_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Категория -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Категория <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="category" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-green-500 focus:border-transparent appearance-none cursor-pointer" required>
                                    <option value="salary" {{ old('category') == 'salary' ? 'selected' : '' }}>Зарплата</option>
                                    <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Бизнес</option>
                                    <option value="gift" {{ old('category') == 'gift' ? 'selected' : '' }}>Подарок</option>
                                    <option value="refund" {{ old('category') == 'refund' ? 'selected' : '' }}>Возврат</option>
                                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Прочее</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Название -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   placeholder="Например: Зарплата, Премия, Кэшбэк..."
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <!-- Дата -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Дата <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('date') border-red-500 @enderror">
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Сумма -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Сумма <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="amount" step="0.01" value="{{ old('amount') }}" required
                                           placeholder="0.00"
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">₽</span>
                                </div>
                                @error('amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Пробег -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Пробег
                            </label>
                            <div class="relative">
                                <input type="number" name="odometer" value="{{ old('odometer') }}"
                                       placeholder="Текущий пробег"
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('odometer') border-red-500 @enderror">
                                <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">км</span>
                            </div>
                            @if(isset($maxOdometer) && $maxOdometer > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Последний зафиксированный пробег: <span class="font-medium">{{ number_format($maxOdometer) }}</span> км
                                </p>
                            @endif
                            @error('odometer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Описание -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание
                            </label>
                            <textarea name="description" rows="3"
                                      placeholder="Дополнительная информация..."
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="px-5 sm:px-7 md:px-8 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('incomes-list.index') }}" 
                           class="px-5 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                            Отмена
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl text-base font-medium text-white bg-green-600 hover:bg-green-700 transition shadow-sm">
                            Добавить доход
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>