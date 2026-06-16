<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Доходы') }}
            </h2>
            <a href="{{ route('incomes.create', ['car_id' => $carId ?? '']) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Добавить доход</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <!-- Фильтры -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                <div class="p-4 sm:p-5">
                    <form method="GET" action="{{ route('incomes-list.index') }}" class="space-y-4">
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                            <!-- Автомобиль -->
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                    Автомобиль
                                </label>
                                <div class="relative">
                                    <select name="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-lg shadow-sm text-sm pl-3 pr-8 py-2.5 appearance-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
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

                            <!-- Дата от -->
                            <div class="flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                    Дата от
                                </label>
                                <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-lg shadow-sm text-sm px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>

                            <!-- Дата до -->
                            <div class="flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                    Дата до
                                </label>
                                <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-lg shadow-sm text-sm px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>

                            <!-- Поиск -->
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                    Поиск
                                </label>
                                <input type="text" name="search" value="{{ $search ?? '' }}" 
                                       placeholder="Название, описание, категория..."
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-lg shadow-sm text-sm px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>

                            <!-- Кнопки -->
                            <div class="flex gap-2 items-end">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    Применить
                                </button>
                                <a href="{{ route('incomes-list.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition px-3 py-2.5">
                                    Сбросить
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Уведомление об успехе -->
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6 text-sm sm:text-base">
                    {{ session('success') }}
                </div>
            @endif

            @if($incomes->isEmpty())
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-12 text-center">
                        <p class="text-gray-500 dark:text-gray-400 font-medium mb-2">Нет данных о доходах</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-6">Добавьте первый доход для вашего автомобиля</p>
                        <a href="{{ route('incomes.create', ['car_id' => $carId ?? '']) }}" 
                           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                            <span>Добавить доход</span>
                        </a>
                    </div>
                </div>
            @else
                <!-- Десктопная таблица -->
                <div class="hidden md:block bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-[#1a1a1a] border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Автомобиль</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Название</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Категория</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сумма</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($incomes as $income)
                                    <tr class="hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition-all duration-200">
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                            {{ $income->date->format('d.m.Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $income->car->brand }} {{ $income->car->model }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-green-600 dark:text-green-400">
                                            <a href="{{ route('incomes.show', $income) }}" class="hover:underline">
                                                {{ $income->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-gray-600 dark:text-gray-300">
                                                @switch($income->category)
                                                    @case('salary') Зарплата @break
                                                    @case('business') Бизнес @break
                                                    @case('gift') Подарок @break
                                                    @case('refund') Возврат @break
                                                    @default Прочее
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400 text-right whitespace-nowrap">
                                            +{{ number_format($income->converted_amount, 2) }} {{ $income->currency }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 text-right whitespace-nowrap">
                                            {{ number_format($income->converted_odometer) }} {{ $income->distance_unit }}
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('incomes.show', $income) }}" 
                                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                                    Просмотр
                                                </a>
                                                <form action="{{ route('incomes-list.destroy', $income) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот доход?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Мобильные карточки -->
                <div class="block md:hidden space-y-4">
                    @foreach($incomes as $income)
                        <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl p-4">
                            <div class="flex justify-between items-start mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $income->date->format('d.m.Y') }}
                                </span>
                                <span class="text-base font-bold text-green-600 dark:text-green-400">
                                    +{{ number_format($income->converted_amount, 2) }} {{ $income->currency }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <a href="{{ route('incomes.show', $income) }}" class="text-sm font-semibold text-green-600 dark:text-green-400 hover:underline">
                                    {{ $income->title }}
                                </a>
                            </div>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $income->car->brand }} {{ $income->car->model }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-3 p-2 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Категория</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                        @switch($income->category)
                                            @case('salary') Зарплата @break
                                            @case('business') Бизнес @break
                                            @case('gift') Подарок @break
                                            @case('refund') Возврат @break
                                            @default Прочее
                                        @endswitch
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Пробег</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ number_format($income->converted_odometer) }} {{ $income->distance_unit }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($income->description)
                                <div class="mb-3 p-2 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Описание</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 break-words">
                                        {{ $income->description }}
                                    </p>
                                </div>
                            @endif
                            
                            <div class="flex gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('incomes.show', $income) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition">
                                    Просмотр
                                </a>
                                <form action="{{ route('incomes-list.destroy', $income) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот доход?')" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition cursor-pointer">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Пагинация -->
                @if($incomes->hasPages())
                    <div class="mt-6 bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                        <div class="px-4 sm:px-6 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                                    Показано с {{ $incomes->firstItem() }} по {{ $incomes->lastItem() }} 
                                    из {{ $incomes->total() }} доходов
                                </div>
                                
                                <div class="flex items-center justify-center gap-1 overflow-x-auto pb-1 sm:pb-0">
                                    @if($incomes->onFirstPage())
                                        <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">Назад</span>
                                    @else
                                        <a href="{{ $incomes->previousPageUrl() }}" class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">Назад</a>
                                    @endif
                                    
                                    <div class="flex gap-0.5 sm:gap-1">
                                        @foreach($incomes->getUrlRange(1, $incomes->lastPage()) as $page => $url)
                                            @php $currentPage = $incomes->currentPage(); $lastPage = $incomes->lastPage(); @endphp
                                            @if($page == $currentPage)
                                                <span class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium bg-green-600 text-white shadow-sm text-center">{{ $page }}</span>
                                            @elseif($page == 1 || $page == $lastPage || ($page >= $currentPage - 2 && $page <= $currentPage + 2))
                                                <a href="{{ $url }}" class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">{{ $page }}</a>
                                            @elseif($page == $currentPage - 3 || $page == $currentPage + 3)
                                                <span class="px-1 sm:px-2 py-1.5 sm:py-2 text-gray-500 dark:text-gray-400 text-xs sm:text-sm">...</span>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    @if($incomes->hasMorePages())
                                        <a href="{{ $incomes->nextPageUrl() }}" class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">Вперед</a>
                                    @else
                                        <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed whitespace-nowrap">Вперед</span>
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