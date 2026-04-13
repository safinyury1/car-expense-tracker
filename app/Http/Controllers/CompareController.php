<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompareController extends Controller
{
    /**
     * Страница сравнения автомобилей
     */
    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        
        // Получаем ID выбранных автомобилей (максимум 4)
        $selectedCarIds = $request->get('cars', []);
        if (!is_array($selectedCarIds)) {
            $selectedCarIds = [];
        }
        
        // Ограничиваем максимум 4 автомобиля
        $selectedCarIds = array_slice($selectedCarIds, 0, 4);
        
        $selectedCars = Car::whereIn('id', $selectedCarIds)
            ->where('user_id', Auth::id())
            ->get();
        
        $comparisonData = [];
        
        foreach ($selectedCars as $car) {
            $comparisonData[$car->id] = $this->getCarStatistics($car->id);
        }
        
        // Данные для графика общих расходов
        $chartData = $this->getComparisonChartData($selectedCarIds);
        
        // Данные для круговой диаграммы по каждому автомобилю
        $expenseDistributionData = [];
        foreach ($selectedCars as $car) {
            $expenseDistributionData[$car->id] = $this->getExpenseDistribution($car->id);
        }
        
        // Данные для линейного графика динамики расходов
        $monthlyTrendData = $this->getMonthlyTrendData($selectedCarIds);
        
        return view('compare.index', compact(
            'cars',
            'selectedCars',
            'selectedCarIds',
            'comparisonData',
            'chartData',
            'expenseDistributionData',
            'monthlyTrendData'
        ));
    }
    
    /**
     * Получение статистики по автомобилю
     */
    private function getCarStatistics($carId)
    {
        $car = Car::find($carId);
        
        // Общие расходы
        $expensesSum = Expense::where('car_id', $carId)->sum('amount');
        $refuelingsSum = Refueling::where('car_id', $carId)->sum('total_amount');
        $totalExpenses = $expensesSum + $refuelingsSum;
        
        // Затраты на топливо
        $totalFuelCost = Refueling::where('car_id', $carId)->sum('total_amount');
        
        // Средний расход топлива
        $avgFuelConsumption = $this->calculateAvgFuelConsumption($carId);
        
        // Стоимость 1 км
        $costPerKm = $this->calculateCostPerKm($carId, $totalExpenses);
        
        // Количество расходов
        $expensesCount = Expense::where('car_id', $carId)->count();
        
        // Количество заправок
        $refuelingsCount = Refueling::where('car_id', $carId)->count();
        
        // Общий пробег
        $maxOdometer = Refueling::where('car_id', $carId)->max('odometer');
        if (!$maxOdometer) {
            $maxOdometer = Expense::where('car_id', $carId)->max('odometer');
        }
        $totalDistance = ($maxOdometer ?? $car->initial_odometer) - $car->initial_odometer;
        
        return [
            'car' => $car,
            'totalExpenses' => $totalExpenses,
            'totalFuelCost' => $totalFuelCost,
            'avgFuelConsumption' => $avgFuelConsumption,
            'costPerKm' => $costPerKm,
            'expensesCount' => $expensesCount,
            'refuelingsCount' => $refuelingsCount,
            'totalDistance' => max($totalDistance, 0),
        ];
    }
    
    /**
     * Расчёт среднего расхода топлива
     */
    private function calculateAvgFuelConsumption($carId)
    {
        $refuelings = Refueling::where('car_id', $carId)
            ->orderBy('date', 'asc')
            ->get();
        
        if ($refuelings->count() < 2) {
            return 0;
        }
        
        $totalLiters = 0;
        $totalDistance = 0;
        $prevOdometer = null;
        
        foreach ($refuelings as $refueling) {
            if ($prevOdometer !== null) {
                $distance = $refueling->odometer - $prevOdometer;
                if ($distance > 0) {
                    $totalLiters += $refueling->liters;
                    $totalDistance += $distance;
                }
            }
            $prevOdometer = $refueling->odometer;
        }
        
        if ($totalDistance == 0) {
            return 0;
        }
        
        return round(($totalLiters / $totalDistance) * 100, 1);
    }
    
    /**
     * Расчёт стоимости 1 км
     */
    private function calculateCostPerKm($carId, $totalExpenses)
    {
        $car = Car::find($carId);
        
        $maxOdometer = Refueling::where('car_id', $carId)->max('odometer');
        if (!$maxOdometer) {
            $maxOdometer = Expense::where('car_id', $carId)->max('odometer');
        }
        
        $totalDistance = ($maxOdometer ?? $car->initial_odometer) - $car->initial_odometer;
        
        if ($totalDistance <= 0 || $totalExpenses <= 0) {
            return 0;
        }
        
        return round($totalExpenses / $totalDistance, 2);
    }
    
    /**
     * Данные для графика сравнения (столбчатая диаграмма)
     */
    private function getComparisonChartData($carIds)
    {
        $data = [];
        
        foreach ($carIds as $carId) {
            $car = Car::find($carId);
            if (!$car) continue;
            
            $expensesSum = Expense::where('car_id', $carId)->sum('amount');
            $refuelingsSum = Refueling::where('car_id', $carId)->sum('total_amount');
            
            $data[] = [
                'name' => $car->brand . ' ' . $car->model,
                'expenses' => round($expensesSum + $refuelingsSum, 2),
                'fuel' => round($refuelingsSum, 2),
            ];
        }
        
        return $data;
    }
    
    /**
     * Распределение расходов по категориям для круговой диаграммы
     */
    private function getExpenseDistribution($carId)
    {
        // Расходы по категориям
        $expensesByCat = Expense::where('car_id', $carId)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get();
        
        // Заправки как отдельная категория
        $fuelTotal = Refueling::where('car_id', $carId)->sum('total_amount');
        
        $result = [];
        
        foreach ($expensesByCat as $item) {
            if ($item->category) {
                $result[] = [
                    'name' => $item->category->name,
                    'amount' => round($item->total, 2),
                ];
            }
        }
        
        if ($fuelTotal > 0) {
            $result[] = [
                'name' => 'Топливо',
                'amount' => round($fuelTotal, 2),
            ];
        }
        
        return $result;
    }
    
    /**
     * Динамика расходов по месяцам для сравнения
     */
    private function getMonthlyTrendData($carIds)
    {
        $months = collect();
        
        // Последние 6 месяцев
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }
        
        $result = [];
        
        foreach ($carIds as $carId) {
            $car = Car::find($carId);
            if (!$car) continue;
            
            $seriesData = [];
            
            foreach ($months as $month) {
                $year = substr($month, 0, 4);
                $monthNum = substr($month, 5, 2);
                
                $expensesSum = Expense::where('car_id', $carId)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthNum)
                    ->sum('amount');
                
                $refuelingsSum = Refueling::where('car_id', $carId)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthNum)
                    ->sum('total_amount');
                
                $seriesData[] = round($expensesSum + $refuelingsSum, 2);
            }
            
            $result[] = [
                'name' => $car->brand . ' ' . $car->model,
                'data' => $seriesData,
            ];
        }
        
        return [
            'months' => $months->toArray(),
            'series' => $result,
        ];
    }
}