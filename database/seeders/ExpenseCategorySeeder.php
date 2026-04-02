<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $categories = [
        ['name' => 'Топливо', 'icon' => 'fuel'],
        ['name' => 'Ремонт', 'icon' => 'repair'],
        ['name' => 'Страховка', 'icon' => 'insurance'],
        ['name' => 'Налог', 'icon' => 'tax'],
        ['name' => 'Мойка', 'icon' => 'wash'],
        ['name' => 'Штрафы', 'icon' => 'fine'],
        ['name' => 'Парковка', 'icon' => 'parking'],
        ['name' => 'Прочее', 'icon' => 'other'],
    ];

    foreach ($categories as $category) {
        \App\Models\ExpenseCategory::create($category);
    }
}
}
