<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить авто') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="text-center mb-6">
                        <div class="inline-block p-4 bg-[#6B727E] dark:bg-[#6B727E] rounded-full">
                            <img src="{{ asset('images/car.svg') }}" alt="Автомобиль" class="w-25 h-20">
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
                                    <label class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition">
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

                            <!-- Марка с выпадающим списком -->
                            <div class="relative">
                                <label for="brand" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Марка <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand" id="brand" autocomplete="off"
                                    value="{{ old('brand') }}" 
                                    placeholder="Например: LADA, BMW, Toyota..."
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm"
                                    required>
                                
                                <div id="brandDropdown" 
                                     class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-[#222222] border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                    <div class="py-1"></div>
                                </div>
                                @error('brand')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Модель с выпадающим списком -->
                            <div class="relative">
                                <label for="model" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Модель <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" id="model" autocomplete="off"
                                    value="{{ old('model') }}" 
                                    placeholder="Сначала выберите марку..."
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm"
                                    required>
                                
                                <div id="modelDropdown" 
                                     class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-[#222222] border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                    <div class="py-1"></div>
                                </div>
                                @error('model')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="year" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Год</label>
                                <input type="number" name="year" id="year" value="{{ old('year') }}" 
                                    placeholder="2020" 
                                    min="1900" max="{{ date('Y') }}" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('year')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="initial_odometer" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Пробег (км)</label>
                                <input type="number" name="initial_odometer" id="initial_odometer" value="{{ old('initial_odometer', 0) }}" 
                                    min="0" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('initial_odometer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="vin" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">VIN</label>
                                <input type="text" name="vin" id="vin" value="{{ old('vin') }}" 
                                    placeholder="WBAGL..." maxlength="17" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-md shadow-sm">
                                @error('vin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
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
        // База данных марок и их моделей
        const carDatabase = {
            "LADA": ["Granta", "Vesta", "Largus", "Niva", "Niva Travel", "XRAY", "Kalina", "Priora", "Samara", "2101", "2105", "2106", "2107", "2109", "2110", "2111", "2112", "2114", "2115"],
            "ВАЗ": ["Granta", "Vesta", "Largus", "Niva", "XRAY", "Kalina", "Priora", "2101", "2105", "2106", "2107", "2109", "2110", "2111", "2112", "2114", "2115"],
            "ГАЗ": ["Gazelle", "Gazelle Next", "Sobol", "Valdai", "Volga", "Chaika"],
            "УАЗ": ["Patriot", "Hunter", "Bukhanka", "Pickup", "Profi", "Cargo"],
            "Москвич": ["3", "6", "2141", "403", "408", "412"],
            "ЗАЗ": ["Lanos", "Vida", "Chance", "Sens", "Forza"],
            "Иж": ["2125", "2126", "2715", "Oda"],
            "Ravon": ["Gentra", "Nexia", "R2", "R4"],
            "Acura": ["ILX", "MDX", "NSX", "RDX", "RLX", "TLX"],
            "Alfa Romeo": ["Giulia", "Stelvio", "Tonale"],
            "Aston Martin": ["DB11", "DBS", "Vantage"],
            "Audi": ["A3", "A4", "A5", "A6", "A7", "A8", "Q3", "Q5", "Q7", "Q8", "e-tron", "RS3", "RS5", "RS6", "RS7", "S3", "S4", "S5", "S6", "S7", "S8"],
            "BAW": ["BJ2020", "BJ212"],
            "Bentley": ["Bentayga", "Continental GT", "Flying Spur"],
            "BMW": ["1 series", "2 series", "3 series", "4 series", "5 series", "6 series", "7 series", "8 series", "X1", "X2", "X3", "X4", "X5", "X6", "X7", "i3", "i4", "i7", "iX", "M2", "M3", "M4", "M5"],
            "Brilliance": ["H230", "H530", "V5"],
            "Bugatti": ["Chiron", "Veyron"],
            "BYD": ["Atto 3", "Dolphin", "Han", "Seal", "Song", "Tang", "Yuan"],
            "Cadillac": ["CT4", "CT5", "CT6", "Escalade", "XT4", "XT5", "XT6"],
            "Changan": ["CS35", "CS55", "CS75", "UNI-K", "UNI-T", "UNI-V"],
            "Chery": ["Arrizo 7", "Tiggo 4", "Tiggo 7", "Tiggo 8", "Tiggo 8 Pro"],
            "Chevrolet": ["Aveo", "Camaro", "Captiva", "Cobalt", "Corvette", "Cruze", "Equinox", "Lacetti", "Malibu", "Niva", "Onix", "Orlando", "Spark", "Tahoe", "TrailBlazer", "Traverse"],
            "Chrysler": ["300", "Pacifica", "Voyager"],
            "Citroen": ["C3", "C4", "C5", "Berlingo", "Jumpy", "SpaceTourer"],
            "Dacia": ["Duster", "Logan", "Sandero", "Spring"],
            "Daewoo": ["Lacetti", "Matiz", "Nexia"],
            "Daihatsu": ["Mira", "Terios"],
            "Dodge": ["Challenger", "Charger", "Durango"],
            "Dongfeng": ["AX7", "S30"],
            "DS": ["DS3", "DS4", "DS7", "DS9"],
            "FAW": ["Bestune T77"],
            "Ferrari": ["296 GTB", "F8 Tributo", "Portofino", "Purosangue", "Roma", "SF90 Stradale"],
            "Fiat": ["500", "500X", "Doblo", "Ducato", "Panda", "Tipo"],
            "Fisker": ["Ocean"],
            "Ford": ["Bronco", "Edge", "Explorer", "F-150", "Fiesta", "Focus", "Fusion", "Kuga", "Mondeo", "Mustang", "Mustang Mach-E", "Ranger", "S-Max", "Tourneo", "Transit"],
            "Foton": ["Tunland"],
            "Geely": ["Atlas", "Coolray", "Emgrand", "Monjaro", "Tugella"],
            "Genesis": ["G70", "G80", "G90", "GV60", "GV70", "GV80"],
            "GMC": ["Sierra", "Yukon"],
            "Great Wall": ["Hover H3", "Pao", "Poer"],
            "Haval": ["F7", "F7x", "H9", "Jolion", "M6"],
            "Honda": ["Accord", "Civic", "CR-V", "HR-V", "Jazz", "Odyssey", "Pilot"],
            "Hummer": ["H2", "H3", "EV"],
            "Hyundai": ["Accent", "Azera", "Creta", "Elantra", "Grandeur", "Ioniq 5", "Ioniq 6", "Kona", "Palisade", "Santa Fe", "Solaris", "Sonata", "Staria", "Tucson", "Veloster"],
            "Infiniti": ["Q50", "Q60", "Q70", "QX50", "QX55", "QX60", "QX80"],
            "JAC": ["JS4", "JS6", "T6"],
            "Jaguar": ["E-Pace", "F-Pace", "F-Type", "I-Pace", "XE", "XF"],
            "Jeep": ["Cherokee", "Compass", "Gladiator", "Grand Cherokee", "Renegade", "Wagoneer", "Wrangler"],
            "Kia": ["Ceed", "Cerato", "K5", "K9", "Niro", "Optima", "Picanto", "Rio", "Seltos", "Sorento", "Soul", "Sportage", "Stinger", "Telluride"],
            "Lamborghini": ["Aventador", "Huracan", "Urus"],
            "Lancia": ["Ypsilon"],
            "Land Rover": ["Defender", "Discovery", "Discovery Sport", "Range Rover", "Range Rover Evoque", "Range Rover Sport", "Range Rover Velar"],
            "Lexus": ["ES", "GS", "IS", "LC", "LS", "LX", "NX", "RC", "RX", "TX", "UX"],
            "Lifan": ["X60", "X70"],
            "Lincoln": ["Aviator", "Corsair", "Nautilus", "Navigator"],
            "Lotus": ["Emira", "Eletre"],
            "Maserati": ["Ghibli", "Grecale", "Levante", "MC20", "Quattroporte"],
            "Maybach": ["S-Class"],
            "Mazda": ["2", "3", "5", "6", "CX-3", "CX-30", "CX-5", "CX-60", "CX-8", "CX-9", "MX-5"],
            "McLaren": ["720S", "Artura", "GT"],
            "Mercedes-Benz": ["A-Class", "B-Class", "C-Class", "CLA", "CLS", "E-Class", "EQB", "EQC", "EQS", "G-Class", "GLA", "GLB", "GLC", "GLE", "GLS", "S-Class", "SL", "SLC", "V-Class", "Vito", "Sprinter"],
            "MG": ["5", "HS", "Marvel R", "ZS", "ZS EV"],
            "Mini": ["Clubman", "Countryman", "Mini Cooper"],
            "Mitsubishi": ["ASX", "Eclipse Cross", "L200", "Outlander", "Pajero", "Pajero Sport"],
            "Nissan": ["370Z", "Altima", "GT-R", "Juke", "Leaf", "Murano", "Navara", "Note", "Pathfinder", "Qashqai", "Sentra", "Teana", "Terrano", "X-Trail"],
            "Opel": ["Astra", "Corsa", "Crossland", "Grandland", "Insignia", "Mokka"],
            "Peugeot": ["208", "2008", "308", "3008", "408", "5008", "508", "Boxer", "Partner", "Rifter"],
            "Porsche": ["911", "718", "Cayenne", "Macan", "Panamera", "Taycan"],
            "Renault": ["Arkana", "Captur", "Clio", "Duster", "Kadjar", "Kangoo", "Kaptur", "Kiger", "Logan", "Megane", "Sandero", "Scenic", "Talisman", "Trafic"],
            "Rolls-Royce": ["Cullinan", "Ghost", "Phantom", "Wraith"],
            "Rover": ["75"],
            "Saab": ["9-3", "9-5"],
            "Seat": ["Ateca", "Ibiza", "Leon", "Tarraco"],
            "Skoda": ["Fabia", "Kamiq", "Karoq", "Kodiaq", "Octavia", "Rapid", "Scala", "Superb"],
            "Smart": ["Fortwo", "Forfour"],
            "SsangYong": ["Korando", "Rexton", "Tivoli"],
            "Subaru": ["BRZ", "Crosstrek", "Forester", "Impreza", "Legacy", "Outback", "WRX"],
            "Suzuki": ["Ignis", "Jimny", "S-Cross", "Swift", "Vitara"],
            "Tesla": ["Cybertruck", "Model 3", "Model S", "Model X", "Model Y"],
            "Toyota": ["4Runner", "Avalon", "Camry", "C-HR", "Corolla", "FJ Cruiser", "GR86", "Highlander", "Land Cruiser", "Prado", "Prius", "RAV4", "Sequoia", "Sienna", "Supra", "Tacoma", "Tundra"],
            "Volkswagen": ["Arteon", "Caravelle", "Crafter", "Golf", "ID.3", "ID.4", "ID.5", "ID. Buzz", "Jetta", "Multivan", "Passat", "Polo", "T-Cross", "T-Roc", "Taos", "Tiguan", "Touareg"],
            "Volvo": ["C40", "S60", "S90", "V60", "V90", "XC40", "XC60", "XC90"],
            "Vortex": ["Estina", "Tingo"],
            "ZX Auto": ["Grand Tiger"],
            "Другая": ["Другая модель"]
        };

        // Элементы DOM
        const brandInput = document.getElementById('brand');
        const modelInput = document.getElementById('model');
        const brandDropdown = document.getElementById('brandDropdown');
        const modelDropdown = document.getElementById('modelDropdown');
        
        let brandSelectedIndex = -1;
        let modelSelectedIndex = -1;
        let currentBrandFilter = '';
        let currentModelFilter = '';

        // Показать подсказки для марок
        function showBrandSuggestions(filterText) {
            currentBrandFilter = filterText;
            const brands = Object.keys(carDatabase);
            const filtered = brands.filter(brand => 
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
                    modelInput.value = '';
                    modelInput.disabled = false;
                    modelInput.placeholder = "Например: Granta, Vesta, X5...";
                    showModelSuggestions('');
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

        // Показать подсказки для моделей
        function showModelSuggestions(filterText) {
            currentModelFilter = filterText;
            const selectedBrand = brandInput.value.trim();
            
            if (!selectedBrand || !carDatabase[selectedBrand]) {
                modelDropdown.classList.add('hidden');
                modelSelectedIndex = -1;
                return;
            }
            
            const models = carDatabase[selectedBrand];
            const filtered = models.filter(model => 
                model.toLowerCase().includes(filterText.toLowerCase())
            );
            
            if (filtered.length === 0) {
                modelDropdown.classList.add('hidden');
                modelSelectedIndex = -1;
                return;
            }
            
            const dropdownContent = modelDropdown.querySelector('.py-1');
            dropdownContent.innerHTML = '';
            modelSelectedIndex = -1;
            
            filtered.forEach((model, index) => {
                const div = document.createElement('div');
                div.textContent = model;
                div.setAttribute('data-index', index);
                div.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-200 text-sm transition';
                div.onclick = () => {
                    modelInput.value = model;
                    modelDropdown.classList.add('hidden');
                    modelSelectedIndex = -1;
                };
                div.onmouseenter = () => {
                    modelSelectedIndex = index;
                    highlightModelItem(index);
                };
                dropdownContent.appendChild(div);
            });
            
            modelDropdown.classList.remove('hidden');
        }

        function highlightModelItem(index) {
            const items = modelDropdown.querySelectorAll('.py-1 > div');
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

        // Клавиатурная навигация для марки
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
                const selectedBrand = items[brandSelectedIndex].textContent;
                brandInput.value = selectedBrand;
                brandDropdown.classList.add('hidden');
                modelInput.value = '';
                modelInput.disabled = false;
                modelInput.placeholder = "Например: Granta, Vesta, X5...";
                showModelSuggestions('');
                brandSelectedIndex = -1;
            } else if (e.key === 'Escape') {
                brandDropdown.classList.add('hidden');
                brandSelectedIndex = -1;
            }
        });

        // Клавиатурная навигация для модели
        modelInput.addEventListener('keydown', function(e) {
            const items = modelDropdown.querySelectorAll('.py-1 > div');
            if (items.length === 0) return;
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                modelSelectedIndex = Math.min(modelSelectedIndex + 1, items.length - 1);
                highlightModelItem(modelSelectedIndex);
                items[modelSelectedIndex]?.scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                modelSelectedIndex = Math.max(modelSelectedIndex - 1, 0);
                highlightModelItem(modelSelectedIndex);
                items[modelSelectedIndex]?.scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter' && modelSelectedIndex >= 0) {
                e.preventDefault();
                modelInput.value = items[modelSelectedIndex].textContent;
                modelDropdown.classList.add('hidden');
                modelSelectedIndex = -1;
            } else if (e.key === 'Escape') {
                modelDropdown.classList.add('hidden');
                modelSelectedIndex = -1;
            }
        });

        // События для марки
        brandInput.addEventListener('input', function(e) {
            showBrandSuggestions(e.target.value);
            if (carDatabase[e.target.value.trim()]) {
                modelInput.disabled = false;
                modelInput.placeholder = "Например: Granta, Vesta, X5...";
                modelInput.value = '';
            }
        });

        brandInput.addEventListener('focus', function() {
            if (this.value) {
                showBrandSuggestions(this.value);
            }
        });

        // События для модели
        modelInput.addEventListener('input', function(e) {
            if (brandInput.value.trim() && carDatabase[brandInput.value.trim()]) {
                showModelSuggestions(e.target.value);
            } else {
                this.disabled = true;
                this.placeholder = "Сначала выберите марку";
            }
        });

        modelInput.addEventListener('focus', function() {
            if (brandInput.value.trim() && carDatabase[brandInput.value.trim()]) {
                showModelSuggestions(this.value);
            }
        });

        // Скрыть подсказки при клике вне полей
        document.addEventListener('click', function(e) {
            if (e.target !== brandInput && !brandDropdown.contains(e.target)) {
                brandDropdown.classList.add('hidden');
                brandSelectedIndex = -1;
            }
            if (e.target !== modelInput && !modelDropdown.contains(e.target)) {
                modelDropdown.classList.add('hidden');
                modelSelectedIndex = -1;
            }
        });

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