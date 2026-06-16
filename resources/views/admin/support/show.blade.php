<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Обращение в поддержку') }}
            </h2>
            <a href="{{ route('admin.support.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm transition">
                Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 sm:p-4 rounded-lg mb-4 text-sm sm:text-base">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-5 sm:p-6">
                    
                    <!-- Информация -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пользователь</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $message->user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $message->user->email }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Статус</p>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                                {{ $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                   ($message->status === 'answered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                   'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                {{ $message->status === 'pending' ? 'Ожидает ответа' : 
                                   ($message->status === 'answered' ? 'Отвечено' : 'Закрыто') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Тема -->
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Тема</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $message->subject }}</p>
                    </div>
                    
                    <!-- Сообщение пользователя -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $message->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $message->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                    
                    <!-- Ответ администратора -->
                    @if($message->admin_reply)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Ваш ответ</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $message->updated_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->admin_reply }}</p>
                        </div>
                    @endif
                    
                    <!-- Форма ответа -->
                    @if($message->status !== 'closed')
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                            <h4 class="font-medium text-gray-800 dark:text-white text-sm mb-3">
                                {{ $message->admin_reply ? 'Изменить ответ' : 'Ответить пользователю' }}
                            </h4>
                            <form action="{{ route('admin.support.reply', $message->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="admin_reply" rows="4" 
                                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="Введите ответ...">{{ old('admin_reply') }}</textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
                                        {{ $message->admin_reply ? 'Отправить доп. ответ' : 'Отправить ответ' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                            Обращение закрыто
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>