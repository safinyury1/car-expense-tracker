<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Просмотр заправки') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    
                    <!-- Информация -->
                    <div class="space-y-4">
                        <!-- Автомобиль -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Автомобиль</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                {{ $refueling->car->brand }} {{ $refueling->car->model }}
                            </p>
                        </div>
                        
                        <!-- Дата и Сумма -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ $refueling->date->format('d.m.Y') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сумма</p>
                                <p class="text-lg font-semibold text-red-600 dark:text-red-400 mt-1">
                                    {{ number_format($refueling->converted_amount, 2) }} {{ $refueling->currency }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Литры и Цена за литр -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Литры</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ number_format($refueling->converted_liters, 2) }} {{ $refueling->volume_unit }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Цена за литр</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ number_format($refueling->converted_price, 2) }} {{ $refueling->currency }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Пробег и АЗС -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ number_format($refueling->converted_odometer) }} {{ $refueling->distance_unit }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">АЗС</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ $refueling->gas_station ?: '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Кнопки -->
                    <div class="flex justify-between items-center mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('overview.index', ['car_id' => $refueling->car_id]) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition">
                            Назад
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('refuelings.edit', $refueling) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition">
                                Редактировать
                            </a>
                            <form action="{{ route('refuelings.destroy', $refueling) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту заправку?')" class="inline">
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