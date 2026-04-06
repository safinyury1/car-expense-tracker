<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Редактировать расход') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('expenses.update', $expense) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="car_id" class="block text-gray-700 font-bold mb-2">Автомобиль *</label>
                            <select name="car_id" id="car_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Выберите автомобиль</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ old('car_id', $expense->car_id) == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-gray-700 font-bold mb-2">Категория *</label>
                            <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-gray-700 font-bold mb-2">Дата *</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700 font-bold mb-2">Сумма (₽) *</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="odometer" class="block text-gray-700 font-bold mb-2">Пробег (км) *</label>
                            <input type="number" name="odometer" id="odometer" value="{{ old('odometer', $expense->odometer) }}" min="0" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('odometer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-bold mb-2">Описание</label>
                            <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $expense->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('expenses.index', ['car_id' => $expense->car_id]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Назад</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Обновить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>