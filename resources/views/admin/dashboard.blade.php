<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Админ-панель') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['users'] }}</p>
                    <p class="text-sm text-gray-500">Пользователей</p>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['cars'] }}</p>
                    <p class="text-sm text-gray-500">Автомобилей</p>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow p-4 text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $stats['expenses'] }}</p>
                    <p class="text-sm text-gray-500">Расходов</p>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['refuelings'] }}</p>
                    <p class="text-sm text-gray-500">Заправок</p>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['incomes'] }}</p>
                    <p class="text-sm text-gray-500">Доходов</p>
                </div>
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow p-4 text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['services'] }}</p>
                    <p class="text-sm text-gray-500">Обслуживаний</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Последние пользователи -->
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow">
                    <div class="p-4 border-b dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200">Последние пользователи</h3>
                    </div>
                    <div class="divide-y dark:divide-gray-700">
                        @foreach($recentUsers as $user)
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="font-medium">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 text-center">
                        <a href="{{ route('admin.users') }}" class="text-blue-500 hover:underline">Все пользователи →</a>
                    </div>
                </div>
                
                <!-- Последние автомобили -->
                <div class="bg-white dark:bg-[#222222] rounded-lg shadow">
                    <div class="p-4 border-b dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200">Последние автомобили</h3>
                    </div>
                    <div class="divide-y dark:divide-gray-700">
                        @foreach($recentCars as $car)
                            <div class="p-4">
                                <p class="font-medium">{{ $car->brand }} {{ $car->model }}</p>
                                <p class="text-sm text-gray-500">Владелец: {{ $car->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $car->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 text-center">
                        <a href="{{ route('admin.cars') }}" class="text-blue-500 hover:underline">Все автомобили →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>