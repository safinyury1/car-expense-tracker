<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Страховка', 'is_default' => true, 'user_id' => null],
            ['name' => 'Штраф', 'is_default' => true, 'user_id' => null],
            ['name' => 'Покупка авто', 'is_default' => true, 'user_id' => null],
            ['name' => 'Платеж по кредиту', 'is_default' => true, 'user_id' => null],
            ['name' => 'Ремонт', 'is_default' => true, 'user_id' => null],
            ['name' => 'Платная дорога', 'is_default' => true, 'user_id' => null],
            ['name' => 'Парковка', 'is_default' => true, 'user_id' => null],
            ['name' => 'Прочие расходы', 'is_default' => true, 'user_id' => null],
            ['name' => 'Зарплата', 'is_default' => true, 'user_id' => null],
            ['name' => 'Обслуживание', 'is_default' => true, 'user_id' => null],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::updateOrCreate(
                ['name' => $category['name'], 'is_default' => true],
                ['user_id' => null, 'is_default' => true]
            );
        }
    }
}