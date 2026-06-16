<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Обращение в поддержку') }}
            </h2>
            <a href="{{ route('support.user.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm transition">
                Назад
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-5 sm:p-6">
                    
                    <!-- Тема -->
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Тема</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $message->subject }}</p>
                    </div>
                    
                    <!-- Статус -->
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Статус</p>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                            {{ $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                               ($message->status === 'answered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                            {{ $message->status === 'pending' ? 'Ожидает ответа' : 
                               ($message->status === 'answered' ? 'Отвечено' : 'Закрыто') }}
                        </span>
                    </div>
                    
                    <!-- Сообщение пользователя -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Вы</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $message->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                    
                    <!-- Ответ администратора -->
                    @if($message->admin_reply)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Поддержка</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $message->updated_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->admin_reply }}</p>
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-400 dark:text-gray-500 text-sm">
                            ⏳ Ожидайте ответа от поддержки
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>