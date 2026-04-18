<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить автомобиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <div class="inline-block p-6 bg-blue-100 dark:bg-blue-900 rounded-full mb-6">
                        <svg class="w-20 h-20 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M6 12l-2 5h16l-2-5M8 9h8M9 6h6M12 6v3" />
                            <rect x="7" y="14" width="2" height="2" rx="1" />
                            <rect x="15" y="14" width="2" height="2" rx="1" />
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">Добавьте свой первый автомобиль</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">Введите сервисную историю, расходы и напоминания для вашего авто</p>
                    
                    <a href="{{ route('cars.create.form') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg inline-flex items-center gap-2 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить авто
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>