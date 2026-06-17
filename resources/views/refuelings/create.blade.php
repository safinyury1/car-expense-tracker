<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Добавить заправку') }}
            </h2>
            <a href="{{ route('refuelings.index', ['car_id' => old('car_id', $selectedCar)]) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 sm:px-5 py-2.5 rounded-lg text-sm sm:text-base flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Список истории заправок</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    <form action="{{ route('refuelings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 sm:space-y-7">
                        @csrf

                        <!-- Автомобиль -->
                        <div>
                            <label for="car_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Автомобиль <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="car_id" id="car_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm text-base pl-4 pr-10 py-3 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('car_id') border-red-500 @enderror" required>
                                    <option value="">Выберите автомобиль</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}" {{ (old('car_id', $selectedCar) == $car->id) ? 'selected' : '' }}
                                                data-odometer="{{ $lastOdometerByCar[$car->id] ?? '' }}">
                                            {{ $car->brand }} {{ $car->model }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('car_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Дата -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Дата <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror" required>
                            @error('date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <!-- Литры -->
                            <div>
                                <label for="liters" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Литры <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="liters" id="liters" value="{{ old('liters') }}" step="0.01" min="0" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('liters') border-red-500 @enderror" required
                                       placeholder="0.00">
                                @error('liters')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Цена за литр -->
                            <div>
                                <label for="price_per_liter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Цена за литр <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="price_per_liter" id="price_per_liter" value="{{ old('price_per_liter') }}" step="0.01" min="0" 
                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price_per_liter') border-red-500 @enderror" required
                                           placeholder="0.00">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">₽</span>
                                </div>
                                @error('price_per_liter')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Пробег -->
                        <div>
                            <label for="odometer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Пробег <span class="text-gray-400 text-xs font-normal">(необязательно)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="odometer" id="odometer" value="{{ old('odometer', $lastOdometer ?? '') }}" min="0" 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('odometer') border-red-500 @enderror"
                                       placeholder="0">
                                <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">{{ $selectedCar ? ($cars->firstWhere('id', $selectedCar)->distance_unit ?? 'км') : 'км' }}</span>
                            </div>
                            @if(isset($maxOdometer) && $maxOdometer > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" id="lastOdometerText">
                                    Последний зафиксированный пробег: <span class="font-medium">{{ number_format($maxOdometer) }}</span> {{ $selectedCar ? ($cars->firstWhere('id', $selectedCar)->distance_unit ?? 'км') : 'км' }}
                                </p>
                            @endif
                            @error('odometer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- АЗС -->
                        <div>
                            <label for="gas_station" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                АЗС
                            </label>
                            <input type="text" name="gas_station" id="gas_station" value="{{ old('gas_station') }}" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white dark:placeholder-gray-400 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gas_station') border-red-500 @enderror"
                                   placeholder="Название АЗС">
                            @error('gas_station')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ВЛОЖЕНИЯ (ЧЕКИ) - МАКСИМУМ 4 ФАЙЛА -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Прикрепить чек/квитанцию
                                <span class="text-xs text-gray-400 font-normal">(максимум 4 файла)</span>
                            </label>
                            
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 hover:border-blue-500 transition relative">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="text-blue-500 dark:text-blue-400 font-medium">Нажмите для выбора</span> или перетащите файлы
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">JPG, PNG, PDF до 5 МБ (макс. 4 файла)</p>
                                </div>
                                <input type="file" name="attachments[]" id="attachments" 
                                       multiple accept=".jpg,.jpeg,.png,.pdf"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            
                            <div id="fileList" class="mt-3 space-y-2"></div>
                            <div id="fileLimitMessage" class="text-red-500 text-sm mt-2 hidden">Достигнут лимит в 4 файла</div>
                            @error('attachments.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопки -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('refuelings.index', ['car_id' => old('car_id', $selectedCar)]) }}" 
                               class="px-5 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                                Отмена
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                Сохранить заправку
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Автозаполнение пробега
            const carSelect = document.getElementById('car_id');
            const odometerInput = document.getElementById('odometer');
            const lastOdometerText = document.getElementById('lastOdometerText');
            
            function updateOdometer() {
                const selectedOption = carSelect.options[carSelect.selectedIndex];
                const odometerValue = selectedOption.getAttribute('data-odometer');
                
                if (odometerValue && odometerValue !== '') {
                    odometerInput.value = odometerValue;
                    if (lastOdometerText) {
                        const formatted = new Intl.NumberFormat('ru-RU').format(odometerValue);
                        lastOdometerText.innerHTML = 'Последний зафиксированный пробег: <span class="font-medium">' + formatted + '</span> км';
                    }
                } else {
                    odometerInput.value = '';
                    if (lastOdometerText) {
                        lastOdometerText.innerHTML = 'Последний зафиксированный пробег: <span class="font-medium">0</span> км';
                    }
                }
            }
            
            carSelect.addEventListener('change', updateOdometer);
            
            if (carSelect.value) {
                updateOdometer();
            }

            // ВЛОЖЕНИЯ С ЛИМИТОМ 4 ФАЙЛА
            const fileInput = document.getElementById('attachments');
            const fileList = document.getElementById('fileList');
            const fileLimitMessage = document.getElementById('fileLimitMessage');
            const MAX_FILES = 4;
            const dropZone = fileInput.closest('.border-2');
            
            // Функция обновления списка файлов
            function updateFileList() {
                fileList.innerHTML = '';
                const files = fileInput.files;
                if (files.length === 0) {
                    fileList.innerHTML = '<p class="text-sm text-gray-400 dark:text-gray-500">Файлы не выбраны</p>';
                    return;
                }
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const size = (file.size / 1024 / 1024).toFixed(2);
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-800/50 rounded-lg';
                    
                    let previewHtml = '';
                    if (file.type.startsWith('image/')) {
                        previewHtml = `<img src="${URL.createObjectURL(file)}" class="w-8 h-8 object-cover rounded">`;
                    } else if (file.type === 'application/pdf') {
                        previewHtml = `<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>`;
                    } else {
                        previewHtml = `<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`;
                    }
                    
                    div.innerHTML = `
                        ${previewHtml}
                        <span class="text-sm text-gray-700 dark:text-gray-300 flex-1 truncate">${file.name}</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">${size} MB</span>
                        <button type="button" onclick="removeFile(${i})" class="text-red-500 hover:text-red-600 text-sm font-medium">✕</button>
                    `;
                    fileList.appendChild(div);
                }
            }
            
            // Обработка выбора файлов с ограничением
            fileInput.addEventListener('change', function(e) {
                const files = this.files;
                
                // Если выбрано больше MAX_FILES
                if (files.length > MAX_FILES) {
                    fileLimitMessage.classList.remove('hidden');
                    // Обрезаем до MAX_FILES
                    const dt = new DataTransfer();
                    for (let i = 0; i < MAX_FILES; i++) {
                        dt.items.add(files[i]);
                    }
                    this.files = dt.files;
                    fileLimitMessage.classList.remove('hidden');
                } else {
                    fileLimitMessage.classList.add('hidden');
                }
                
                updateFileList();
            });
            
            // Перетаскивание с ограничением
            if (dropZone) {
                dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('border-blue-500', 'bg-blue-50/20');
                });
                
                dropZone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-blue-500', 'bg-blue-50/20');
                });
                
                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-blue-500', 'bg-blue-50/20');
                    
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    
                    if (files.length > MAX_FILES) {
                        fileLimitMessage.classList.remove('hidden');
                        const newDt = new DataTransfer();
                        for (let i = 0; i < MAX_FILES; i++) {
                            newDt.items.add(files[i]);
                        }
                        fileInput.files = newDt.files;
                    } else {
                        fileLimitMessage.classList.add('hidden');
                        fileInput.files = files;
                    }
                    
                    updateFileList();
                });
            }
            
            // Удаление файла по индексу
            window.removeFile = function(index) {
                const dt = new DataTransfer();
                const files = fileInput.files;
                for (let i = 0; i < files.length; i++) {
                    if (i !== index) dt.items.add(files[i]);
                }
                fileInput.files = dt.files;
                fileLimitMessage.classList.add('hidden');
                updateFileList();
            };
            
            // Инициализация списка
            updateFileList();
        });
    </script>
</x-app-layout>