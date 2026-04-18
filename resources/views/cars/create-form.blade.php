<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить авто') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="text-center mb-6">
                        <div class="inline-block p-4 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <svg class="w-16 h-16 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M6 12l-2 5h16l-2-5M8 9h8M9 6h6M12 6v3" />
                                <rect x="7" y="14" width="2" height="2" rx="1" />
                                <rect x="15" y="14" width="2" height="2" rx="1" />
                            </svg>
                        </div>
                    </div>

                    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Фото автомобиля</label>
                                <div id="photoPreview" class="hidden mb-3">
                                    <img id="previewImage" class="w-32 h-32 object-cover rounded-lg border dark:border-gray-600">
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="cursor-pointer bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition">
                                        Выбрать файл
                                        <input type="file" name="photo" id="photo" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(this)">
                                    </label>
                                    <span id="fileName" class="text-sm text-gray-500 dark:text-gray-400">Файл не выбран</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Поддерживаются JPEG, PNG, JPG. Максимум 2 МБ</p>
                                @error('photo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="brand" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Марка <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand" id="brand" value="{{ old('brand') }}" 
                                    placeholder="Например: Toyota, BMW, Kia" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" 
                                    required>
                                @error('brand')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="model" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Модель <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" id="model" value="{{ old('model') }}" 
                                    placeholder="Например: Camry, X5, Rio" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" 
                                    required>
                                @error('model')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="year" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Год</label>
                                <input type="number" name="year" id="year" value="{{ old('year') }}" 
                                    placeholder="2020" 
                                    min="1900" max="{{ date('Y') }}" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                @error('year')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="initial_odometer" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Пробег (км)</label>
                                <input type="number" name="initial_odometer" id="initial_odometer" value="{{ old('initial_odometer', 0) }}" 
                                    min="0" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                @error('initial_odometer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="vin" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">VIN</label>
                                <input type="text" name="vin" id="vin" value="{{ old('vin') }}" 
                                    placeholder="WBAGL..." maxlength="17" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                @error('vin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-red-500">*</span> Поля, отмеченные <span class="text-red-500">*</span>, обязательны для заполнения
                        </div>

                        <div class="flex justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('cars.create') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                                Назад
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">
                                Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewPhoto(input) {
            const file = input.files[0];
            const fileNameSpan = document.getElementById('fileName');
            const previewDiv = document.getElementById('photoPreview');
            const previewImage = document.getElementById('previewImage');
            
            if (file) {
                fileNameSpan.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewDiv.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                fileNameSpan.textContent = 'Файл не выбран';
                previewDiv.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>