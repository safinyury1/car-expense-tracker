<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Настройки') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Профиль -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold shadow">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <button onclick="document.getElementById('avatarInput').click()" 
                                    class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 text-white rounded-full p-1.5 shadow-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ Auth::user()->name }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 dark:text-gray-400 dark:text-gray-400">{{ Auth::user()->email }}</p>
                            <div class="flex gap-3 mt-3">
                                <a href="{{ route('profile.edit') }}" class="text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-1.5 rounded-lg transition">
                                    Редактировать профиль
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="avatarForm" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('PATCH')
                <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
            </form>

            <!-- Управление автомобилями -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white text-lg">Управление автомобилями</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Настройки авто</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400 dark:text-gray-400">Единицы измерения, валюта, категории</p>
                        </div>
                        <a href="{{ route('car-settings.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                            Перейти →
                        </a>
                    </div>
                    
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Мой гараж</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400 dark:text-gray-400">Список ваших автомобилей</p>
                        </div>
                        <a href="{{ route('cars.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                            Перейти →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Внешний вид -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white text-lg">Внешний вид</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Тёмная тема</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400 dark:text-gray-400">Переключение между светлой и тёмной темой</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="themeToggle" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Помощь -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white text-lg">Помощь</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <a href="{{ route('guide.index') }}" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <span class="text-gray-700 dark:text-gray-300">Руководство пользователя</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="#" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <span class="text-gray-700 dark:text-gray-300">Связаться с поддержкой</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            if (!themeToggle) return;
            
            const savedTheme = localStorage.getItem('theme');
            themeToggle.checked = (savedTheme === 'dark');
            
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    fetch('{{ route("settings.theme") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ theme: 'dark' })
                    });
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    fetch('{{ route("settings.theme") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ theme: 'light' })
                    });
                }
            });
        });
    </script>
</x-app-layout>