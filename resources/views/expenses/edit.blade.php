<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Редактировать расход') }}
            </h2>
            <a href="{{ route('expenses.index', ['car_id' => $expense->car_id]) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Список истории расходов</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    
                    <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-5 sm:space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Автомобиль -->
                        <div>
                            <label for="car_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Автомобиль <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="car_id" id="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm text-base px-4 py-3 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('car_id') border-red-500 @enderror" required>
                                    <option value="">Выберите автомобиль</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}" {{ old('car_id', $expense->car_id) == $car->id ? 'selected' : '' }}>
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
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Категория <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="category_id" id="category_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm text-base px-4 py-3 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror" required>
                                    <option value="">Выберите категорию</option>
                                    
                                    @php
                                        $userCategories = $categories->where('user_id', Auth::id());
                                        $defaultCategories = $categories->where('is_default', true);
                                    @endphp
                                    
                                    @if($userCategories->count() > 0)
                                        <optgroup label="Мои категории">
                                            @foreach($userCategories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    
                                    @if($defaultCategories->count() > 0)
                                        <optgroup label="Стандартные категории">
                                            @foreach($defaultCategories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Дата -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Дата <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror" required>
                            @error('date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <!-- Сумма -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Сумма <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0" 
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror" required
                                           placeholder="0.00">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">{{ $expense->car->currency ?? '₽' }}</span>
                                </div>
                                @error('amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Пробег (необязательный) -->
                            <div>
                                <label for="odometer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Пробег <span class="text-gray-400 text-xs font-normal">(необязательно)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="odometer" id="odometer" value="{{ old('odometer', $expense->odometer) }}" min="0" 
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('odometer') border-red-500 @enderror"
                                           placeholder="0">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">{{ $expense->car->distance_unit ?? 'км' }}</span>
                                </div>
                                @if(isset($maxOdometer) && $maxOdometer > 0)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Последний зафиксированный пробег: <span class="font-medium">{{ number_format($maxOdometer) }}</span> {{ $expense->car->distance_unit ?? 'км' }}
                                    </p>
                                @endif
                                @error('odometer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Описание -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание
                            </label>
                            <textarea name="description" id="description" rows="3" 
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('description') border-red-500 @enderror"
                                      placeholder="Дополнительная информация о расходе...">{{ old('description', $expense->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопки -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('expenses.index', ['car_id' => $expense->car_id]) }}" 
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