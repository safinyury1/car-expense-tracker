<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Просмотр заправки') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Автомобиль</p>
                            <p class="text-lg font-medium">{{ $refueling->car->brand }} {{ $refueling->car->model }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Дата</p>
                            <p class="text-lg font-medium">{{ $refueling->date->format('d.m.Y') }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Литры</p>
                            <p class="text-lg font-medium">{{ number_format($refueling->liters, 2) }} л</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Цена за литр</p>
                            <p class="text-lg font-medium">{{ number_format($refueling->price_per_liter, 2) }} ₽</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Сумма</p>
                            <p class="text-lg font-medium">{{ number_format($refueling->total_amount, 2) }} ₽</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Пробег</p>
                            <p class="text-lg font-medium">{{ number_format($refueling->odometer) }} км</p>
                        </div>
                        
                        @if($refueling->gas_station)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">АЗС</p>
                            <p class="text-lg font-medium">{{ $refueling->gas_station }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('overview.index', ['car_id' => $refueling->car_id]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Назад
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('refuelings.edit', $refueling) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ✏️ Редактировать
                            </a>
                            <form action="{{ route('refuelings.destroy', $refueling) }}" method="POST" onsubmit="return confirm('Вы уверены?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    🗑️ Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>