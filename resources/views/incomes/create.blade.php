<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить доход') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('incomes.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Автомобиль</label>
                            <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm" required>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $selectedCar?->id == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория</label>
                            <select name="category" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm" required>
                                <option value="salary">Зарплата</option>
                                <option value="business">Бизнес</option>
                                <option value="gift">Подарок</option>
                                <option value="refund">Возврат</option>
                                <option value="other">Прочее</option>
                            </select>
                            @error('category')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   placeholder="Например: Зарплата, Премия, Кэшбэк..."
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сумма</label>
                            <input type="number" name="amount" step="0.01" value="{{ old('amount') }}" required
                                   placeholder="Сумма дохода"
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm @error('amount') border-red-500 @enderror">
                            @error('amount')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Пробег</label>
                            <input type="number" name="odometer" value="{{ old('odometer') }}"
                                   placeholder="Текущий пробег (опционально)"
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm @error('odometer') border-red-500 @enderror">
                            @if(isset($maxOdometer) && $maxOdometer > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Последний зафиксированный пробег: {{ number_format($maxOdometer) }} км
                                </p>
                            @endif
                            @error('odometer')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                            <textarea name="description" rows="3"
                                      placeholder="Дополнительная информация..."
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
                        <a href="{{ route('incomes-list.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                            Назад
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition">
                            Добавить доход
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>