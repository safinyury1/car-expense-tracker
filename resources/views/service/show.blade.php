<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Просмотр обслуживания') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Название</p>
                            <p class="text-lg font-medium">{{ $reminder->title }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Автомобиль</p>
                            <p class="text-lg font-medium">{{ $reminder->car->brand }} {{ $reminder->car->model }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Дата выполнения</p>
                            <p class="text-lg font-medium">{{ $reminder->service_date ? \Carbon\Carbon::parse($reminder->service_date)->format('d.m.Y') : '—' }}</p>
                        </div>
                        
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Пробег при выполнении</p>
                            <p class="text-lg font-medium">{{ number_format($reminder->due_odometer) }} км</p>
                        </div>
                        
                        @if($reminder->service_cost > 0)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Стоимость</p>
                            <p class="text-lg font-medium">{{ number_format($reminder->service_cost, 2) }} ₽</p>
                        </div>
                        @endif
                        
                        @if($reminder->service_notes)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Примечания</p>
                            <p class="text-lg font-medium">{{ $reminder->service_notes }}</p>
                        </div>
                        @endif
                        
                        @if($reminder->next_due_odometer || $reminder->next_due_date)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Следующее ТО</p>
                            <p class="text-lg font-medium">
                                @if($reminder->next_due_odometer) Пробег: {{ number_format($reminder->next_due_odometer) }} км @endif
                                @if($reminder->next_due_date) Дата: {{ \Carbon\Carbon::parse($reminder->next_due_date)->format('d.m.Y') }} @endif
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
    <a href="{{ route('overview.index', ['car_id' => $reminder->car_id]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
        Назад
    </a>
    <div class="flex gap-2">
        <a href="{{ route('reminders.edit', $reminder) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">
            Редактировать
        </a>
        <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" onsubmit="return confirm('Вы уверены?')">
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