<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Мои автомобили') }}
            </h2>
            <a href="{{ route('cars.create.form') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Добавить автомобиль</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <!-- Уведомление об успехе -->
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6 text-sm sm:text-base">
                    {{ session('success') }}
                </div>
            @endif

            @if($cars->isEmpty())
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-12 text-center">
                        <p class="text-gray-500 dark:text-gray-400 font-medium mb-2">Нет автомобилей</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-6">Добавьте свой первый автомобиль, чтобы начать учёт расходов</p>
                        <a href="{{ route('cars.create.form') }}" 
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                            <span>Добавить автомобиль</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    @foreach($cars as $car)
                        <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl hover:shadow-md transition-all duration-200">
                            <!-- Фото автомобиля -->
                            <div class="relative h-40 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700">
                                @if($car->photo)
                                    <img src="{{ Storage::url($car->photo) }}" 
                                         class="w-full h-40 object-cover" 
                                         alt="{{ $car->brand }} {{ $car->model }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                                @else
                                    <div class="w-full h-40 flex items-center justify-center">
                                        <span class="text-gray-400 dark:text-gray-500 text-sm">Нет фото</span>
                                    </div>
                                @endif
                                
                                <!-- Год выпуска -->
                                @if($car->year)
                                    <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full">
                                        {{ $car->year }} г.
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Информация -->
                            <div class="p-4 sm:p-5">
                                <div class="mb-4">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $car->brand }} {{ $car->model }}</h3>
                                    @if($car->vin)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 break-all">VIN: {{ $car->vin }}</p>
                                    @endif
                                </div>
                                
                                <!-- Показатели -->
                                <div class="grid grid-cols-2 gap-3 mb-5 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Начальный пробег</p>
                                        <p class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">
                                            {{ number_format($car->converted_initial_odometer) }}
                                            <span class="text-xs font-normal text-gray-500">{{ $car->distance_unit }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Текущий пробег</p>
                                        <p class="text-base sm:text-lg font-semibold text-blue-600 dark:text-blue-400">
                                            {{ number_format($car->converted_current_odometer) }}
                                            <span class="text-xs font-normal text-gray-500">{{ $car->distance_unit }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Кнопки -->
                                <div class="flex gap-3">
                                    <a href="{{ route('cars.edit', $car) }}" 
                                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition text-center">
                                        Изменить
                                    </a>
                                    <form action="{{ route('cars.destroy', $car) }}" method="POST" class="flex-1" onsubmit="return confirm('Вы уверены, что хотите удалить автомобиль {{ $car->brand }} {{ $car->model }}? Все связанные расходы, заправки и напоминания также будут удалены.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition cursor-pointer">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- ПАГИНАЦИЯ -->
                @if($cars->hasPages())
                    <div class="mt-6 bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="px-4 sm:px-6 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <!-- Информация о количестве -->
                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                                    Показано с {{ $cars->firstItem() }} по {{ $cars->lastItem() }} 
                                    из {{ $cars->total() }} автомобилей
                                </div>
                                
                                <!-- Кнопки пагинации -->
                                <div class="flex items-center justify-center gap-1 overflow-x-auto pb-1 sm:pb-0">
                                    {{-- Назад --}}
                                    @if($cars->onFirstPage())
                                        <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                            Назад
                                        </span>
                                    @else
                                        <a href="{{ $cars->previousPageUrl() }}" 
                                           class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">
                                            Назад
                                        </a>
                                    @endif
                                    
                                    {{-- Номера страниц --}}
                                    <div class="flex gap-0.5 sm:gap-1">
                                        @foreach($cars->getUrlRange(1, $cars->lastPage()) as $page => $url)
                                            @php
                                                $currentPage = $cars->currentPage();
                                                $lastPage = $cars->lastPage();
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
                                    @if($cars->hasMorePages())
                                        <a href="{{ $cars->nextPageUrl() }}" 
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
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>