<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Управление пользователями') }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Всего: {{ $users->total() }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <!-- ФИЛЬТР И ПОИСК -->
                    <form method="GET" action="{{ route('admin.users') }}" class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 flex gap-2">
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search ?? '' }}" 
                                       placeholder="Поиск по имени или email..."
                                       class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                            </div>
                            
                            <div class="flex gap-2">
                                <select name="role" class="border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                    <option value="all" {{ ($role ?? 'all') == 'all' ? 'selected' : '' }}>Все роли</option>
                                    <option value="user" {{ ($role ?? '') == 'user' ? 'selected' : '' }}>Пользователи</option>
                                    <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Администраторы</option>
                                </select>
                                
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded whitespace-nowrap">
                                    Применить
                                </button>
                                
                                @if($search || ($role && $role !== 'all'))
                                    <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded whitespace-nowrap">
                                        Сбросить
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse admin-table">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-[#6B727F]">
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">ID</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Имя</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Email (логин)</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Роль</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Дата регистрации</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="border-b dark:border-gray-700 admin-row cursor-pointer" onclick="window.location.href='{{ route('admin.user.show', $user->id) }}'">
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ $user->id }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600 font-medium text-blue-600 dark:text-blue-400">{{ $user->name }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ $user->email }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600">
                                            <span class="px-2 py-1 rounded text-xs {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-gray-100 text-gray-800 dark:bg-[#6B727F] dark:text-gray-300' }}">
                                                {{ $user->role === 'admin' ? 'Админ' : 'Пользователь' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 border dark:border-gray-600">{{ $user->created_at->format('d.m.Y') }}</td>
                                        <td class="px-4 py-3 border dark:border-gray-600" onclick="event.stopPropagation()">
                                            <div class="flex gap-2 flex-wrap">
                                                @if($user->role !== 'admin')
                                                    <a href="{{ route('admin.make.admin', $user->id) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded text-sm transition">Сделать админом</a>
                                                @else
                                                    <a href="{{ route('admin.make.user', $user->id) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-sm transition">Снять права</a>
                                                @endif
                                                @if(Auth::id() !== $user->id)
                                                    <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Удалить пользователя? Все его данные будут удалены!')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm transition cursor-pointer">Удалить</button>
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
                    
                    <div class="mt-4">
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