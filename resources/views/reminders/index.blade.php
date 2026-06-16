<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Напоминания о ТО') }}
            </h2>
            <a href="{{ route('reminders.create', ['car_id' => $carId ?? '']) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Добавить напоминание</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <!-- Фильтры -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-4 sm:mb-6">
                <div class="p-3 sm:p-4 md:p-5">
                    <form method="GET" action="{{ route('reminders.index') }}" class="space-y-3 sm:space-y-4">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <!-- Автомобиль -->
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                    Фильтр по автомобилю
                                </label>
                                <div class="relative">
                                    <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg shadow-sm text-sm pl-3 pr-8 py-2.5 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Все автомобили</option>
                                        @foreach($cars as $car)
                                            <option value="{{ $car->id }}" {{ ($carId ?? '') == $car->id ? 'selected' : '' }}>
                                                {{ $car->brand }} {{ $car->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="flex gap-2 items-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    Применить
                                </button>

                                @if(!empty($carId))
                                    <a href="{{ route('reminders.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition px-3 py-2.5">
                                        Сбросить
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Уведомление об успехе -->
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6 flex items-center gap-2 sm:gap-3 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="break-words">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Список напоминаний -->
            @if($reminders->isEmpty())
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-8 sm:p-12 text-center">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Нет добавленных напоминаний</p>
                        <p class="text-xs sm:text-sm text-gray-400 dark:text-gray-500 mt-1">Добавьте напоминание о техническом обслуживании</p>
                        <a href="{{ route('reminders.create', ['car_id' => $carId ?? '']) }}" class="inline-flex items-center gap-2 mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Создать напоминание
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($reminders as $reminder)
                            <div class="p-3 sm:p-4 md:p-5 {{ $reminder->is_completed ? 'opacity-75 bg-gray-50 dark:bg-[#1a1a1a]' : '' }}">
                                <div class="flex flex-col gap-3">
                                    <!-- Верхняя строка: статус + название -->
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                            <!-- Статус -->
                                            <form action="{{ route('reminders.toggle', $reminder) }}" method="POST" class="flex-shrink-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="group focus:outline-none">
                                                    @if($reminder->is_completed)
                                                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-green-500 flex items-center justify-center shadow-sm">
                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 group-hover:border-green-400 transition-colors"></div>
                                                    @endif
                                                </button>
                                            </form>
                                            
                                            <!-- Иконка -->
                                            <div class="hidden sm:flex flex-shrink-0">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center
                                                    {{ $reminder->is_completed ? 'bg-gray-100 dark:bg-gray-800' : 'bg-blue-100 dark:bg-blue-900/30' }}">
                                                    <svg class="w-5 h-5 {{ $reminder->is_completed ? 'text-gray-400' : 'text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <!-- Название -->
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route('reminders.show', $reminder) }}" class="hover:underline">
                                                    <p class="font-semibold text-gray-800 dark:text-white text-sm sm:text-base break-words {{ $reminder->is_completed ? 'line-through text-gray-500 dark:text-gray-400' : '' }}">
                                                        {{ $reminder->title }}
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Действия -->
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <a href="{{ route('reminders.edit', $reminder) }}" 
                                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition whitespace-nowrap">
                                                Изменить
                                            </a>
                                            <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это напоминание?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition cursor-pointer whitespace-nowrap">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Информация -->
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1.5 pl-7 sm:pl-14">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            {{ $reminder->car->brand }} {{ $reminder->car->model }}
                                        </span>
                                        
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            {{ number_format($reminder->converted_odometer) }} {{ $reminder->distance_unit }}
                                        </span>
                                        
                                        @if($reminder->due_date)
                                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $reminder->due_date->format('d.m.Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Заметки -->
                                    @if($reminder->service_notes)
                                        <div class="pl-7 sm:pl-14">
                                            <p class="text-xs text-gray-400 dark:text-gray-500 line-clamp-2 break-words bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg">
                                                {{ $reminder->service_notes }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- ПАГИНАЦИЯ -->
                    @if($reminders->hasPages())
                        <div class="border-t border-gray-100 dark:border-gray-700 px-3 sm:px-4 md:px-6 py-3 sm:py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <!-- Информация о количестве -->
                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                                    Показано с {{ $reminders->firstItem() }} по {{ $reminders->lastItem() }} 
                                    из {{ $reminders->total() }} напоминаний
                                </div>
                                
                                <!-- Кнопки пагинации -->
                                <div class="flex items-center justify-center gap-1 overflow-x-auto pb-1 sm:pb-0">
                                    {{-- Назад --}}
                                    @if($reminders->onFirstPage())
                                        <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                            Назад
                                        </span>
                                    @else
                                        <a href="{{ $reminders->previousPageUrl() }}" 
                                           class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">
                                            Назад
                                        </a>
                                    @endif
                                    
                                    {{-- Номера страниц --}}
                                    <div class="flex gap-0.5 sm:gap-1">
                                        @foreach($reminders->getUrlRange(1, $reminders->lastPage()) as $page => $url)
                                            @php
                                                $currentPage = $reminders->currentPage();
                                                $lastPage = $reminders->lastPage();
                                            @endphp
                                            
                                            @if($page == $currentPage)
                                                <span class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium bg-blue-600 text-white shadow-sm text-center">
                                                    {{ $page }}
                                                </span>
                                            @elseif($page == 1 || $page == $lastPage || ($page >= $currentPage - 2 && $page <= $currentPage + 2))
                                                <a href="{{ $url }}" 
                                                   class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                                                    {{ $page }}
                                                </a>
                                            @elseif($page == $currentPage - 3 || $page == $currentPage + 3)
                                                <span class="px-1 sm:px-2 py-1.5 sm:py-2 text-gray-500 dark:text-gray-400 text-xs sm:text-sm">...</span>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    {{-- Вперед --}}
                                    @if($reminders->hasMorePages())
                                        <a href="{{ $reminders->nextPageUrl() }}" 
                                           class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">
                                            Вперед
                                        </a>
                                    @else
                                        <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed whitespace-nowrap">
                                            Вперед
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>