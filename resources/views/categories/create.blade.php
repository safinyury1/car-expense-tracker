<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Создать категорию') }}
            </h2>
            <a href="{{ route('categories.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Назад к категориям</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    
                    <!-- Ошибки -->
                    @if(session('error'))
                        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6 text-sm sm:text-base">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6 text-sm sm:text-base">
                            @foreach($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('categories.store') }}" method="POST" class="space-y-5 sm:space-y-6">
                        @csrf

                        <!-- Название категории -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название категории <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                                   required
                                   placeholder="Например: Ремонт, Страховка, Налог...">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Стандартные категории (подсказка) -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <p class="font-medium mb-1">Стандартные категории</p>
                                    <p class="text-xs">Стандартные категории (Ремонт, Страховка, Налог и др.) уже доступны в системе и не требуют создания. Вы можете создавать свои уникальные категории.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('categories.index') }}" 
                               class="px-5 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                                Отмена
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                Создать категорию
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>