<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить доход') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('incomes.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-6 space-y-5">
                        <!-- Выбор автомобиля -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Автомобиль</label>
                            <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $selectedCar?->id == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Категория дохода -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория</label>
                            <select name="category" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                                <option value="salary">💰 Зарплата</option>
                                <option value="business">🏢 Бизнес</option>
                                <option value="gift">🎁 Подарок</option>
                                <option value="refund">↩️ Возврат</option>
                                <option value="other">📦 Прочее</option>
                            </select>
                        </div>
                        
                        <!-- Название -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                            <input type="text" name="title" required
                                   placeholder="Например: Зарплата, Премия, Кэшбэк..."
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <!-- Дата -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <!-- Сумма -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сумма (₽)</label>
                            <input type="number" name="amount" step="0.01" required
                                   placeholder="Сумма дохода"
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <!-- Пробег -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Пробег (км)</label>
                            <input type="number" name="odometer"
                                   placeholder="Текущий пробег (опционально)"
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <!-- Описание -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                            <textarea name="description" rows="3"
                                      placeholder="Дополнительная информация..."
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm"></textarea>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end gap-3">
                        <a href="{{ route('overview.index') }}" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">
                            Отмена
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