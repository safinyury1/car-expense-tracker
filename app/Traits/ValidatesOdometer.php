<?php

namespace App\Traits;

use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Income;
use Illuminate\Validation\ValidationException;

trait ValidatesOdometer
{
    /**
     * Проверка, что новый пробег больше предыдущего
     *
     * @param int $carId
     * @param int $newOdometer
     * @param int|null $excludeId ID записи при редактировании (исключаем её из проверки)
     * @param string $type 'expense', 'refueling', 'income' или 'service'
     * @return void
     * @throws ValidationException
     */
    protected function validateOdometer($carId, $newOdometer, $excludeId = null, $type = 'expense')
    {
        // Получаем максимальный пробег из расходов
        $maxOdometerExpense = Expense::where('car_id', $carId)
            ->when($excludeId && $type === 'expense', function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->max('odometer');
        
        // Получаем максимальный пробег из заправок
        $maxOdometerRefueling = Refueling::where('car_id', $carId)
            ->when($excludeId && $type === 'refueling', function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->max('odometer');
        
        // Получаем максимальный пробег из доходов
        $maxOdometerIncome = Income::where('car_id', $carId)
            ->when($excludeId && $type === 'income', function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->max('odometer');
        
        $maxOdometer = max($maxOdometerExpense, $maxOdometerRefueling, $maxOdometerIncome);
        
        // Если есть записи с пробегом, проверяем
        if ($maxOdometer !== null && $newOdometer < $maxOdometer) {
            throw ValidationException::withMessages([
                'odometer' => "Пробег не может быть меньше предыдущего значения ({$maxOdometer} км).",
            ]);
        }
    }
}