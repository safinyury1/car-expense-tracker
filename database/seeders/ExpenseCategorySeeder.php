<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
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

        foreach ($categories as $category) {
            ExpenseCategory::updateOrCreate(
                ['name' => $category['name'], 'is_default' => true],
                $category
            );
        }
    }
}