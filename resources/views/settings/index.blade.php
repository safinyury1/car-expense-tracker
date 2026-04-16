<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Настройки') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Профиль -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-6">
                        <!-- Аватар -->
                        <div class="relative">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white shadow">
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
                            <h3 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                            <p class="text-gray-500">{{ Auth::user()->email }}</p>
                            <div class="flex gap-3 mt-3">
                                <a href="{{ route('profile.edit') }}" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg transition">
                                    Редактировать профиль
                                </a>
                                <form action="{{ route('profile.avatar.delete') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-600 transition">
                                        Удалить фото
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Форма загрузки аватара (скрытая) -->
            <form id="avatarForm" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('PATCH')
                <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
            </form>

            <!-- Настройки аккаунта -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-lg">Настройки аккаунта</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <!-- Смена пароля -->
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-700">Смена пароля</p>
                            <p class="text-sm text-gray-500">Обновите ваш пароль для входа</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                            Изменить →
                        </a>
                    </div>
                    
                    <!-- Удалить аккаунт -->
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-red-600">Удалить аккаунт</p>
                            <p class="text-sm text-gray-500">Безвозвратное удаление всех данных</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="text-red-500 hover:text-red-600 text-sm font-medium">
                            Удалить →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Настройки авто -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-lg">Настройки авто</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-700">Управление автомобилями</p>
                            <p class="text-sm text-gray-500">Добавление, редактирование, удаление авто</p>
                        </div>
                        <a href="{{ route('cars.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                            Перейти →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Внешний вид -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-lg">Внешний вид</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <!-- Тёмная тема (заглушка) -->
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-700">Тёмная тема</p>
                            <p class="text-sm text-gray-500">Переключение между светлой и тёмной темой</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="themeToggle" class="sr-only peer" disabled>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        </label>
                    </div>
                    
                    <!-- Язык (заглушка) -->
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-700">Язык / Language</p>
                            <p class="text-sm text-gray-500">Русский / English</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-4 py-1.5 rounded-lg text-sm bg-blue-500 text-white cursor-default">
                                Русский
                            </button>
                            <button class="px-4 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-500 cursor-default">
                                English
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Помощь -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-lg">Помощь</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <a href="#" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <span class="text-gray-700">Руководство пользователя</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="#" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <span class="text-gray-700">Связаться с поддержкой</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>