<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Категории расходов') }}
            </h2>
            <a href="{{ route('categories.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Создать категорию</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <!-- Уведомления -->
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6 text-sm sm:text-base">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6 text-sm sm:text-base">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Стандартные категории -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                            Стандартные категории
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Предустановленные категории, доступные всем пользователям</p>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($categories as $category)
                            <div class="p-4 sm:p-5 flex items-center justify-between gap-3">
                                <span class="font-medium text-gray-800 dark:text-white text-sm sm:text-base break-words">
                                    {{ $category->name }}
                                </span>
                                <span class="text-xs text-white bg-blue-500 px-2 py-1 rounded-full whitespace-nowrap">
                                    по умолчанию
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-gray-500 dark:text-gray-400">Нет стандартных категорий</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Мои категории -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                            Мои категории
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Созданные вами категории</p>
                    </div>
                    
                    @if($userCategories->isEmpty())
                        <div class="p-8 text-center">
                            <p class="text-gray-500 dark:text-gray-400 font-medium">У вас пока нет своих категорий</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Создайте первую категорию для своих расходов</p>
                            <a href="{{ route('categories.create') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Создать категорию
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($userCategories as $category)
                                <div class="p-4 sm:p-5 flex items-center justify-between gap-3 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition-all duration-200">
                                    <span class="font-medium text-gray-800 dark:text-white text-sm sm:text-base break-words">
                                        {{ $category->name }}
                                    </span>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition whitespace-nowrap">
                                            Изменить
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Удалить категорию «{{ $category->name }}»? Все расходы с этой категорией будут перемещены в категорию «Прочее».')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition cursor-pointer whitespace-nowrap">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Подсказка -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                <div class="text-sm text-blue-700 dark:text-blue-300">
                    <p class="font-medium mb-1">Информация о категориях</p>
                    <ul class="text-xs space-y-1 list-disc list-inside">
                        <li>Стандартные категории доступны всем пользователям и не могут быть изменены или удалены</li>
                        <li>Свои категории вы можете создавать, редактировать и удалять</li>
                        <li>При удалении категории, все расходы с этой категорией будут перемещены в категорию «Прочее»</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>