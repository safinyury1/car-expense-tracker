<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Просмотр напоминания') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    
                    <!-- Информация -->
                    <div class="space-y-4">
                        <!-- Название -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Название</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                {{ $reminder->title }}
                            </p>
                        </div>
                        
                        <!-- Автомобиль -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Автомобиль</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                {{ $reminder->car->brand }} {{ $reminder->car->model }}
                            </p>
                        </div>
                        
                        <!-- Пробег для напоминания и Дата -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег для напоминания</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ number_format($reminder->due_odometer) }} км
                                </p>
                            </div>
                            
                            @if($reminder->due_date)
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата напоминания</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ $reminder->due_date->format('d.m.Y') }}
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Статус -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Статус</p>
                            <p class="text-lg font-semibold mt-1">
                                @if($reminder->is_completed)
                                    <span class="text-green-600 dark:text-green-400">✓ Выполнено</span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400">Ожидает выполнения</span>
                                @endif
                            </p>
                        </div>
                        
                        @if($reminder->service_type === 'service' && $reminder->service_cost > 0)
                        <!-- Стоимость обслуживания -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Стоимость обслуживания</p>
                            <p class="text-lg font-semibold text-red-600 dark:text-red-400 mt-1">
                                {{ number_format($reminder->service_cost, 2) }} ₽
                            </p>
                        </div>
                        @endif
                        
                        @if($reminder->service_notes)
                        <!-- Примечания -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Примечания</p>
                            <p class="text-base text-gray-700 dark:text-gray-300 mt-1 break-words">
                                {{ $reminder->service_notes }}
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Кнопки -->
                    <div class="flex justify-between items-center mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('overview.index', ['car_id' => $reminder->car_id]) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition">
                            Назад
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('reminders.edit', $reminder) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition">
                                Редактировать
                            </a>
                            <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это напоминание?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition cursor-pointer">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>