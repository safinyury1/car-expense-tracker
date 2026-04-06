<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Редактировать напоминание') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reminders.update', $reminder) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="car_id" class="block text-gray-700 font-bold mb-2">Автомобиль *</label>
                            <select name="car_id" id="car_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Выберите автомобиль</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ old('car_id', $reminder->car_id) == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-bold mb-2">Что нужно сделать? *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $reminder->title) }}" placeholder="Пример: Замена масла" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="due_odometer" class="block text-gray-700 font-bold mb-2">Пробег для напоминания (км) *</label>
                            <input type="number" name="due_odometer" id="due_odometer" value="{{ old('due_odometer', $reminder->due_odometer) }}" min="0" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('due_odometer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="due_date" class="block text-gray-700 font-bold mb-2">Дата</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $reminder->due_date ? $reminder->due_date->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('due_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Статус</label>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_completed" id="is_completed" value="1" {{ old('is_completed', $reminder->is_completed) ? 'checked' : '' }} class="mr-2">
                                <label for="is_completed" class="text-gray-700">Выполнено</label>
                            </div>
                            @error('is_completed')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('reminders.index', ['car_id' => $reminder->car_id]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Назад</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Обновить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>