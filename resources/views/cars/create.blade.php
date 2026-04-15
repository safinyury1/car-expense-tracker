<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Добавить автомобиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <!-- Иконка автомобиля (вид спереди) -->
                    <div class="inline-block p-6 bg-blue-100 rounded-full mb-6">
    <img src="{{ asset('images/car.svg') }}" class="w-19 h-20" alt="Car">
</div>
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Добавьте свой первый автомобиль</h3>
                    <p class="text-gray-500 mb-8">Введите сервисную историю, расходы и напоминания для вашего авто</p>
                    
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