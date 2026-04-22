<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Просмотр дохода') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Название</p>
                            <p class="text-lg font-medium">{{ $income->title }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Категория</p>
                            <p class="text-lg font-medium">
                                @switch($income->category)
                                    @case('salary') Зарплата @break
                                    @case('business') Бизнес @break
                                    @case('gift') Подарок @break
                                    @case('refund') Возврат @break
                                    @default Прочее
                                @endswitch
                            </p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Автомобиль</p>
                            <p class="text-lg font-medium">{{ $income->car->brand }} {{ $income->car->model }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Дата</p>
                            <p class="text-lg font-medium">{{ $income->date->format('d.m.Y') }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Сумма</p>
                            <p class="text-lg font-medium text-green-600">+{{ number_format($income->amount, 2) }} ₽</p>
                        </div>
                        
                        @if($income->odometer)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Пробег</p>
                            <p class="text-lg font-medium">{{ number_format($income->odometer) }} км</p>
                        </div>
                        @endif
                        
                        @if($income->description)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Описание</p>
                            <p class="text-lg font-medium">{{ $income->description }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('incomes-list.index', ['car_id' => $income->car_id]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
                            Назад
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('incomes.edit', $income) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">
                                Редактировать
                            </a>
                            <form action="{{ route('incomes.destroy', $income) }}" method="POST" onsubmit="return confirm('Вы уверены?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition cursor-pointer">
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