<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Профиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Блок с фото и именем по центру -->
            <div class="text-center mb-8">
                <div class="relative inline-block group cursor-pointer" onclick="document.getElementById('avatar_input').click()">
                    <!-- Круглая рамка для фото -->
                    <div class="w-32 h-32 rounded-full border-4 border-blue-500 dark:border-blue-400 overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800 mx-auto">
                        @if(Auth::user()->avatar)
                            <img id="avatar_preview" src="{{ Storage::url(Auth::user()->avatar) }}" 
                                 alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div id="avatar_preview" class="w-full h-full flex items-center justify-center text-4xl font-bold text-gray-500 dark:text-gray-400 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-gray-700 dark:to-gray-800">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <!-- Иконка камеры при наведении -->
                    <div class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                <p class="text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
            </div>

            <!-- Форма загрузки аватара (AJAX) -->
            <form id="avatar_form" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('PATCH')
                <input type="file" name="avatar" id="avatar_input" accept="image/jpeg,image/png,image/jpg">
            </form>

            <script>
                document.getElementById('avatar_input').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    const formData = new FormData();
                    formData.append('avatar', file);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PATCH');
                    
                    // Показываем превью сразу
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.getElementById('avatar_preview');
                        if (preview.tagName === 'IMG') {
                            preview.src = event.target.result;
                        } else {
                            const newImg = document.createElement('img');
                            newImg.id = 'avatar_preview';
                            newImg.src = event.target.result;
                            newImg.className = 'w-full h-full object-cover';
                            preview.parentNode.replaceChild(newImg, preview);
                        }
                    }
                    reader.readAsDataURL(file);
                    
                    // Отправляем AJAX запрос
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
                            showNotification('Аватар обновлён!', 'success');
                        } else {
                            showNotification('Ошибка при загрузке: ' + (data.message || 'неизвестная ошибка'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Ошибка при загрузке фото', 'error');
                    });
                });
                
                function showNotification(message, type) {
                    const notification = document.createElement('div');
                    notification.textContent = message;
                    notification.className = 'fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg text-white ' + 
                        (type === 'success' ? 'bg-green-500' : 'bg-red-500');
                    notification.style.animation = 'fadeInOut 2s ease-in-out';
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), 2000);
                }
                
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes fadeInOut {
                        0% { opacity: 0; transform: translateY(-20px); }
                        15% { opacity: 1; transform: translateY(0); }
                        85% { opacity: 1; transform: translateY(0); }
                        100% { opacity: 0; transform: translateY(-20px); }
                    }
                `;
                document.head.appendChild(style);
            </script>

            <!-- Два блока в ряд (информация + пароль) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Обновление информации профиля -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            {{ __('Информация профиля') }}
                        </h3>
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label for="name" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">{{ __('Имя') }}</label>
                                <input id="name" name="name" type="text" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="email" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">{{ __('Email') }}</label>
                                <input id="email" name="email" type="email" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end gap-4 mt-6">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Сохранить') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Обновление пароля -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            {{ __('Сменить пароль') }}
                        </h3>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label for="current_password" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">{{ __('Текущий пароль') }}</label>
                                <input id="current_password" name="current_password" type="password" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="password" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">{{ __('Новый пароль') }}</label>
                                <input id="password" name="password" type="password" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="password_confirmation" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">{{ __('Подтверждение пароля') }}</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                            </div>

                            <div class="flex items-center justify-end gap-4 mt-6">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('Сменить пароль') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Удаление аккаунта (на всю ширину) -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-red-600 dark:text-red-400 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        {{ __('Удалить аккаунт') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('После удаления аккаунта все данные будут безвозвратно удалены.') }}
                    </p>
                    <button onclick="openDeleteModal()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Удалить аккаунт') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-[#222222] rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Подтверждение удаления') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Вы уверены, что хотите удалить свой аккаунт? Это действие необратимо.') }}
                </p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeDeleteModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Отмена') }}
                    </button>
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Удалить') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</x-app-layout>