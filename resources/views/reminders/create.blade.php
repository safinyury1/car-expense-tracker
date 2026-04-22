<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить напоминание') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('reminders.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="car_id" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Автомобиль *</label>
                            <select name="car_id" id="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                                <option value="">Выберите автомобиль</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $selectedCar == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Что нужно сделать? *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Пример: Замена масла, Замена ремня ГРМ, Шиномонтаж..." class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="due_odometer" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Пробег для напоминания (км) *</label>
                            <input type="number" name="due_odometer" id="due_odometer" value="{{ old('due_odometer') }}" min="0" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Напоминание появится, когда пробег достигнет этого значения</p>
                            @error('due_odometer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="due_date" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Дата (необязательно)</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Дополнительное напоминание по дате</p>
                            @error('due_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('reminders.index', ['car_id' => $selectedCar]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Назад</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>