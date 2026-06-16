<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Управление пользователями') }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">
                Всего: {{ $users->total() }}
            </span>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-4 sm:p-6">
                    
                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 sm:p-4 rounded-lg mb-4 text-sm sm:text-base">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 sm:p-4 rounded-lg mb-4 text-sm sm:text-base">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <!-- ФИЛЬТР И ПОИСК -->
                    <form method="GET" action="{{ route('admin.users') }}" class="mb-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Поиск -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                    Поиск
                                </label>
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search ?? '' }}" 
                                       placeholder="Поиск по имени или email..."
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <!-- Роль -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                    Роль
                                </label>
                                <div class="relative">
                                    <select name="role" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none pr-8">
                                        <option value="all" {{ ($role ?? 'all') == 'all' ? 'selected' : '' }}>Все роли</option>
                                        <option value="user" {{ ($role ?? '') == 'user' ? 'selected' : '' }}>Пользователи</option>
                                        <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Администраторы</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Кнопки -->
                            <div class="flex items-end gap-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl text-sm transition shadow-sm">
                                    Применить
                                </button>
                                
                                @if($search || ($role && $role !== 'all'))
                                    <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2.5 px-5 rounded-xl text-sm transition">
                                        Сбросить
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    
                    <!-- ДЕСКТОПНАЯ ТАБЛИЦА (видна на экранах > 768px) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Имя</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Роль</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата регистрации</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer" onclick="window.location.href='{{ route('admin.user.show', $user->id) }}'">
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $user->id }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-blue-600 dark:text-blue-400">{{ $user->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                {{ $user->role === 'admin' ? 'Админ' : 'Пользователь' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('d.m.Y') }}</td>
                                        <td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
                                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                                @if($user->role !== 'admin')
                                                    <a href="{{ route('admin.make.admin', $user->id) }}" 
                                                       class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                                        Сделать админом
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.make.user', $user->id) }}" 
                                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                                        Снять права
                                                    </a>
                                                @endif
                                                @if(Auth::id() !== $user->id)
                                                    <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Удалить пользователя? Все его данные будут удалены!')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer">
                                                            Удалить
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            @if($search)
                                                Пользователи не найдены по запросу "{{ $search }}"
                                            @else
                                                Нет зарегистрированных пользователей
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- МОБИЛЬНЫЕ КАРТОЧКИ (видны на экранах < 768px) -->
                    <div class="block md:hidden space-y-4">
                        @forelse($users as $user)
                            <div class="bg-white dark:bg-[#222222] border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition cursor-pointer" onclick="window.location.href='{{ route('admin.user.show', $user->id) }}'">
                                <!-- Верхняя строка: ID + Роль -->
                                <div class="flex justify-between items-start mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $user->id }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $user->role === 'admin' ? 'Админ' : 'Пользователь' }}
                                    </span>
                                </div>
                                
                                <!-- Имя -->
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $user->name }}</span>
                                </div>
                                
                                <!-- Email -->
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</span>
                                </div>
                                
                                <!-- Дата -->
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">Дата регистрации: {{ $user->created_at->format('d.m.Y') }}</span>
                                </div>
                                
                                <!-- Кнопки действий -->
                                <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100 dark:border-gray-700" onclick="event.stopPropagation()">
                                    @if($user->role !== 'admin')
                                        <a href="{{ route('admin.make.admin', $user->id) }}" 
                                           class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center px-3 py-2 rounded-lg text-xs font-medium transition">
                                            Сделать админом
                                        </a>
                                    @else
                                        <a href="{{ route('admin.make.user', $user->id) }}" 
                                           class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center px-3 py-2 rounded-lg text-xs font-medium transition">
                                            Снять права
                                        </a>
                                    @endif
                                    @if(Auth::id() !== $user->id)
                                        <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Удалить пользователя? Все его данные будут удалены!')" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-medium transition cursor-pointer">
                                                Удалить
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                @if($search)
                                    Пользователи не найдены по запросу "{{ $search }}"
                                @else
                                    Нет зарегистрированных пользователей
                                @endif
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- ПАГИНАЦИЯ -->
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .admin-table tbody tr {
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        
        .admin-table tbody tr:hover {
            background-color: #f3f4f6 !important;
        }
        
        .dark .admin-table tbody tr:hover {
            background-color: #374151 !important;
        }
        
        .admin-table tbody tr:active {
            outline: none;
        }
    </style>
</x-app-layout>