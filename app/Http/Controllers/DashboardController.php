<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Получаем автомобили пользователя
        $cars = Auth::user()->cars;
        
        // Выбранный автомобиль (по умолчанию первый)
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        if (!$selectedCar) {
            return view('dashboard', [
                'cars' => $cars,
                'selectedCar' => null,
                'totalExpenses' => 0,
                'totalFuelCost' => 0,
                'avgFuelConsumption' => 0,
                'costPerKm' => 0,
                'expensesByCategory' => [],
                'expensesByMonth' => [],
                'fuelConsumptionHistory' => [],
                'recentExpenses' => collect(),
                'recentRefuelings' => collect(),
            ]);
        }
        
        // 1. Общая статистика
        $totalExpenses = $this->getTotalExpenses($selectedCarId);
        $totalFuelCost = $this->getTotalFuelCost($selectedCarId);
        $avgFuelConsumption = $this->getAvgFuelConsumption($selectedCarId);
        $costPerKm = $this->getCostPerKm($selectedCarId);
        
        // 2. Расходы по категориям (для круговой диаграммы)
        $expensesByCategory = $this->getExpensesByCategory($selectedCarId);
        
        // 3. Динамика расходов по месяцам (для линейного графика)
        $expensesByMonth = $this->getExpensesByMonth($selectedCarId);
        
        // 4. История расхода топлива
        $fuelConsumptionHistory = $this->getFuelConsumptionHistory($selectedCarId);
        
        // 5. Последние операции
        $recentExpenses = Expense::where('car_id', $selectedCarId)
            ->with('category')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
        
        $recentRefuelings = Refueling::where('car_id', $selectedCarId)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'cars',
            'selectedCar',
            'selectedCarId',
            'totalExpenses',
            'totalFuelCost',
            'avgFuelConsumption',
            'costPerKm',
            'expensesByCategory',
            'expensesByMonth',
            'fuelConsumptionHistory',
            'recentExpenses',
            'recentRefuelings'
        ));
    }
    
    /**
     * Общая сумма всех расходов
     */
    private function getTotalExpenses($carId)
    {
        // Расходы (включая топливо из refuelings)
        $expensesSum = Expense::where('car_id', $carId)->sum('amount');
        $refuelingsSum = Refueling::where('car_id', $carId)->sum('total_amount');
        
        return $expensesSum + $refuelingsSum;
    }
    
    /**
     * Общая сумма затрат на топливо
     */
    private function getTotalFuelCost($carId)
    {
        return Refueling::where('car_id', $carId)->sum('total_amount');
    }
    
    /**
     * Средний расход топлива (л/100 км)
     */
    private function getAvgFuelConsumption($carId)
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
     * Стоимость одного километра (руб/км)
     */
    private function getCostPerKm($carId)
    {
        $totalCost = $this->getTotalExpenses($carId);
        
        // Получаем максимальный пробег
        $maxOdometer = Refueling::where('car_id', $carId)->max('odometer');
        if (!$maxOdometer) {
            $maxOdometer = Expense::where('car_id', $carId)->max('odometer');
        }
        
        $car = Car::find($carId);
        $initialOdometer = $car->initial_odometer ?? 0;
        $totalDistance = ($maxOdometer ?? $initialOdometer) - $initialOdometer;
        
        if ($totalDistance <= 0 || $totalCost <= 0) {
            return 0;
        }
        
        return round($totalCost / $totalDistance, 2);
    }
    
    /**
     * Расходы по категориям (для круговой диаграммы)
     */
    private function getExpensesByCategory($carId)
    {
        // Расходы по категориям из таблицы expenses
        $expensesByCat = Expense::where('car_id', $carId)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get();
        
        // Заправки как отдельная категория "Топливо"
        $fuelTotal = Refueling::where('car_id', $carId)->sum('total_amount');
        
        $result = [];
        
        foreach ($expensesByCat as $item) {
            if ($item->category) {
                $result[] = [
                    'name' => $item->category->name,
                    'amount' => $item->total,
                ];
            }
        }
        
        if ($fuelTotal > 0) {
            $result[] = [
                'name' => 'Топливо',
                'amount' => $fuelTotal,
            ];
        }
        
        return $result;
    }
    
    /**
     * Динамика расходов по месяцам
     */
    private function getExpensesByMonth($carId)
    {
        $months = collect();
        
        // Последние 6 месяцев
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }
        
        $result = [];
        
        foreach ($months as $month) {
            $year = substr($month, 0, 4);
            $monthNum = substr($month, 5, 2);
            
            // Расходы из таблицы expenses
            $expensesSum = Expense::where('car_id', $carId)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->sum('amount');
            
            // Заправки
            $refuelingsSum = Refueling::where('car_id', $carId)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->sum('total_amount');
            
            $result[] = [
                'month' => $month,
                'total' => $expensesSum + $refuelingsSum,
            ];
        }
        
        return $result;
    }
    
    /**
     * История расхода топлива
     */
    private function getFuelConsumptionHistory($carId)
    {
        $refuelings = Refueling::where('car_id', $carId)
            ->orderBy('date', 'asc')
            ->get();
        
        $result = [];
        $prevOdometer = null;
        
        foreach ($refuelings as $refueling) {
            if ($prevOdometer !== null) {
                $distance = $refueling->odometer - $prevOdometer;
                if ($distance > 0) {
                    $consumption = round(($refueling->liters / $distance) * 100, 1);
                    $result[] = [
                        'date' => $refueling->date->format('Y-m-d'),
                        'consumption' => $consumption,
                    ];
                }
            }
            $prevOdometer = $refueling->odometer;
        }
        
        // Берём последние 10 записей
        return array_slice($result, -10);
    }
}