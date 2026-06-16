<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Добавить автомобиль') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-6 sm:p-8 md:p-10 text-center">
                    <!-- Иконка -->
                        <div class="inline-block p-6 bg-blue-100 dark:bg-blue-900 rounded-full mb-6">
                        <img src="{{ asset('images/car.svg') }}" alt="Автомобиль" class="w-25 h-20">
                        </div>

                    
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white mb-2">Добавьте свой первый автомобиль</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        Введите данные вашего автомобиля, чтобы начать отслеживать расходы, заправки и напоминания
                    </p>
                    
                    <a href="{{ route('cars.create.form') }}" 
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg text-base transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить автомобиль
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>