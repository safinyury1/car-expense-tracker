<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Управление пользователями') }}
        </h2>
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
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-[#6B727F]">
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">ID</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Имя</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Email</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Роль</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Дата регистрации</th>
                                    <th class="px-4 py-3 text-left border dark:border-gray-600">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="border-b dark:border-gray-700 hover:bg-[#1D1D1D] dark:hover:bg-[#1D1D1D] cursor-pointer" onclick="window.location.href='{{ route('admin.user.show', $user->id) }}'">
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
                                @endforeach
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
</x-app-layout>