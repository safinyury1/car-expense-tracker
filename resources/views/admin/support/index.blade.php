<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Обращения в поддержку') }}
                @if($pendingCount > 0)
                    <span class="ml-2 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full">{{ $pendingCount }}</span>
                @endif
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">
                Всего: {{ $messages->total() }}
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

                    <!-- Десктопная таблица -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пользователь</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Тема</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Статус</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($messages as $message)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition {{ !$message->is_read ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                                        <td class="px-4 py-3 text-sm">
                                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ $message->user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->user->email }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $message->subject }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                                                {{ $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                                   ($message->status === 'answered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                                   'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                {{ $message->status === 'pending' ? 'Ожидает' : 
                                                   ($message->status === 'answered' ? 'Отвечено' : 'Закрыто') }}
                                            </span>
                                            @if(!$message->is_read && $message->status === 'pending')
                                                <span class="ml-1 px-1 py-0.5 bg-red-500 text-white text-[10px] rounded-full">new</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $message->created_at->format('d.m.Y H:i') }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.support.show', $message->id) }}" 
                                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs transition">
                                                    {{ $message->status === 'pending' ? 'Ответить' : 'Просмотр' }}
                                                </a>
                                                <form action="{{ route('admin.support.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Удалить сообщение?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs transition cursor-pointer">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Нет обращений
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Мобильные карточки -->
                    <div class="block md:hidden space-y-4">
                        @forelse($messages as $message)
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 {{ !$message->is_read && $message->status === 'pending' ? 'border-l-4 border-blue-500' : '' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $message->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->user->email }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                                        {{ $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                           ($message->status === 'answered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                           'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                        {{ $message->status === 'pending' ? 'Ожидает' : 
                                           ($message->status === 'answered' ? 'Отвечено' : 'Закрыто') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">{{ $message->subject }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $message->created_at->format('d.m.Y H:i') }}</p>
                                <div class="flex gap-2 mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('admin.support.show', $message->id) }}" 
                                       class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-3 py-1.5 rounded-lg text-xs transition">
                                        {{ $message->status === 'pending' ? 'Ответить' : 'Просмотр' }}
                                    </a>
                                    <form action="{{ route('admin.support.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Удалить сообщение?')" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs transition cursor-pointer">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">Нет обращений</div>
                        @endforelse
                    </div>
                    
                    <div class="mt-6">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>