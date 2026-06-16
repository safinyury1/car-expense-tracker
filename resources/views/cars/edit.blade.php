<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Редактировать автомобиль') }}
            </h2>
            <a href="{{ route('cars.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 sm:px-5 py-2.5 rounded-xl text-sm sm:text-base flex items-center justify-center gap-2 transition shadow-sm w-full sm:w-auto">
                <span>Назад к списку</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-7 md:p-8">
                    
                    <!-- Фото автомобиля -->
                    <div class="text-center mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-center">
                            Фото автомобиля
                        </label>
                        
                        <div class="relative inline-block">
                            <!-- Превью фото (показывается когда есть фото) -->
                            <div id="photoPreview" class="{{ $car->photo ? '' : 'hidden' }} relative group cursor-pointer" onclick="document.getElementById('photo_input').click()">
                                <div class="w-32 h-32 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-sm bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <img id="car_photo_preview" 
                                         src="{{ $car->photo ? Storage::url($car->photo) : '' }}" 
                                         alt="{{ $car->brand }} {{ $car->model }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="absolute inset-0 rounded-xl bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <span class="text-white text-sm font-medium">Изменить</span>
                                </div>
                            </div>
                            
                            <!-- Заглушка когда нет фото -->
                            <div id="photoPlaceholder" class="{{ $car->photo ? 'hidden' : '' }} cursor-pointer" onclick="document.getElementById('photo_input').click()">
                                <div class="w-32 h-32 bg-gray-100 dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">Нет фото</span>
                                </div>
                            </div>
                            
                            <!-- Кнопка удаления фото (крестик) -->
                            @if($car->photo)
                                <button type="button" onclick="deletePhoto()" 
                                        id="deletePhotoBtn"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm shadow-md transition z-10">
                                    ×
                                </button>
                            @endif
                        </div>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Кликните по фото для замены</p>
                    </div>

                    <!-- Форма загрузки фото (AJAX) -->
                    <form id="photo_form" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="photo" id="photo_input" accept="image/jpeg,image/png,image/jpg" class="hidden">
                    </form>

                    <!-- Форма обновления данных автомобиля -->
                    <form id="carForm" action="{{ route('cars.update', $car) }}" method="POST" class="space-y-5 sm:space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Скрытое поле для удаления фото -->
                        <input type="hidden" name="delete_photo" id="delete_photo_field" value="0">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                            <!-- Марка -->
                            <div class="relative">
                                <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Марка <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand" id="brand" autocomplete="off"
                                    value="{{ old('brand', $car->brand) }}" 
                                    placeholder="Например: LADA, BMW, Toyota..."
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('brand') border-red-500 @enderror" required>
                                
                                <div id="brandDropdown" 
                                     class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-[#222222] border border-gray-200 dark:border-gray-600 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                    <div class="py-1"></div>
                                </div>
                                @error('brand')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Модель -->
                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Модель <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" id="model" value="{{ old('model', $car->model) }}" 
                                    placeholder="Введите модель"
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('model') border-red-500 @enderror" required>
                                @error('model')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Год выпуска -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Год выпуска
                                </label>
                                <input type="number" name="year" id="year" value="{{ old('year', $car->year) }}" 
                                    placeholder="2020"
                                    min="1900" max="{{ date('Y') }}" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-500 @enderror">
                                @error('year')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- VIN-код -->
                            <div>
                                <label for="vin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    VIN-код
                                </label>
                                <input type="text" name="vin" id="vin" value="{{ old('vin', $car->vin) }}" 
                                    placeholder="WBAGL..." maxlength="17" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vin') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">17 символов, только латиница и цифры</p>
                                @error('vin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Начальный пробег -->
                            <div class="md:col-span-2">
                                <label for="initial_odometer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Начальный пробег
                                </label>
                                <div class="relative">
                                    <input type="number" name="initial_odometer" id="initial_odometer" value="{{ old('initial_odometer', $car->initial_odometer) }}" 
                                        min="0" 
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('initial_odometer') border-red-500 @enderror">
                                    <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400 text-base">км</span>
                                </div>
                                @error('initial_odometer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('cars.index') }}" 
                               class="px-5 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                                Отмена
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // База данных марок
        const carBrandsList = [
            "Acura", "Alfa Romeo", "Aston Martin", "Audi", "BAW", "Bentley", "BMW", "Brilliance", 
            "Bugatti", "BYD", "Cadillac", "Changan", "Chery", "Chevrolet", "Chrysler", "Citroen", 
            "Dacia", "Daewoo", "Daihatsu", "Dodge", "Dongfeng", "DS", "FAW", "Ferrari", "Fiat", 
            "Fisker", "Ford", "Foton", "Geely", "Genesis", "GMC", "Great Wall", "Haval", "Honda", 
            "Hummer", "Hyundai", "Infiniti", "JAC", "Jaguar", "Jeep", "Kia", "Lamborghini", 
            "Lancia", "Land Rover", "Lexus", "Lifan", "Lincoln", "Lotus", "Maserati", "Maybach", 
            "Mazda", "McLaren", "Mercedes-Benz", "MG", "Mini", "Mitsubishi", "Nissan", "Opel", 
            "Peugeot", "Porsche", "Ravon", "Renault", "Rolls-Royce", "Rover", "Saab", "Seat", 
            "Skoda", "Smart", "SsangYong", "Subaru", "Suzuki", "Tesla", "Toyota", "Volkswagen", 
            "Volvo", "Vortex", "ZAZ", "ZX Auto", "LADA", "ВАЗ", "ГАЗ", "УАЗ", "Москвич", "ЗАЗ", "Иж", "Другая"
        ];

        const brandInput = document.getElementById('brand');
        const brandDropdown = document.getElementById('brandDropdown');
        
        let brandSelectedIndex = -1;

        function showBrandSuggestions(filterText) {
            const filtered = carBrandsList.filter(brand => 
                brand.toLowerCase().includes(filterText.toLowerCase())
            );
            
            if (filtered.length === 0 || filterText === '') {
                brandDropdown.classList.add('hidden');
                brandSelectedIndex = -1;
                return;
            }
            
            const dropdownContent = brandDropdown.querySelector('.py-1');
            dropdownContent.innerHTML = '';
            brandSelectedIndex = -1;
            
            filtered.forEach((brand, index) => {
                const div = document.createElement('div');
                div.textContent = brand;
                div.className = 'px-4 py-2.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-200 text-sm transition';
                div.onclick = () => {
                    brandInput.value = brand;
                    brandDropdown.classList.add('hidden');
                    brandSelectedIndex = -1;
                };
                div.onmouseenter = () => {
                    brandSelectedIndex = index;
                    highlightBrandItem(index);
                };
                dropdownContent.appendChild(div);
            });
            
            brandDropdown.classList.remove('hidden');
        }

        function highlightBrandItem(index) {
            const items = brandDropdown.querySelectorAll('.py-1 > div');
            items.forEach((item, i) => {
                if (i === index) {
                    item.classList.add('bg-blue-500', 'text-white');
                } else {
                    item.classList.remove('bg-blue-500', 'text-white');
                }
            });
        }

        brandInput.addEventListener('keydown', function(e) {
            const items = brandDropdown.querySelectorAll('.py-1 > div');
            if (items.length === 0) return;
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                brandSelectedIndex = Math.min(brandSelectedIndex + 1, items.length - 1);
                highlightBrandItem(brandSelectedIndex);
                items[brandSelectedIndex]?.scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                brandSelectedIndex = Math.max(brandSelectedIndex - 1, 0);
                highlightBrandItem(brandSelectedIndex);
                items[brandSelectedIndex]?.scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter' && brandSelectedIndex >= 0) {
                e.preventDefault();
                brandInput.value = items[brandSelectedIndex].textContent;
                brandDropdown.classList.add('hidden');
                brandSelectedIndex = -1;
            } else if (e.key === 'Escape') {
                brandDropdown.classList.add('hidden');
                brandSelectedIndex = -1;
            }
        });

        brandInput.addEventListener('input', function(e) {
            showBrandSuggestions(e.target.value);
        });

        brandInput.addEventListener('focus', function() {
            if (this.value) {
                showBrandSuggestions(this.value);
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target !== brandInput && !brandDropdown.contains(e.target)) {
                brandDropdown.classList.add('hidden');
                brandSelectedIndex = -1;
            }
        });

        // Выбор фото
        document.getElementById('photo_input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('car_photo_preview').src = event.target.result;
                // Показываем превью и скрываем заглушку
                document.getElementById('photoPreview').classList.remove('hidden');
                document.getElementById('photoPlaceholder').classList.add('hidden');
                // Показываем крестик
                if (!document.getElementById('deletePhotoBtn')) {
                    const container = document.querySelector('.relative.inline-block');
                    const newDeleteBtn = document.createElement('button');
                    newDeleteBtn.type = 'button';
                    newDeleteBtn.onclick = deletePhoto;
                    newDeleteBtn.id = 'deletePhotoBtn';
                    newDeleteBtn.className = 'absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm shadow-md transition z-10';
                    newDeleteBtn.innerHTML = '×';
                    container.appendChild(newDeleteBtn);
                }
                // Сбрасываем флаг удаления
                document.getElementById('delete_photo_field').value = '0';
            }
            reader.readAsDataURL(file);
            
            // Загружаем фото на сервер сразу
            fetch('{{ route("cars.update.photo", $car) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Фото загружено', 'success');
                } else {
                    showNotification('Ошибка при загрузке фото', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка при загрузке фото', 'error');
            });
        });
        
        // Удаление фото
        function deletePhoto() {
            if (!confirm('Удалить фото?')) return;
            
            fetch('{{ route("cars.delete-photo", $car) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Скрываем превью, показываем заглушку
                    document.getElementById('photoPreview').classList.add('hidden');
                    document.getElementById('photoPlaceholder').classList.remove('hidden');
                    // Удаляем крестик
                    const deleteBtn = document.getElementById('deletePhotoBtn');
                    if (deleteBtn) deleteBtn.remove();
                    // Устанавливаем флаг удаления
                    document.getElementById('delete_photo_field').value = '1';
                    showNotification('Фото удалено', 'success');
                } else {
                    showNotification('Ошибка при удалении фото', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка при удалении фото', 'error');
            });
        }
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.className = 'fixed bottom-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white text-sm ' + 
                (type === 'success' ? 'bg-green-500' : 'bg-red-500');
            notification.style.animation = 'fadeInOut 2s ease-in-out';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 2000);
        }
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateY(20px); }
                15% { opacity: 1; transform: translateY(0); }
                85% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(20px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>