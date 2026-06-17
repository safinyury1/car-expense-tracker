<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Просмотр дохода') }}
            </h2>
            <a href="{{ route('incomes-list.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Список доходов</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    
                    <!-- Информация -->
                    <div class="space-y-4">
                        <!-- Автомобиль -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Автомобиль</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                {{ $income->car->brand }} {{ $income->car->model }}
                            </p>
                        </div>
                        
                        <!-- Дата и Сумма -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ $income->date->format('d.m.Y') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сумма</p>
                                <p class="text-lg font-semibold text-green-600 dark:text-green-400 mt-1">
                                    +{{ number_format($income->converted_amount, 2) }} {{ $income->currency }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Название и Категория -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Название</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    {{ $income->title }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Категория</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                    @switch($income->category)
                                        @case('salary') Зарплата @break
                                        @case('business') Бизнес @break
                                        @case('gift') Подарок @break
                                        @case('refund') Возврат @break
                                        @default Прочее
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        
                        <!-- Пробег -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пробег</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                                {{ number_format($income->converted_odometer) }} {{ $income->distance_unit }}
                            </p>
                        </div>
                        
                        <!-- Описание -->
                        @if($income->description)
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Описание</p>
                                <p class="text-base text-gray-700 dark:text-gray-300 mt-1">
                                    {{ $income->description }}
                                </p>
                            </div>
                        @endif

                        <!-- ========================================== -->
                        <!-- ВЛОЖЕНИЯ (ЧЕКИ) -->
                        <!-- ========================================== -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Прикреплённые файлы ({{ $income->attachments->count() }}/4)
                                </h4>
                                @if($income->attachments->count() < 4)
                                    <button onclick="document.getElementById('addAttachmentInput').click()" 
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Добавить файл
                                    </button>
                                @endif
                            </div>
                            
                            <form id="addAttachmentForm" action="{{ route('incomes.add-attachment', $income) }}" 
                                  method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" name="attachments[]" id="addAttachmentInput" 
                                       accept=".jpg,.jpeg,.png,.pdf" multiple>
                            </form>

                            @if($income->attachments && $income->attachments->count() > 0)
                                <div class="flex flex-wrap gap-3">
                                    @foreach($income->attachments as $attachment)
                                        @php
                                            $isImage = str_contains($attachment->file_type, 'image');
                                        @endphp
                                        <div class="relative group">
                                            @if($isImage)
                                                <button onclick="openImageModal('{{ Storage::url($attachment->file_path) }}')"
                                                        class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-blue-500 transition">
                                                    <img src="{{ Storage::url($attachment->file_path) }}" 
                                                         alt="{{ $attachment->file_name }}"
                                                         class="w-full h-full object-cover">
                                                </button>
                                            @else
                                                <a href="{{ Storage::url($attachment->file_path) }}" 
                                                   target="_blank"
                                                   class="flex items-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[150px]">{{ $attachment->file_name }}</span>
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ number_format($attachment->file_size / 1024, 1) }} KB
                                                    </span>
                                                </a>
                                            @endif
                                            <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" class="absolute -top-2 -right-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs transition shadow-md" onclick="return confirm('Удалить файл?')">
                                                    ✕
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500">Файлы не прикреплены</p>
                            @endif
                            
                            @if($income->attachments->count() >= 4)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Достигнут лимит в 4 файла</p>
                            @endif
                            
                            @if(session('attachment_success'))
                                <div class="mt-3 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-2 rounded-lg text-sm">
                                    {{ session('attachment_success') }}
                                </div>
                            @endif
                            
                            @if(session('attachment_error'))
                                <div class="mt-3 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-2 rounded-lg text-sm">
                                    {{ session('attachment_error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Кнопки -->
                    <div class="flex justify-between items-center mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('overview.index', ['car_id' => $income->car_id]) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition">
                            Назад
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('incomes.edit', $income) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition">
                                Редактировать
                            </a>
                            <form action="{{ route('incomes.destroy', $income) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот доход?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-lg text-sm transition cursor-pointer">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ ПРОСМОТРА ИЗОБРАЖЕНИЙ -->
    <div id="imageModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-[9999] p-4">
        <div class="relative max-w-4xl w-full max-h-[90vh]">
            <button onclick="closeImageModal()" 
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl transition">
                ✕
            </button>
            <img id="modalImage" src="" alt="Чек" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
            <button onclick="closeImageModal()" 
                    class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-2 rounded-lg text-sm transition">
                Закрыть
            </button>
        </div>
    </div>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.src = src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
        
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                closeImageModal();
            }
        });
        
        document.getElementById('addAttachmentInput').addEventListener('change', function(e) {
            const files = this.files;
            const currentCount = {{ $income->attachments->count() }};
            const maxFiles = 4;
            
            if (files.length > (maxFiles - currentCount)) {
                alert('Нельзя добавить больше ' + (maxFiles - currentCount) + ' файлов');
                this.value = '';
                return;
            }
            
            if (files.length > 0) {
                document.getElementById('addAttachmentForm').submit();
            }
        });
    </script>
</x-app-layout>