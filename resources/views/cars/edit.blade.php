<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактировать автомобиль') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- ФОТО КВАДРАТ С ЗАКРУГЛЕНИЕМ (AJAX ЗАГРУЗКА) -->
                    <div class="flex justify-center mb-8">
                        <div class="relative group cursor-pointer" onclick="document.getElementById('photo_input').click()">
                            <div class="w-32 h-32 rounded-xl border-4 border-blue-500 dark:border-blue-400 overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <img id="car_photo_preview" 
                                     src="{{ $car->photo ? Storage::url($car->photo) : asset('images/default-car.png') }}" 
                                     alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="absolute inset-0 rounded-xl bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Форма загрузки фото (AJAX) -->
                    <form id="photo_form" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="photo" id="photo_input" accept="image/jpeg,image/png,image/jpg" class="hidden">
                    </form>

                    <script>
                        document.getElementById('photo_input').addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            
                            const formData = new FormData();
                            formData.append('photo', file);
                            formData.append('_token', '{{ csrf_token() }}');
                            
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                document.getElementById('car_photo_preview').src = event.target.result;
                            }
                            reader.readAsDataURL(file);
                            
                            fetch('{{ route("cars.update-photo", $car) }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showNotification('Фото обновлено!', 'success');
                                } else {
                                    showNotification('Ошибка при загрузке фото', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('Ошибка при загрузке фото', 'error');
                            });
                        });
                        
                        function showNotification(message, type) {
                            const notification = document.createElement('div');
                            notification.textContent = message;
                            notification.className = 'fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg text-white ' + 
                                (type === 'success' ? 'bg-green-500' : 'bg-red-500');
                            notification.style.animation = 'fadeInOut 2s ease-in-out';
                            document.body.appendChild(notification);
                            setTimeout(() => notification.remove(), 2000);
                        }
                        
                        const style = document.createElement('style');
                        style.textContent = `
                            @keyframes fadeInOut {
                                0% { opacity: 0; transform: translateY(-20px); }
                                15% { opacity: 1; transform: translateY(0); }
                                85% { opacity: 1; transform: translateY(0); }
                                100% { opacity: 0; transform: translateY(-20px); }
                            }
                        `;
                        document.head.appendChild(style);
                    </script>

                    <!-- Форма обновления данных автомобиля -->
                    <form action="{{ route('cars.update', $car) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Марка с выпадающим списком -->
                            <div class="relative">
                                <label for="brand" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Марка <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand" id="brand" autocomplete="off"
                                    value="{{ old('brand', $car->brand) }}" 
                                    placeholder="Например: LADA, BMW, Toyota..."
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                                
                                <div id="brandDropdown" 
                                     class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-[#222222] border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                    <div class="py-1"></div>
                                </div>
                                @error('brand')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Модель (просто поле ввода) -->
                            <div>
                                <label for="model" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Модель <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" id="model" value="{{ old('model', $car->model) }}" 
                                    placeholder="Введите модель"
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm" required>
                                @error('model')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="year" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Год выпуска</label>
                                <input type="number" name="year" id="year" value="{{ old('year', $car->year) }}" 
                                    min="1900" max="{{ date('Y') }}" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('year')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="vin" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">VIN-код</label>
                                <input type="text" name="vin" id="vin" value="{{ old('vin', $car->vin) }}" 
                                    maxlength="17" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('vin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="initial_odometer" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Начальный пробег (км)</label>
                                <input type="number" name="initial_odometer" id="initial_odometer" value="{{ old('initial_odometer', $car->initial_odometer) }}" 
                                    min="0" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('initial_odometer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Назад</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Обновить</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // База данных марок (только список, без моделей)
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

        // Элементы DOM
        const brandInput = document.getElementById('brand');
        const brandDropdown = document.getElementById('brandDropdown');
        
        let brandSelectedIndex = -1;
        let currentBrandFilter = '';

        // Показать подсказки для марок
        function showBrandSuggestions(filterText) {
            currentBrandFilter = filterText;
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
                div.setAttribute('data-index', index);
                div.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-200 text-sm transition';
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
                    item.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-700');
                } else {
                    item.classList.remove('bg-blue-500', 'text-white');
                    item.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-700');
                }
            });
        }

        // Клавиатурная навигация
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

        // Скрыть подсказки при клике вне поля
        document.addEventListener('click', function(e) {
            if (e.target !== brandInput && !brandDropdown.contains(e.target)) {
                brandDropdown.classList.add('hidden');
                brandSelectedIndex = -1;
            }
        });
    </script>
</x-app-layout>