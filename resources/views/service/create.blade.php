<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Добавить обслуживание') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('service.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-6 space-y-5">
                        <!-- Выбор автомобиля -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Автомобиль</label>
                            <select name="car_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ $selectedCar?->id == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Тип обслуживания -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Тип обслуживания</label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                   placeholder="Например: Замена масла, ТО-15, Шиномонтаж..."
                                   class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        
                        <!-- Дата -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Дата</label>
                            <input type="date" name="service_date" value="{{ old('service_date', date('Y-m-d')) }}" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        
                        <!-- Пробег -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Пробег (км)</label>
                            <input type="number" name="odometer" value="{{ old('odometer') }}" 
                                   placeholder="Текущий пробег"
                                   class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        
                        <!-- Сумма -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Сумма</label>
                            <input type="number" name="cost" value="{{ old('cost') }}" step="0.01"
                                   placeholder="Стоимость обслуживания"
                                   class="w-full border-gray-300 rounded-lg shadow-sm">
                        </div>
                        
                        <!-- Примечания -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Примечания</label>
                            <textarea name="notes" rows="3" placeholder="Дополнительная информация..."
                                      class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="border-t border-gray-100 my-4"></div>
                        
                        <!-- Следующее ТО -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-800 mb-3">Следующее ТО</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Следующая замена (пробег)</label>
                                    <input type="number" name="next_due_odometer" 
                                           placeholder="Пробег для следующего ТО"
                                           class="w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Следующая замена (дата)</label>
                                    <input type="date" name="next_due_date" 
                                           class="w-full border-gray-300 rounded-lg shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('overview.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
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