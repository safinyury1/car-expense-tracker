<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Мои обращения') }}
            </h2>
            <a href="{{ route('settings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm transition w-full sm:w-auto text-center">
                Назад
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
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

            <div class="bg-white dark:bg-[#222222] rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($messages as $message)
                        <div class="p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex-1 cursor-pointer" onclick="window.location.href='{{ route('support.user.show', $message->id) }}'">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $message->subject }}</p>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium 
                                            {{ $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                               ($message->status === 'answered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                            {{ $message->status === 'pending' ? 'Ожидает ответа' : 
                                               ($message->status === 'answered' ? 'Отвечено' : 'Закрыто') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $message->message }}</p>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <div class="text-right text-xs text-gray-400 dark:text-gray-500">
                                        {{ $message->created_at->format('d.m.Y H:i') }}
                                    </div>
                                    @if($message->status === 'answered' || $message->status === 'closed')
                                        <form action="{{ route('support.user.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это обращение?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer">
                                                Удалить
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <p class="font-medium">Нет обращений</p>
                            <p class="text-sm mt-1">Свяжитесь с поддержкой, и ваши обращения появятся здесь</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- ПАГИНАЦИЯ -->
                @if($messages->hasPages())
                    <div class="border-t border-gray-100 dark:border-gray-700 px-4 sm:px-6 py-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                                Показано с {{ $messages->firstItem() }} по {{ $messages->lastItem() }} 
                                из {{ $messages->total() }} обращений
                            </div>
                            
                            <div class="flex items-center justify-center gap-1 overflow-x-auto pb-1 sm:pb-0">
                                @if($messages->onFirstPage())
                                    <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">Назад</span>
                                @else
                                    <a href="{{ $messages->previousPageUrl() }}" 
                                       class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">Назад</a>
                                @endif
                                
                                <div class="flex gap-0.5 sm:gap-1">
                                    @foreach($messages->getUrlRange(1, $messages->lastPage()) as $page => $url)
                                        @php
                                            $currentPage = $messages->currentPage();
                                            $lastPage = $messages->lastPage();
                                        @endphp
                                        
                                        @if($page == $currentPage)
                                            <span class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium bg-blue-600 text-white shadow-sm text-center">{{ $page }}</span>
                                        @elseif($page == 1 || $page == $lastPage || ($page >= $currentPage - 2 && $page <= $currentPage + 2))
                                            <a href="{{ $url }}" 
                                               class="min-w-[32px] sm:min-w-[40px] px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">{{ $page }}</a>
                                        @elseif($page == $currentPage - 3 || $page == $currentPage + 3)
                                            <span class="px-1 sm:px-2 py-1.5 sm:py-2 text-gray-500 dark:text-gray-400 text-xs sm:text-sm">...</span>
                                        @endif
                                    @endforeach
                                </div>
                                
                                @if($messages->hasMorePages())
                                    <a href="{{ $messages->nextPageUrl() }}" 
                                       class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition whitespace-nowrap">Вперед</a>
                                @else
                                    <span class="px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 cursor-not-allowed whitespace-nowrap">Вперед</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>