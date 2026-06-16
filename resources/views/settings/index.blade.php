<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Настройки') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <!-- Профиль -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                        <div class="relative">
                            <div id="avatarContainer">
                                @if(Auth::user()->avatar)
                                    <img id="avatarPreview" src="{{ Storage::url(Auth::user()->avatar) }}" 
                                         class="w-20 sm:w-24 h-20 sm:h-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                @else
                                    <div id="avatarPreview" class="w-20 sm:w-24 h-20 sm:h-24 rounded-full bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400 dark:text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Нет фото</span>
                                    </div>
                                @endif
                            </div>
                            <button onclick="document.getElementById('avatarInput').click()" 
                                    class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-1.5 shadow-md transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ Auth::user()->email }}</p>
                            <div class="flex gap-3 mt-3">
                                <a href="{{ route('profile.edit') }}" class="text-sm bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-1.5 rounded-lg transition">
                                    Редактировать профиль
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="avatarForm" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/jpg" class="hidden">
            </form>

            <!-- Управление автомобилями -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white text-base sm:text-lg">Управление автомобилями</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Настройки авто</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Единицы измерения, валюта, категории</p>
                        </div>
                        <a href="{{ route('car-settings.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center gap-1">
                            <span>Перейти</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-[#E5E7EB] dark:hover:bg-[#1D1D1D] transition">
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Мой гараж</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Список ваших автомобилей</p>
                        </div>
                        <a href="{{ route('cars.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center gap-1">
                            <span>Перейти</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Внешний вид -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl mb-6">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white text-base sm:text-lg">Внешний вид</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Тёмная тема</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Переключение между светлой и тёмной темой</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="themeToggle" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Помощь -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white text-base sm:text-lg">Помощь</h3>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('guide.index') }}" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Руководство пользователя</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <button onclick="openSupportModal()" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition w-full text-left">
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Связаться с поддержкой</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно поддержки -->
    <div id="supportModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-[#222222] rounded-xl shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Связаться с поддержкой</h3>
                    <button onclick="closeSupportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Контакты -->
                <div class="space-y-3 mb-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Email для связи</p>
                            <a href="mailto:support@autocost.ru" class="text-blue-600 dark:text-blue-400 font-medium text-sm break-all">autocost774@gmail.com</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Telegram</p>
                            <a href="https://t.me/autocost_support" target="_blank" class="text-blue-600 dark:text-blue-400 font-medium text-sm break-all">@autocost_support</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Телефон</p>
                            <a href="tel:+79991234567" class="text-blue-600 dark:text-blue-400 font-medium text-sm">+7 (999) 123-45-67</a>
                        </div>
                    </div>
                </div>
                
                <!-- Форма отправки сообщения -->
                <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                    <h4 class="font-medium text-gray-800 dark:text-white text-sm mb-3">Написать сообщение</h4>
                    
                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 rounded-lg mb-3 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 rounded-lg mb-3 text-sm">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    
                    <form action="{{ route('support.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="subject" placeholder="Тема обращения" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="mb-3">
                            <textarea name="message" rows="4" placeholder="Опишите вашу проблему..." 
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg text-sm transition">
                            Отправить сообщение
                        </button>
                    </form>
                </div>
                
                <!-- Ссылка на историю сообщений -->
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 text-center">
                    <a href="{{ route('support.user.index') }}" class="text-xs text-blue-500 hover:text-blue-600 transition">
                        Просмотреть историю обращений
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Уведомление -->
    <div id="notification" class="fixed bottom-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white bg-green-500 hidden transition-all">
        <span id="notificationMessage"></span>
    </div>

    <script>
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const messageSpan = document.getElementById('notificationMessage');
            messageSpan.textContent = message;
            notification.classList.remove('hidden', 'bg-green-500', 'bg-red-500');
            if (type === 'success') {
                notification.classList.add('bg-green-500');
            } else {
                notification.classList.add('bg-red-500');
            }
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const container = document.getElementById('avatarContainer');
                const newImg = document.createElement('img');
                newImg.id = 'avatarPreview';
                newImg.src = event.target.result;
                newImg.className = 'w-20 sm:w-24 h-20 sm:h-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm';
                container.innerHTML = '';
                container.appendChild(newImg);
            }
            reader.readAsDataURL(file);
            
            fetch('{{ route("profile.avatar.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Фото профиля обновлено!', 'success');
                } else {
                    showNotification('Ошибка при загрузке фото', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка при загрузке фото', 'error');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            if (!themeToggle) return;
            
            const isDark = document.documentElement.classList.contains('dark');
            themeToggle.checked = isDark;
            
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
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
                    localStorage.setItem('darkMode', 'false');
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
        
        function openSupportModal() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        
        function closeSupportModal() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</x-app-layout>