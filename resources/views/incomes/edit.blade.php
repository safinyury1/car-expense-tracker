<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактировать доход') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('incomes.update', $income) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Автомобиль</label>
                            <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ old('car_id', $income->car_id) == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория</label>
                            <select name="category" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                                <option value="salary" {{ old('category', $income->category) == 'salary' ? 'selected' : '' }}>Зарплата</option>
                                <option value="business" {{ old('category', $income->category) == 'business' ? 'selected' : '' }}>Бизнес</option>
                                <option value="gift" {{ old('category', $income->category) == 'gift' ? 'selected' : '' }}>Подарок</option>
                                <option value="refund" {{ old('category', $income->category) == 'refund' ? 'selected' : '' }}>Возврат</option>
                                <option value="other" {{ old('category', $income->category) == 'other' ? 'selected' : '' }}>Прочее</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                            <input type="text" name="title" value="{{ old('title', $income->title) }}" required
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата</label>
                            <input type="date" name="date" value="{{ old('date', $income->date->format('Y-m-d')) }}" required
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сумма</label>
                            <input type="number" name="amount" step="0.01" value="{{ old('amount', $income->amount) }}" required
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Пробег</label>
                            <input type="number" name="odometer" value="{{ old('odometer', $income->odometer) }}"
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                            <textarea name="description" rows="3"
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm">{{ old('description', $income->description) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end gap-3">
                        <a href="{{ route('incomes.show', $income) }}" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">
                            Отмена
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>