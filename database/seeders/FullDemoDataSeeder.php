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
            ->where('email', '!=', 'autocost774@mail.ru')
            ->where('email', '!=', 'user@gmail.com')
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
        $taxId = ExpenseCategory::where('name', 'Налог')->first()->id;
        $washingId = ExpenseCategory::where('name', 'Мойка')->first()->id;
        $fineId = ExpenseCategory::where('name', 'Штрафы')->first()->id;
        $parkingId = ExpenseCategory::where('name', 'Парковка')->first()->id;
        $tiresId = ExpenseCategory::where('name', 'Шины')->first()->id;

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

        // === 4. ОСНОВНОЙ ПОЛЬЗОВАТЕЛЬ ===
        $user = User::firstOrCreate(
            ['email' => 'autocost774@mail.ru'],
            [
                'name' => 'Пользователь',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'avatar' => 'avatars/user.png',
            ]
        );

        // === 5. ПОЛЬЗОВАТЕЛЬ ДЛЯ КОМИССИИ (НОВЫЙ) ===
        $commissionUser = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Пользователь',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'avatar' => 'avatars/user.png',
            ]
        );

        // === 6. АВТОМОБИЛИ ДЛЯ ОСНОВНОГО ПОЛЬЗОВАТЕЛЯ ===
        
        // 1. Toyota Camry
        $toyota = Car::create([
            'user_id' => $user->id,
            'brand' => 'Toyota',
            'model' => 'Camry',
            'year' => 2020,
            'vin' => 'JTDBE30KX0000001',
            'initial_odometer' => 45000,
            'odometer' => 57800,
            'photo' => 'car-photos/toyota.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // 2. BMW X5
        $bmw = Car::create([
            'user_id' => $user->id,
            'brand' => 'BMW',
            'model' => 'X5',
            'year' => 2021,
            'vin' => 'WBAKS410900000001',
            'initial_odometer' => 32000,
            'odometer' => 45200,
            'photo' => 'car-photos/bmw.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // 3. LADA Vesta
        $lada = Car::create([
            'user_id' => $user->id,
            'brand' => 'LADA',
            'model' => 'Vesta',
            'year' => 2022,
            'vin' => 'XTA12345678901234',
            'initial_odometer' => 15000,
            'odometer' => 22300,
            'photo' => 'car-photos/lada.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // 4. BMW M5 G90 (2024)
        $bmwM5 = Car::create([
            'user_id' => $user->id,
            'brand' => 'BMW',
            'model' => 'M5 G90',
            'year' => 2024,
            'vin' => 'WBSJF02000G900001',
            'initial_odometer' => 5000,
            'odometer' => 8500,
            'photo' => 'car-photos/bmw-m5.jpeg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // 5. Kia Stinger
        $stinger = Car::create([
            'user_id' => $user->id,
            'brand' => 'Kia',
            'model' => 'Stinger',
            'year' => 2022,
            'vin' => 'KNAE552T8N0000001',
            'initial_odometer' => 12000,
            'odometer' => 18900,
            'photo' => 'car-photos/stinger.jpeg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // === 7. АВТОМОБИЛИ ДЛЯ ПОЛЬЗОВАТЕЛЯ КОМИССИИ ===
        
        // 6. Mercedes-Benz S-Class
        $mercedes = Car::create([
            'user_id' => $commissionUser->id,
            'brand' => 'Mercedes-Benz',
            'model' => 'S-Class',
            'year' => 2023,
            'vin' => 'W1KNG8EB4PA000001',
            'initial_odometer' => 8000,
            'odometer' => 12000,
            'photo' => 'car-photos/mercedes.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // 7. Audi Q7
        $audi = Car::create([
            'user_id' => $commissionUser->id,
            'brand' => 'Audi',
            'model' => 'Q7',
            'year' => 2022,
            'vin' => 'WA1LHAF79PD000001',
            'initial_odometer' => 15000,
            'odometer' => 22500,
            'photo' => 'car-photos/audi.jpg',
            'distance_unit' => 'km',
            'volume_unit' => 'liters',
            'currency' => 'RUB',
        ]);

        // === 8. РАСХОДЫ ===
        
        // ===== Toyota Camry =====
        $expensesToyota = [
            ['category_id' => $fuelId, 'date' => '2026-05-01', 'amount' => 3500, 'odometer' => 45200, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-05-10', 'amount' => 3200, 'odometer' => 46500, 'description' => 'Заправка Газпром'],
            ['category_id' => $fuelId, 'date' => '2026-05-18', 'amount' => 3600, 'odometer' => 47500, 'description' => 'Заправка Татнефть'],
            ['category_id' => $fuelId, 'date' => '2026-06-01', 'amount' => 3400, 'odometer' => 48800, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-06-12', 'amount' => 3800, 'odometer' => 50200, 'description' => 'Заправка Газпром'],
            ['category_id' => $fuelId, 'date' => '2026-06-20', 'amount' => 3700, 'odometer' => 51500, 'description' => 'Заправка Татнефть'],
            ['category_id' => $repairId, 'date' => '2026-05-05', 'amount' => 8500, 'odometer' => 45800, 'description' => 'Замена масла'],
            ['category_id' => $repairId, 'date' => '2026-05-15', 'amount' => 4500, 'odometer' => 47000, 'description' => 'Замена фильтров'],
            ['category_id' => $insuranceId, 'date' => '2026-05-20', 'amount' => 12000, 'odometer' => 0, 'description' => 'ОСАГО'],
            ['category_id' => $otherId, 'date' => '2026-05-22', 'amount' => 3000, 'odometer' => 47800, 'description' => 'Мойка детейлинг'],
            ['category_id' => $tiresId, 'date' => '2026-06-05', 'amount' => 15000, 'odometer' => 49000, 'description' => 'Комплект летней резины'],
            ['category_id' => $washingId, 'date' => '2026-06-10', 'amount' => 500, 'odometer' => 50000, 'description' => 'Мойка'],
            ['category_id' => $parkingId, 'date' => '2026-06-15', 'amount' => 300, 'odometer' => 50800, 'description' => 'Парковка в аэропорту'],
        ];

        foreach ($expensesToyota as $exp) {
            Expense::create(array_merge(['car_id' => $toyota->id], $exp));
        }

        // ===== BMW X5 =====
        $expensesBmw = [
            ['category_id' => $fuelId, 'date' => '2026-05-02', 'amount' => 5000, 'odometer' => 32500, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-05-12', 'amount' => 4800, 'odometer' => 34000, 'description' => 'Заправка BP'],
            ['category_id' => $fuelId, 'date' => '2026-05-25', 'amount' => 5200, 'odometer' => 35500, 'description' => 'Заправка Shell'],
            ['category_id' => $fuelId, 'date' => '2026-06-05', 'amount' => 4900, 'odometer' => 37000, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-06-18', 'amount' => 5500, 'odometer' => 38800, 'description' => 'Заправка BP'],
            ['category_id' => $repairId, 'date' => '2026-05-08', 'amount' => 12000, 'odometer' => 33200, 'description' => 'Замена тормозных колодок'],
            ['category_id' => $repairId, 'date' => '2026-06-01', 'amount' => 8500, 'odometer' => 36200, 'description' => 'Замена масла и фильтров'],
            ['category_id' => $insuranceId, 'date' => '2026-06-10', 'amount' => 15000, 'odometer' => 0, 'description' => 'КАСКО'],
            ['category_id' => $tiresId, 'date' => '2026-05-15', 'amount' => 25000, 'odometer' => 34500, 'description' => 'Комплект зимней резины'],
            ['category_id' => $washingId, 'date' => '2026-06-08', 'amount' => 800, 'odometer' => 37500, 'description' => 'Детейлинг салона'],
            ['category_id' => $fineId, 'date' => '2026-05-20', 'amount' => 1500, 'odometer' => 35000, 'description' => 'Штраф за превышение скорости'],
        ];

        foreach ($expensesBmw as $exp) {
            Expense::create(array_merge(['car_id' => $bmw->id], $exp));
        }

        // ===== LADA Vesta =====
        $expensesLada = [
            ['category_id' => $fuelId, 'date' => '2026-05-03', 'amount' => 2500, 'odometer' => 15200, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-05-14', 'amount' => 2800, 'odometer' => 16200, 'description' => 'Заправка Газпром'],
            ['category_id' => $fuelId, 'date' => '2026-05-28', 'amount' => 2600, 'odometer' => 17500, 'description' => 'Заправка Татнефть'],
            ['category_id' => $fuelId, 'date' => '2026-06-08', 'amount' => 2700, 'odometer' => 18800, 'description' => 'Заправка Лукойл'],
            ['category_id' => $repairId, 'date' => '2026-05-10', 'amount' => 3500, 'odometer' => 15800, 'description' => 'Замена масла'],
            ['category_id' => $repairId, 'date' => '2026-06-01', 'amount' => 2200, 'odometer' => 18000, 'description' => 'Замена воздушного фильтра'],
            ['category_id' => $taxId, 'date' => '2026-05-25', 'amount' => 4500, 'odometer' => 0, 'description' => 'Транспортный налог'],
            ['category_id' => $washingId, 'date' => '2026-06-05', 'amount' => 400, 'odometer' => 18500, 'description' => 'Мойка'],
        ];

        foreach ($expensesLada as $exp) {
            Expense::create(array_merge(['car_id' => $lada->id], $exp));
        }

        // ===== BMW M5 G90 (2024) =====
        $expensesBmwM5 = [
            ['category_id' => $fuelId, 'date' => '2026-05-04', 'amount' => 6500, 'odometer' => 5200, 'description' => 'Заправка Shell V-Power'],
            ['category_id' => $fuelId, 'date' => '2026-05-16', 'amount' => 7000, 'odometer' => 6200, 'description' => 'Заправка BP'],
            ['category_id' => $fuelId, 'date' => '2026-05-30', 'amount' => 6800, 'odometer' => 7200, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-06-10', 'amount' => 7200, 'odometer' => 8200, 'description' => 'Заправка Shell V-Power'],
            ['category_id' => $repairId, 'date' => '2026-05-08', 'amount' => 3000, 'odometer' => 5500, 'description' => 'Первое ТО (обкатка)'],
            ['category_id' => $repairId, 'date' => '2026-06-01', 'amount' => 15000, 'odometer' => 7500, 'description' => 'Замена масла в двигателе'],
            ['category_id' => $insuranceId, 'date' => '2026-05-20', 'amount' => 25000, 'odometer' => 0, 'description' => 'КАСКО'],
            ['category_id' => $washingId, 'date' => '2026-06-05', 'amount' => 1500, 'odometer' => 7800, 'description' => 'Детейлинг полировка'],
            ['category_id' => $fineId, 'date' => '2026-05-25', 'amount' => 3000, 'odometer' => 6800, 'description' => 'Штраф за парковку'],
            ['category_id' => $tiresId, 'date' => '2026-05-10', 'amount' => 22000, 'odometer' => 5800, 'description' => 'Комплект летних шин Michelin Pilot Sport 4S'],
            ['category_id' => $taxId, 'date' => '2026-06-01', 'amount' => 12000, 'odometer' => 0, 'description' => 'Транспортный налог'],
        ];

        foreach ($expensesBmwM5 as $exp) {
            Expense::create(array_merge(['car_id' => $bmwM5->id], $exp));
        }

        // ===== Kia Stinger =====
        $expensesStinger = [
            ['category_id' => $fuelId, 'date' => '2026-05-05', 'amount' => 4500, 'odometer' => 12200, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-05-17', 'amount' => 4800, 'odometer' => 13500, 'description' => 'Заправка Газпром'],
            ['category_id' => $fuelId, 'date' => '2026-05-29', 'amount' => 4600, 'odometer' => 14800, 'description' => 'Заправка Татнефть'],
            ['category_id' => $fuelId, 'date' => '2026-06-09', 'amount' => 4900, 'odometer' => 16200, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-06-15', 'amount' => 5200, 'odometer' => 17800, 'description' => 'Заправка Shell'],
            ['category_id' => $repairId, 'date' => '2026-05-10', 'amount' => 6000, 'odometer' => 12800, 'description' => 'Замена масла'],
            ['category_id' => $repairId, 'date' => '2026-06-01', 'amount' => 4500, 'odometer' => 15500, 'description' => 'Замена передних колодок'],
            ['category_id' => $tiresId, 'date' => '2026-05-15', 'amount' => 18000, 'odometer' => 13000, 'description' => 'Комплект зимней резины'],
            ['category_id' => $insuranceId, 'date' => '2026-06-10', 'amount' => 10000, 'odometer' => 0, 'description' => 'ОСАГО'],
            ['category_id' => $washingId, 'date' => '2026-06-03', 'amount' => 600, 'odometer' => 16000, 'description' => 'Мойка'],
            ['category_id' => $parkingId, 'date' => '2026-05-20', 'amount' => 500, 'odometer' => 14000, 'description' => 'Платная парковка'],
            ['category_id' => $fineId, 'date' => '2026-06-05', 'amount' => 1000, 'odometer' => 16500, 'description' => 'Штраф за неправильную парковку'],
        ];

        foreach ($expensesStinger as $exp) {
            Expense::create(array_merge(['car_id' => $stinger->id], $exp));
        }

        // ===== Mercedes-Benz S-Class (для комиссии) =====
        $expensesMercedes = [
            ['category_id' => $fuelId, 'date' => '2026-05-06', 'amount' => 6000, 'odometer' => 8500, 'description' => 'Заправка Shell'],
            ['category_id' => $fuelId, 'date' => '2026-05-18', 'amount' => 5800, 'odometer' => 9500, 'description' => 'Заправка BP'],
            ['category_id' => $fuelId, 'date' => '2026-06-02', 'amount' => 6200, 'odometer' => 10800, 'description' => 'Заправка Лукойл'],
            ['category_id' => $repairId, 'date' => '2026-05-12', 'amount' => 18000, 'odometer' => 9000, 'description' => 'Плановое ТО'],
            ['category_id' => $insuranceId, 'date' => '2026-05-25', 'amount' => 30000, 'odometer' => 0, 'description' => 'КАСКО'],
            ['category_id' => $washingId, 'date' => '2026-06-01', 'amount' => 1000, 'odometer' => 10500, 'description' => 'Мойка детейлинг'],
            ['category_id' => $tiresId, 'date' => '2026-05-08', 'amount' => 28000, 'odometer' => 8600, 'description' => 'Комплект зимней резины'],
            ['category_id' => $taxId, 'date' => '2026-06-01', 'amount' => 15000, 'odometer' => 0, 'description' => 'Транспортный налог'],
        ];

        foreach ($expensesMercedes as $exp) {
            Expense::create(array_merge(['car_id' => $mercedes->id], $exp));
        }

        // ===== Audi Q7 (для комиссии) =====
        $expensesAudi = [
            ['category_id' => $fuelId, 'date' => '2026-05-07', 'amount' => 5200, 'odometer' => 15500, 'description' => 'Заправка Лукойл'],
            ['category_id' => $fuelId, 'date' => '2026-05-20', 'amount' => 5400, 'odometer' => 16800, 'description' => 'Заправка Газпром'],
            ['category_id' => $fuelId, 'date' => '2026-06-03', 'amount' => 5600, 'odometer' => 18200, 'description' => 'Заправка Shell'],
            ['category_id' => $repairId, 'date' => '2026-05-14', 'amount' => 15000, 'odometer' => 16000, 'description' => 'Замена масла и фильтров'],
            ['category_id' => $repairId, 'date' => '2026-06-01', 'amount' => 8000, 'odometer' => 17500, 'description' => 'Замена тормозных колодок'],
            ['category_id' => $insuranceId, 'date' => '2026-05-28', 'amount' => 20000, 'odometer' => 0, 'description' => 'ОСАГО'],
            ['category_id' => $washingId, 'date' => '2026-05-30', 'amount' => 700, 'odometer' => 17200, 'description' => 'Мойка'],
            ['category_id' => $fineId, 'date' => '2026-06-05', 'amount' => 1200, 'odometer' => 18000, 'description' => 'Штраф за парковку'],
        ];

        foreach ($expensesAudi as $exp) {
            Expense::create(array_merge(['car_id' => $audi->id], $exp));
        }

        // === 9. ЗАПРАВКИ ===
        
        // Toyota Camry
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-05-01', 'liters' => 40, 'price_per_liter' => 87.50, 'odometer' => 45200, 'gas_station' => 'Лукойл', 'total_amount' => 3500]);
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-05-10', 'liters' => 38, 'price_per_liter' => 84.20, 'odometer' => 46500, 'gas_station' => 'Газпром', 'total_amount' => 3200]);
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-05-18', 'liters' => 42, 'price_per_liter' => 85.70, 'odometer' => 47500, 'gas_station' => 'Татнефть', 'total_amount' => 3600]);
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-06-01', 'liters' => 40, 'price_per_liter' => 85.00, 'odometer' => 48800, 'gas_station' => 'Лукойл', 'total_amount' => 3400]);
        Refueling::create(['car_id' => $toyota->id, 'date' => '2026-06-12', 'liters' => 45, 'price_per_liter' => 84.44, 'odometer' => 50200, 'gas_station' => 'Газпром', 'total_amount' => 3800]);

        // BMW X5
        Refueling::create(['car_id' => $bmw->id, 'date' => '2026-05-02', 'liters' => 55, 'price_per_liter' => 90.91, 'odometer' => 32500, 'gas_station' => 'Лукойл', 'total_amount' => 5000]);
        Refueling::create(['car_id' => $bmw->id, 'date' => '2026-05-12', 'liters' => 52, 'price_per_liter' => 92.30, 'odometer' => 34000, 'gas_station' => 'BP', 'total_amount' => 4800]);
        Refueling::create(['car_id' => $bmw->id, 'date' => '2026-05-25', 'liters' => 58, 'price_per_liter' => 89.65, 'odometer' => 35500, 'gas_station' => 'Shell', 'total_amount' => 5200]);
        Refueling::create(['car_id' => $bmw->id, 'date' => '2026-06-05', 'liters' => 55, 'price_per_liter' => 89.09, 'odometer' => 37000, 'gas_station' => 'Лукойл', 'total_amount' => 4900]);
        Refueling::create(['car_id' => $bmw->id, 'date' => '2026-06-18', 'liters' => 60, 'price_per_liter' => 91.67, 'odometer' => 38800, 'gas_station' => 'BP', 'total_amount' => 5500]);

        // LADA Vesta
        Refueling::create(['car_id' => $lada->id, 'date' => '2026-05-03', 'liters' => 33, 'price_per_liter' => 75.76, 'odometer' => 15200, 'gas_station' => 'Лукойл', 'total_amount' => 2500]);
        Refueling::create(['car_id' => $lada->id, 'date' => '2026-05-14', 'liters' => 38, 'price_per_liter' => 73.68, 'odometer' => 16200, 'gas_station' => 'Газпром', 'total_amount' => 2800]);
        Refueling::create(['car_id' => $lada->id, 'date' => '2026-05-28', 'liters' => 35, 'price_per_liter' => 74.29, 'odometer' => 17500, 'gas_station' => 'Татнефть', 'total_amount' => 2600]);
        Refueling::create(['car_id' => $lada->id, 'date' => '2026-06-08', 'liters' => 36, 'price_per_liter' => 75.00, 'odometer' => 18800, 'gas_station' => 'Лукойл', 'total_amount' => 2700]);

        // BMW M5 G90 (2024)
        Refueling::create(['car_id' => $bmwM5->id, 'date' => '2026-05-04', 'liters' => 60, 'price_per_liter' => 108.33, 'odometer' => 5200, 'gas_station' => 'Shell V-Power', 'total_amount' => 6500]);
        Refueling::create(['car_id' => $bmwM5->id, 'date' => '2026-05-16', 'liters' => 62, 'price_per_liter' => 112.90, 'odometer' => 6200, 'gas_station' => 'BP Ultimate', 'total_amount' => 7000]);
        Refueling::create(['car_id' => $bmwM5->id, 'date' => '2026-05-30', 'liters' => 58, 'price_per_liter' => 117.24, 'odometer' => 7200, 'gas_station' => 'Лукойл', 'total_amount' => 6800]);
        Refueling::create(['car_id' => $bmwM5->id, 'date' => '2026-06-10', 'liters' => 60, 'price_per_liter' => 120.00, 'odometer' => 8200, 'gas_station' => 'Shell V-Power', 'total_amount' => 7200]);

        // Kia Stinger
        Refueling::create(['car_id' => $stinger->id, 'date' => '2026-05-05', 'liters' => 48, 'price_per_liter' => 93.75, 'odometer' => 12200, 'gas_station' => 'Лукойл', 'total_amount' => 4500]);
        Refueling::create(['car_id' => $stinger->id, 'date' => '2026-05-17', 'liters' => 50, 'price_per_liter' => 96.00, 'odometer' => 13500, 'gas_station' => 'Газпром', 'total_amount' => 4800]);
        Refueling::create(['car_id' => $stinger->id, 'date' => '2026-05-29', 'liters' => 48, 'price_per_liter' => 95.83, 'odometer' => 14800, 'gas_station' => 'Татнефть', 'total_amount' => 4600]);
        Refueling::create(['car_id' => $stinger->id, 'date' => '2026-06-09', 'liters' => 50, 'price_per_liter' => 98.00, 'odometer' => 16200, 'gas_station' => 'Лукойл', 'total_amount' => 4900]);
        Refueling::create(['car_id' => $stinger->id, 'date' => '2026-06-15', 'liters' => 52, 'price_per_liter' => 100.00, 'odometer' => 17800, 'gas_station' => 'Shell', 'total_amount' => 5200]);

        // Mercedes-Benz S-Class (комиссия)
        Refueling::create(['car_id' => $mercedes->id, 'date' => '2026-05-06', 'liters' => 55, 'price_per_liter' => 109.09, 'odometer' => 8500, 'gas_station' => 'Shell', 'total_amount' => 6000]);
        Refueling::create(['car_id' => $mercedes->id, 'date' => '2026-05-18', 'liters' => 52, 'price_per_liter' => 111.54, 'odometer' => 9500, 'gas_station' => 'BP', 'total_amount' => 5800]);
        Refueling::create(['car_id' => $mercedes->id, 'date' => '2026-06-02', 'liters' => 56, 'price_per_liter' => 110.71, 'odometer' => 10800, 'gas_station' => 'Лукойл', 'total_amount' => 6200]);

        // Audi Q7 (комиссия)
        Refueling::create(['car_id' => $audi->id, 'date' => '2026-05-07', 'liters' => 58, 'price_per_liter' => 89.66, 'odometer' => 15500, 'gas_station' => 'Лукойл', 'total_amount' => 5200]);
        Refueling::create(['car_id' => $audi->id, 'date' => '2026-05-20', 'liters' => 60, 'price_per_liter' => 90.00, 'odometer' => 16800, 'gas_station' => 'Газпром', 'total_amount' => 5400]);
        Refueling::create(['car_id' => $audi->id, 'date' => '2026-06-03', 'liters' => 62, 'price_per_liter' => 90.32, 'odometer' => 18200, 'gas_station' => 'Shell', 'total_amount' => 5600]);

        // === 10. ДОХОДЫ ===
        Income::create(['car_id' => $toyota->id, 'date' => '2026-05-15', 'title' => 'Кэшбэк от АЗС', 'amount' => 500, 'odometer' => 0, 'description' => 'Бонусы Лукойл']);
        Income::create(['car_id' => $toyota->id, 'date' => '2026-05-20', 'title' => 'Возврат за ремонт', 'amount' => 2000, 'odometer' => 47000, 'description' => 'Гарантийный случай']);
        Income::create(['car_id' => $bmw->id, 'date' => '2026-06-01', 'title' => 'Страховая выплата', 'amount' => 5000, 'odometer' => 0, 'description' => 'Возврат за КАСКО']);
        Income::create(['car_id' => $stinger->id, 'date' => '2026-05-25', 'title' => 'Продажа старых дисков', 'amount' => 3000, 'odometer' => 14500, 'description' => 'Продажа летних дисков']);
        Income::create(['car_id' => $bmwM5->id, 'date' => '2026-05-20', 'title' => 'Бонус от дилера', 'amount' => 10000, 'odometer' => 0, 'description' => 'Кэшбэк при покупке']);
        Income::create(['car_id' => $mercedes->id, 'date' => '2026-05-30', 'title' => 'Кэшбэк от АЗС', 'amount' => 800, 'odometer' => 0, 'description' => 'Бонусы Shell']);
        Income::create(['car_id' => $audi->id, 'date' => '2026-06-01', 'title' => 'Возврат за обслуживание', 'amount' => 3000, 'odometer' => 17500, 'description' => 'Гарантийный ремонт']);

        // === 11. НАПОМИНАНИЯ ===
        Reminder::create(['car_id' => $toyota->id, 'title' => 'Замена масла', 'due_odometer' => 60000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $toyota->id, 'title' => 'Замена ремня ГРМ', 'due_odometer' => 70000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $toyota->id, 'title' => 'Замена тормозных колодок', 'due_odometer' => 65000, 'due_date' => null, 'is_completed' => false]);
        
        Reminder::create(['car_id' => $bmw->id, 'title' => 'Замена тормозных колодок', 'due_odometer' => 45000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $bmw->id, 'title' => 'Техосмотр', 'due_odometer' => 0, 'due_date' => '2026-10-01', 'is_completed' => false]);
        Reminder::create(['car_id' => $bmw->id, 'title' => 'Замена масла в коробке', 'due_odometer' => 50000, 'due_date' => null, 'is_completed' => false]);
        
        Reminder::create(['car_id' => $lada->id, 'title' => 'Замена масла', 'due_odometer' => 25000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $lada->id, 'title' => 'Замена свечей', 'due_odometer' => 30000, 'due_date' => null, 'is_completed' => false]);
        
        Reminder::create(['car_id' => $bmwM5->id, 'title' => 'Замена масла в двигателе', 'due_odometer' => 15000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $bmwM5->id, 'title' => 'Замена тормозных колодок (карбон-керамика)', 'due_odometer' => 30000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $bmwM5->id, 'title' => 'Техосмотр', 'due_odometer' => 0, 'due_date' => '2027-05-01', 'is_completed' => false]);
        Reminder::create(['car_id' => $bmwM5->id, 'title' => 'Замена фильтра салона', 'due_odometer' => 20000, 'due_date' => null, 'is_completed' => false]);
        
        Reminder::create(['car_id' => $stinger->id, 'title' => 'Замена масла', 'due_odometer' => 25000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $stinger->id, 'title' => 'Замена тормозных колодок', 'due_odometer' => 30000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $stinger->id, 'title' => 'Замена свечей зажигания', 'due_odometer' => 35000, 'due_date' => null, 'is_completed' => false]);

        // Напоминания для комиссии
        Reminder::create(['car_id' => $mercedes->id, 'title' => 'Замена масла', 'due_odometer' => 15000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $mercedes->id, 'title' => 'Замена тормозных колодок', 'due_odometer' => 20000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $mercedes->id, 'title' => 'Техосмотр', 'due_odometer' => 0, 'due_date' => '2027-06-01', 'is_completed' => false]);
        
        Reminder::create(['car_id' => $audi->id, 'title' => 'Замена масла', 'due_odometer' => 25000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $audi->id, 'title' => 'Замена тормозных колодок', 'due_odometer' => 30000, 'due_date' => null, 'is_completed' => false]);
        Reminder::create(['car_id' => $audi->id, 'title' => 'Замена воздушного фильтра', 'due_odometer' => 28000, 'due_date' => null, 'is_completed' => false]);

        // === 12. ВЫВОД ИНФОРМАЦИИ ===
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('✅ Демо-данные успешно созданы!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('👤 Администратор: admin@autocost.ru / admin123');
        $this->command->info('👤 Основной пользователь: autocost774@mail.ru / user123');
        $this->command->info('👤 Комиссия: user@gmail.com / user123 ⭐');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('🚗 Автомобили (основной пользователь):');
        $this->command->info('  1. Toyota Camry 2020');
        $this->command->info('  2. BMW X5 2021');
        $this->command->info('  3. LADA Vesta 2022');
        $this->command->info('  4. BMW M5 G90 2024');
        $this->command->info('  5. Kia Stinger 2022');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('🚗 Автомобили (комиссия):');
        $this->command->info('  6. Mercedes-Benz S-Class 2023');
        $this->command->info('  7. Audi Q7 2022');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📊 Статистика:');
        $this->command->info('  Расходов: ' . Expense::count());
        $this->command->info('  Заправок: ' . Refueling::count());
        $this->command->info('  Доходов: ' . Income::count());
        $this->command->info('  Напоминаний: ' . Reminder::count());
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📸 Если фото не отображаются, выполните:');
        $this->command->info('  php artisan storage:link');
    }
}