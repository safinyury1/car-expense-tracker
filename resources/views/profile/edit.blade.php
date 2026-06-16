<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Профиль') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <!-- Блок с фото и именем по центру -->
            <div class="text-center mb-8">
                <div class="relative inline-block">
                    <!-- Превью фото -->
                    <div id="photoPreview" class="{{ Auth::user()->avatar ? '' : 'hidden' }} relative group cursor-pointer" onclick="document.getElementById('avatar_input').click()">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-blue-500 dark:border-blue-400 overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800">
                            <img id="avatar_preview" 
                                 src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : '' }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Заглушка когда нет фото -->
                    <div id="photoPlaceholder" class="{{ Auth::user()->avatar ? 'hidden' : '' }} cursor-pointer" onclick="document.getElementById('avatar_input').click()">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gray-100 dark:bg-gray-800 border-4 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400 dark:text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-xs text-gray-400 dark:text-gray-500">Нет фото</span>
                        </div>
                    </div>
                    
                    <!-- Крестик удаления аватара (показывается только если есть фото) -->
                    @if(Auth::user()->avatar)
                        <button type="button" onclick="deleteAvatar()" 
                                id="deletePhotoBtn"
                                class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm shadow-md transition z-10">
                            ×
                        </button>
                    @endif
                </div>
                
                <h3 class="mt-4 text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
            </div>

            <!-- Форма загрузки аватара (AJAX) -->
            <form id="avatar_form" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('PATCH')
                <input type="file" name="avatar" id="avatar_input" accept="image/jpeg,image/png,image/jpg">
            </form>

            <!-- Два блока в ряд -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
                <!-- Обновление информации профиля -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-5 sm:p-6">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Информация профиля
                        </h3>
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Имя</label>
                                <input id="name" name="name" type="text" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input id="email" name="email" type="email" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl text-sm transition shadow-sm">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Обновление пароля -->
                <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                    <div class="p-5 sm:p-6">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Сменить пароль
                        </h3>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущий пароль</label>
                                <input id="current_password" name="current_password" type="password" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Новый пароль</label>
                                <input id="password" name="password" type="password" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Подтверждение пароля</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl text-sm transition shadow-sm">
                                    Сменить пароль
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Удаление аккаунта -->
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-red-600 dark:text-red-400 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Удалить аккаунт
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        После удаления аккаунта все данные будут безвозвратно удалены.
                    </p>
                    <button onclick="openDeleteModal()" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-xl text-sm transition shadow-sm">
                        Удалить аккаунт
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-[#222222] rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="p-5 sm:p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Подтверждение удаления</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Вы уверены, что хотите удалить свой аккаунт? Это действие необратимо.
                </p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeDeleteModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-xl text-sm transition">
                        Отмена
                    </button>
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-5 rounded-xl text-sm transition">
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Удаление аватара
        function deleteAvatar() {
            if (!confirm('Вы уверены, что хотите удалить фото профиля?')) return;
            
            fetch('{{ route("profile.avatar.delete") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Прячем превью, показываем заглушку
                    document.getElementById('photoPreview').classList.add('hidden');
                    document.getElementById('photoPlaceholder').classList.remove('hidden');
                    // Удаляем крестик
                    const deleteBtn = document.getElementById('deletePhotoBtn');
                    if (deleteBtn) deleteBtn.remove();
                    showNotification('Фото профиля удалено!', 'success');
                } else {
                    showNotification('Ошибка при удалении фото', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка при удалении фото', 'error');
            });
        }

        // Загрузка аватара
        document.getElementById('avatar_input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            
            const reader = new FileReader();
            reader.onload = function(event) {
                // Показываем превью, скрываем заглушку
                document.getElementById('photoPreview').classList.remove('hidden');
                document.getElementById('photoPlaceholder').classList.add('hidden');
                // Устанавливаем фото
                const previewImg = document.getElementById('avatar_preview');
                previewImg.src = event.target.result;
                // Добавляем крестик если его нет
                if (!document.getElementById('deletePhotoBtn')) {
                    const container = document.querySelector('.relative.inline-block');
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.onclick = deleteAvatar;
                    deleteBtn.id = 'deletePhotoBtn';
                    deleteBtn.className = 'absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm shadow-md transition z-10';
                    deleteBtn.innerHTML = '×';
                    container.appendChild(deleteBtn);
                }
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
                    showNotification('Аватар обновлён!', 'success');
                } else {
                    showNotification('Ошибка при загрузке фото', 'error');
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
            notification.className = 'fixed bottom-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white text-sm ' + 
                (type === 'success' ? 'bg-green-500' : 'bg-red-500');
            notification.style.animation = 'fadeInOut 2s ease-in-out';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 2000);
        }
        
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateY(20px); }
                15% { opacity: 1; transform: translateY(0); }
                85% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(20px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>