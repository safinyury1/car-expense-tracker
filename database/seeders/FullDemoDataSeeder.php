<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Income;
use App\Models\Reminder;
use App\Models\ExpenseCategory;
use App\Models\Visit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FullDemoDataSeeder extends Seeder
{
    public function run()
    {
        // === 1. ОЧИСТКА СТАРЫХ ДАННЫХ ===
        Expense::truncate();
        Refueling::truncate();
        Income::truncate();
        Reminder::truncate();
        Visit::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Car::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        User::where('email', '!=', 'admin@autocost.ru')
            ->where('email', '!=', 'user@autocost.ru')
            ->delete();

        // === 2. КАТЕГОРИИ ===
        $categories = [
            ['name' => 'Топливо', 'is_default' => true, 'user_id' => null],
            ['name' => 'Ремонт', 'is_default' => true, 'user_id' => null],
            ['name' => 'Страховка', 'is_default' => true, 'user_id' => null],
            ['name' => 'Налог', 'is_default' => true, 'user_id' => null],
            ['name' => 'Мойка', 'is_default' => true, 'user_id' => null],
            ['name' => 'Штрафы', 'is_default' => true, 'user_id' => null],
            ['name' => 'Парковка', 'is_default' => true, 'user_id' => null],
            ['name' => 'Шины', 'is_default' => true, 'user_id' => null],
            ['name' => 'Прочее', 'is_default' => true, 'user_id' => null],
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::firstOrCreate(
                ['name' => $cat['name'], 'user_id' => $cat['user_id']],
                ['is_default' => $cat['is_default']]
            );
        }

        $fuelId = ExpenseCategory::where('name', 'Топливо')->first()->id;
        $repairId = ExpenseCategory::where('name', 'Ремонт')->first()->id;
        $insuranceId = ExpenseCategory::where('name', 'Страховка')->first()->id;
        $otherId = ExpenseCategory::where('name', 'Прочее')->first()->id;

        // === 3. АДМИН ===
        $admin = User::firstOrCreate(
            ['email' => 'admin@autocost.ru'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'avatar' => 'avatars/admin.png',
            ]
        );

        // Если файл аватара админа существует — копируем в public/storage
        $adminAvatarPath = storage_path('app/public/avatars/admin.png');
        if (file_exists($adminAvatarPath)) {
            $this->command->info('  Аватар админа найден');
        } else {
            $this->command->warn('  Аватар админа не найден: storage/app/public/avatars/admin.png');
        }

        // === 4. ОБЫЧНЫЙ ПОЛЬЗОВАТЕЛЬ ===
        $user = User::firstOrCreate(
            ['email' => 'user@autocost.ru'],
            [
                'name' => 'Пользователь',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'avatar' => 'avatars/user.jpg',
            ]
        );

        // Проверяем файл аватара пользователя
        $userAvatarPath = storage_path('app/public/avatars/user.jpg');
        if (file_exists($userAvatarPath)) {
            $this->command->info('  Аватар пользователя найден');
        } else {
            $this->command->warn('  Аватар пользователя не найден: storage/app/public/avatars/user.jpg');
        }

        // === 5. АВТОМОБИЛИ ===
        
        // Toyota Camry
        $toyota = Car::create([
            'user_id' => $user->id,
            'brand' => 'Toyota',
            'model' => 'Camry',
            'year' => 2020,
            'vin' => 'JTDBE30KX0000001',
            'initial_odometer' => 45000,
            'odometer' => 47500,
            'photo' => 'car-photos/toyota.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // Проверяем фото Toyota
        if (file_exists(storage_path('app/public/car-photos/toyota.jpg'))) {
            $this->command->info('  Фото Toyota найдено');
        } else {
            $this->command->warn('  Фото Toyota не найдено: storage/app/public/car-photos/toyota.jpg');
        }

        // BMW X5
        $bmw = Car::create([
            'user_id' => $user->id,
            'brand' => 'BMW',
            'model' => 'X5',
            'year' => 2021,
            'vin' => 'WBAKS410900000001',
            'initial_odometer' => 32000,
            'odometer' => 34000,
            'photo' => 'car-photos/bmw.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        if (file_exists(storage_path('app/public/car-photos/bmw.jpg'))) {
            $this->command->info('  Фото BMW найдено');
        } else {
            $this->command->warn('  Фото BMW не найдено: storage/app/public/car-photos/bmw.jpg');
        }

        // LADA Vesta
        $lada = Car::create([
            'user_id' => $user->id,
            'brand' => 'LADA',
            'model' => 'Vesta',
            'year' => 2022,
            'vin' => 'XTA12345678901234',
            'initial_odometer' => 15000,
            'odometer' => 16000,
            'photo' => 'car-photos/lada.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        if (file_exists(storage_path('app/public/car-photos/lada.jpg'))) {
            $this->command->info('  Фото LADA найдено');
        } else {
            $this->command->warn('  Фото LADA не найдено: storage/app/public/car-photos/lada.jpg');
        }

        // === 6. РАСХОДЫ ===
        // Toyota
        Expense::create(['car_id' => $toyota->id, 'category_id' => $fuelId, 'date' => '2026-05-01', 'amount' => 3500, 'odometer' => 45200, 'description' => 'Заправка Лукойл']);
        Expense::create(['car_id' => $toyota->id, 'category_id' => $fuelId, 'date' => '2026-05-10', 'amount' => 3200, 'odometer' => 46500, 'description' => 'Заправка Газпром']);
        Expense::create(['car_id' => $toyota->id, 'category_id' => $fuelId, 'date' => '2026-05-18', 'amount' => 3600, 'odometer' => 47500, 'description' => 'Заправка Татнефть']);
        Expense::create(['car_id' => $toyota->id, 'category_id' => $repairId, 'date' => '2026-05-05', 'amount' => 8500, 'odometer' => 45800, 'description' => 'Замена масла']);
        Expense::create(['car_id' => $toyota->id, 'category_id' => $repairId, 'date' => '2026-05-15', 'amount' => 4500, 'odometer' => 47000, 'description' => 'Замена фильтров']);
        Expense::create(['car_id' => $toyota->id, 'category_id' => $insuranceId, 'date' => '2026-05-20', 'amount' => 12000, 'odometer' => 0, 'description' => 'ОСАГО']);
        Expense::create(['car_id' => $toyota->id, 'category_id' => $otherId, 'date' => '2026-05-22', 'amount' => 3000, 'odometer' => 47800, 'description' => 'Мойка детейлинг']);

        // BMW
        Expense::create(['car_id' => $bmw->id, 'category_id' => $fuelId, 'date' => '2026-05-02', 'amount' => 5000, 'odometer' => 32500, 'description' => 'Заправка Лукойл']);
        Expense::create(['car_id' => $bmw->id, 'category_id' => $fuelId, 'date' => '2026-05-12', 'amount' => 4800, 'odometer' => 34000, 'description' => 'Заправка BP']);
        Expense::create(['car_id' => $bmw->id, 'category_id' => $repairId, 'date' => '2026-05-08', 'amount' => 12000, 'odometer' => 33200, 'description' => 'Замена тормозных колодок']);

        // === 7. ЗАПРАВКИ ===
        // Toyota
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-05-01', 'liters' => 40, 'price_per_liter' => 87.50, 'odometer' => 45200, 'gas_station' => 'Лукойл', 'total_amount' => 3500]);
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-05-10', 'liters' => 38, 'price_per_liter' => 84.20, 'odometer' => 46500, 'gas_station' => 'Газпром', 'total_amount' => 3200]);
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-05-18', 'liters' => 42, 'price_per_liter' => 85.70, 'odometer' => 47500, 'gas_station' => 'Татнефть', 'total_amount' => 3600]);

        // BMW
        Refueling::create(['car_id' => $bmw->id, 'date' => '2026-05-02', 'liters' => 55, 'price_per_liter' => 90.91, 'odometer' => 32500, 'gas_station' => 'Лукойл', 'total_amount' => 5000]);
        Refueling::create(['car_id' => $bmw->id, 'date' => '2026-05-12', 'liters' => 52, 'price_per_liter' => 92.30, 'odometer' => 34000, 'gas_station' => 'BP', 'total_amount' => 4800]);

        // === 8. ДОХОДЫ ===
        Income::create(['car_id' => $toyota->id, 'date' => '2026-05-15', 'title' => 'Кэшбэк от АЗС', 'amount' => 500, 'odometer' => 0, 'description' => 'Бонусы Лукойл']);
        Income::create(['car_id' => $toyota->id, 'date' => '2026-05-20', 'title' => 'Возврат за ремонт', 'amount' => 2000, 'odometer' => 47000, 'description' => 'Гарантийный случай']);

        // === 9. НАПОМИНАНИЯ ===
        Reminder::create(['car_id' => $toyota->id, 'title' => 'Замена масла', 'due_odometer' => 50000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $toyota->id, 'title' => 'Замена ремня ГРМ', 'due_odometer' => 60000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $bmw->id, 'title' => 'Замена тормозных колодок', 'due_odometer' => 40000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $bmw->id, 'title' => 'Техосмотр', 'due_odometer' => 0, 'due_date' => '2026-10-01', 'is_completed' => false]);
        Reminder::create(['car_id' => $lada->id, 'title' => 'Замена масла', 'due_odometer' => 20000, 'due_date' => null, 'is_completed' => false]);

        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Демо-данные успешно созданы!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Администратор: admin@autocost.ru / admin123');
        $this->command->info('Аватар: storage/app/public/avatars/admin.png');
        $this->command->info('Обычный пользователь: user@autocost.ru / user123');
        $this->command->info('Аватар: storage/app/public/avatars/user.jpg');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Автомобили:');
        $this->command->info('Toyota Camry 2020 → car-photos/toyota.jpg');
        $this->command->info('BMW X5 2021 → car-photos/bmw.jpg');
        $this->command->info('LADA Vesta 2022 → car-photos/lada.jpg');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Создано расходов: 10');
        $this->command->info('Создано заправок: 5');
        $this->command->info('Создано доходов: 2');
        $this->command->info('Создано напоминаний: 5');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Если фото не отображаются, выполните:');
        $this->command->info('php artisan storage:link');
    }
}