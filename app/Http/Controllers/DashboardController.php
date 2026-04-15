<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        
        // Выбранный автомобиль (по умолчанию 'all' - все автомобили)
        $selectedCarId = $request->get('car_id', 'all');
        
        // Выбранный период
        $period = $request->get('period', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // Получаем данные в зависимости от выбора
        if ($selectedCarId === 'all') {
            $data = $this->getAllCarsData($period, $dateFrom, $dateTo);
            $selectedCar = null;
        } else {
            $selectedCar = $cars->find($selectedCarId);
            if (!$selectedCar) {
                return redirect()->route('dashboard')->with('error', 'Автомобиль не найден');
            }
            $data = $this->getSingleCarData($selectedCarId, $period, $dateFrom, $dateTo);
        }
        
        // Данные для графиков
        $chartData = $this->getChartData($selectedCarId, $period, $dateFrom, $dateTo);
        $monthlyData = $this->getMonthlyData($selectedCarId, $period, $dateFrom, $dateTo);
        $fuelHistory = $this->getFuelHistory($selectedCarId, $period, $dateFrom, $dateTo);
        
        // Инсайты
        $insights = $this->getInsights($selectedCarId, $period, $dateFrom, $dateTo);
        
        // Топ-3 расходов
        $topExpenses = $this->getTopExpenses($selectedCarId, $period, $dateFrom, $dateTo);
        
        return view('dashboard', compact(
            'cars', 
            'selectedCar', 
            'selectedCarId',
            'period',
            'dateFrom',
            'dateTo',
            'data',
            'chartData',
            'monthlyData',
            'fuelHistory',
            'insights',
            'topExpenses'
        ));
    }
    
    private function applyDateFilter($query, $period, $dateFrom, $dateTo)
    {
        if ($period === 'today') {
            $query->whereDate('date', today());
        } elseif ($period === 'week') {
            $query->whereDate('date', '>=', now()->subWeek());
        } elseif ($period === 'month') {
            $query->whereDate('date', '>=', now()->subMonth());
        } elseif ($period === 'custom' && $dateFrom && $dateTo) {
            $query->whereDate('date', '>=', $dateFrom)
                  ->whereDate('date', '<=', $dateTo);
        }
        return $query;
    }
    
    private function getAllCarsData($period, $dateFrom, $dateTo)
    {
        $carIds = Auth::user()->cars->pluck('id')->toArray();
        
        if (empty($carIds)) {
            return [
                'totalExpenses' => 0,
                'totalFuelCost' => 0,
                'avgFuelConsumption' => 0,
                'costPerKm' => 0,
                'totalDistance' => 0,
            ];
        }
        
        $expensesQuery = Expense::whereIn('car_id', $carIds);
        $refuelingsQuery = Refueling::whereIn('car_id', $carIds);
        
        $expensesQuery = $this->applyDateFilter($expensesQuery, $period, $dateFrom, $dateTo);
        $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
        
        $totalExpenses = $expensesQuery->sum('amount');
        $totalFuelCost = $refuelingsQuery->sum('total_amount');
        $allExpenses = $totalExpenses + $totalFuelCost;
        
        // Общий пробег по всем авто (за период)
        $totalDistance = 0;
        foreach (Auth::user()->cars as $car) {
            $maxOdometer = 0;
            if ($period === 'all') {
                $maxOdometer = max(
                    Expense::where('car_id', $car->id)->max('odometer') ?? 0,
                    Refueling::where('car_id', $car->id)->max('odometer') ?? 0,
                    $car->initial_odometer ?? 0
                );
            } else {
                $lastRecord = Expense::where('car_id', $car->id)
                    ->when($period === 'today', fn($q) => $q->whereDate('date', today()))
                    ->when($period === 'week', fn($q) => $q->whereDate('date', '>=', now()->subWeek()))
                    ->when($period === 'month', fn($q) => $q->whereDate('date', '>=', now()->subMonth()))
                    ->when($period === 'custom' && $dateFrom && $dateTo, fn($q) => $q->whereBetween('date', [$dateFrom, $dateTo]))
                    ->orderBy('odometer', 'desc')
                    ->first();
                    
                $lastRefueling = Refueling::where('car_id', $car->id)
                    ->when($period === 'today', fn($q) => $q->whereDate('date', today()))
                    ->when($period === 'week', fn($q) => $q->whereDate('date', '>=', now()->subWeek()))
                    ->when($period === 'month', fn($q) => $q->whereDate('date', '>=', now()->subMonth()))
                    ->when($period === 'custom' && $dateFrom && $dateTo, fn($q) => $q->whereBetween('date', [$dateFrom, $dateTo]))
                    ->orderBy('odometer', 'desc')
                    ->first();
                    
                $maxOdometer = max(
                    $lastRecord->odometer ?? 0,
                    $lastRefueling->odometer ?? 0,
                    $car->initial_odometer ?? 0
                );
            }
            $totalDistance += $maxOdometer - ($car->initial_odometer ?? 0);
        }
        
        $avgFuelConsumption = $this->calculateAvgFuelConsumptionAll($carIds, $period, $dateFrom, $dateTo);
        $costPerKm = $totalDistance > 0 ? round($allExpenses / $totalDistance, 2) : 0;
        
        return [
            'totalExpenses' => $allExpenses,
            'totalFuelCost' => $totalFuelCost,
            'avgFuelConsumption' => $avgFuelConsumption,
            'costPerKm' => $costPerKm,
            'totalDistance' => $totalDistance,
        ];
    }
    
    private function getSingleCarData($carId, $period, $dateFrom, $dateTo)
    {
        $expensesQuery = Expense::where('car_id', $carId);
        $refuelingsQuery = Refueling::where('car_id', $carId);
        
        $expensesQuery = $this->applyDateFilter($expensesQuery, $period, $dateFrom, $dateTo);
        $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
        
        $expensesSum = $expensesQuery->sum('amount');
        $refuelingsSum = $refuelingsQuery->sum('total_amount');
        $totalExpenses = $expensesSum + $refuelingsSum;
        
        $totalFuelCost = $refuelingsQuery->sum('total_amount');
        $avgFuelConsumption = $this->calculateAvgFuelConsumption($carId, $period, $dateFrom, $dateTo);
        $costPerKm = $this->calculateCostPerKm($carId, $totalExpenses, $period, $dateFrom, $dateTo);
        
        // Пробег за период
        if ($period === 'all') {
            $maxOdometer = max(
                Expense::where('car_id', $carId)->max('odometer') ?? 0,
                Refueling::where('car_id', $carId)->max('odometer') ?? 0
            );
        } else {
            $lastRecord = Expense::where('car_id', $carId)
                ->when($period === 'today', fn($q) => $q->whereDate('date', today()))
                ->when($period === 'week', fn($q) => $q->whereDate('date', '>=', now()->subWeek()))
                ->when($period === 'month', fn($q) => $q->whereDate('date', '>=', now()->subMonth()))
                ->when($period === 'custom' && $dateFrom && $dateTo, fn($q) => $q->whereBetween('date', [$dateFrom, $dateTo]))
                ->orderBy('odometer', 'desc')
                ->first();
                
            $lastRefueling = Refueling::where('car_id', $carId)
                ->when($period === 'today', fn($q) => $q->whereDate('date', today()))
                ->when($period === 'week', fn($q) => $q->whereDate('date', '>=', now()->subWeek()))
                ->when($period === 'month', fn($q) => $q->whereDate('date', '>=', now()->subMonth()))
                ->when($period === 'custom' && $dateFrom && $dateTo, fn($q) => $q->whereBetween('date', [$dateFrom, $dateTo]))
                ->orderBy('odometer', 'desc')
                ->first();
                
            $maxOdometer = max(
                $lastRecord->odometer ?? 0,
                $lastRefueling->odometer ?? 0
            );
        }
        
        $car = Car::find($carId);
        $totalDistance = ($maxOdometer > 0 ? $maxOdometer : ($car->initial_odometer ?? 0)) - ($car->initial_odometer ?? 0);
        
        return [
            'totalExpenses' => $totalExpenses,
            'totalFuelCost' => $totalFuelCost,
            'avgFuelConsumption' => $avgFuelConsumption,
            'costPerKm' => $costPerKm,
            'totalDistance' => max($totalDistance, 0),
        ];
    }
    
    private function getChartData($selectedCarId, $period, $dateFrom, $dateTo)
    {
        if ($selectedCarId === 'all') {
            $carIds = Auth::user()->cars->pluck('id')->toArray();
            $expensesQuery = Expense::whereIn('car_id', $carIds);
            $refuelingsQuery = Refueling::whereIn('car_id', $carIds);
        } else {
            $expensesQuery = Expense::where('car_id', $selectedCarId);
            $refuelingsQuery = Refueling::where('car_id', $selectedCarId);
        }
        
        $expensesQuery = $this->applyDateFilter($expensesQuery, $period, $dateFrom, $dateTo);
        $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
        
        $expensesByCat = $expensesQuery
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get();
        
        $fuelTotal = $refuelingsQuery->sum('total_amount');
        
        $categories = [];
        $amounts = [];
        
        foreach ($expensesByCat as $item) {
            if ($item->category) {
                $categories[] = $item->category->name;
                $amounts[] = round($item->total, 2);
            }
        }
        
        if ($fuelTotal > 0) {
            $categories[] = 'Топливо';
            $amounts[] = round($fuelTotal, 2);
        }
        
        return ['categories' => $categories, 'amounts' => $amounts];
    }
    
    private function getMonthlyData($selectedCarId, $period, $dateFrom, $dateTo)
    {
        $months = [];
        $monthlyTotals = [];
        
        // Для кастомного периода показываем только месяцы в диапазоне
        if ($period === 'custom' && $dateFrom && $dateTo) {
            $start = new \DateTime($dateFrom);
            $end = new \DateTime($dateTo);
            $interval = new \DateInterval('P1M');
            $dateRange = new \DatePeriod($start, $interval, $end->modify('+1 month'));
            
            foreach ($dateRange as $date) {
                $months[] = $date->format('M Y');
                $monthlyTotals[] = 0;
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $months[] = $month->format('M Y');
                $monthlyTotals[] = 0;
            }
        }
        
        // Заполняем данные по месяцам
        foreach ($months as $index => $monthLabel) {
            $monthDate = \DateTime::createFromFormat('M Y', $monthLabel);
            if (!$monthDate) continue;
            
            $year = $monthDate->format('Y');
            $monthNum = $monthDate->format('m');
            
            if ($selectedCarId === 'all') {
                $carIds = Auth::user()->cars->pluck('id')->toArray();
                $expensesQuery = Expense::whereIn('car_id', $carIds);
                $refuelingsQuery = Refueling::whereIn('car_id', $carIds);
            } else {
                $expensesQuery = Expense::where('car_id', $selectedCarId);
                $refuelingsQuery = Refueling::where('car_id', $selectedCarId);
            }
            
            $expensesQuery = $this->applyDateFilter($expensesQuery, $period, $dateFrom, $dateTo);
            $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
            
            $expensesSum = $expensesQuery
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->sum('amount');
            
            $refuelingsSum = $refuelingsQuery
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->sum('total_amount');
            
            $monthlyTotals[$index] = round($expensesSum + $refuelingsSum, 2);
        }
        
        return ['months' => $months, 'totals' => $monthlyTotals];
    }
    
    private function getFuelHistory($selectedCarId, $period, $dateFrom, $dateTo)
    {
        if ($selectedCarId === 'all') {
            $carIds = Auth::user()->cars->pluck('id')->toArray();
            $refuelingsQuery = Refueling::whereIn('car_id', $carIds);
        } else {
            $refuelingsQuery = Refueling::where('car_id', $selectedCarId);
        }
        
        $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
        $refuelings = $refuelingsQuery->orderBy('date', 'asc')->get();
        
        $history = [];
        $prevOdometer = null;
        $prevCarId = null;
        
        foreach ($refuelings as $refueling) {
            if ($prevOdometer !== null && $prevCarId === $refueling->car_id) {
                $distance = $refueling->odometer - $prevOdometer;
                if ($distance > 0) {
                    $consumption = round(($refueling->liters / $distance) * 100, 1);
                    $history[] = [
                        'date' => $refueling->date->format('d.m.Y'),
                        'consumption' => $consumption,
                    ];
                }
            }
            $prevOdometer = $refueling->odometer;
            $prevCarId = $refueling->car_id;
        }
        
        return array_slice($history, -10);
    }
    
    private function getInsights($selectedCarId, $period, $dateFrom, $dateTo)
    {
        if ($selectedCarId === 'all') {
            $carIds = Auth::user()->cars->pluck('id')->toArray();
            if (empty($carIds)) {
                return ['dailyAverage' => 0, 'averageExpense' => 0];
            }
            $expensesQuery = Expense::whereIn('car_id', $carIds);
            $refuelingsQuery = Refueling::whereIn('car_id', $carIds);
        } else {
            $expensesQuery = Expense::where('car_id', $selectedCarId);
            $refuelingsQuery = Refueling::where('car_id', $selectedCarId);
        }
        
        $expensesQuery = $this->applyDateFilter($expensesQuery, $period, $dateFrom, $dateTo);
        $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
        
        $expenses = $expensesQuery->get();
        $refuelings = $refuelingsQuery->get();
        
        $allTransactions = collect();
        
        foreach ($expenses as $expense) {
            $allTransactions->push([
                'amount' => $expense->amount,
                'date' => $expense->date,
            ]);
        }
        
        foreach ($refuelings as $refueling) {
            $allTransactions->push([
                'amount' => $refueling->total_amount,
                'date' => $refueling->date,
            ]);
        }
        
        if ($allTransactions->isEmpty()) {
            return ['dailyAverage' => 0, 'averageExpense' => 0];
        }
        
        $firstDate = $allTransactions->min('date');
        $lastDate = $allTransactions->max('date');
        $daysDiff = max(1, $firstDate->diffInDays($lastDate) + 1);
        $totalAmount = $allTransactions->sum('amount');
        $dailyAverage = round($totalAmount / $daysDiff, 2);
        $averageExpense = round($totalAmount / $allTransactions->count(), 2);
        
        return [
            'dailyAverage' => $dailyAverage,
            'averageExpense' => $averageExpense,
        ];
    }
    
    private function getTopExpenses($selectedCarId, $period, $dateFrom, $dateTo)
    {
        if ($selectedCarId === 'all') {
            $carIds = Auth::user()->cars->pluck('id')->toArray();
            if (empty($carIds)) {
                return [];
            }
            $expensesQuery = Expense::whereIn('car_id', $carIds);
        } else {
            $expensesQuery = Expense::where('car_id', $selectedCarId);
        }
        
        $expensesQuery = $this->applyDateFilter($expensesQuery, $period, $dateFrom, $dateTo);
        $expenses = $expensesQuery->with('category', 'car')
            ->orderBy('amount', 'desc')
            ->limit(3)
            ->get();
        
        return $expenses->map(function ($expense) use ($selectedCarId) {
            $result = [
                'title' => $expense->description ?: $expense->category->name,
                'amount' => $expense->amount,
                'date' => $expense->date->format('d.m.Y'),
                'odometer' => $expense->odometer,
                'category' => $expense->category->name,
            ];
            if ($selectedCarId === 'all') {
                $result['car'] = $expense->car->brand . ' ' . $expense->car->model;
            }
            return $result;
        })->toArray();
    }
    
    private function calculateAvgFuelConsumption($carId, $period, $dateFrom, $dateTo)
    {
        $refuelingsQuery = Refueling::where('car_id', $carId);
        $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
        $refuelings = $refuelingsQuery->orderBy('date', 'asc')->get();
        
        if ($refuelings->count() < 2) return 0;
        
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
        
        return $totalDistance > 0 ? round(($totalLiters / $totalDistance) * 100, 1) : 0;
    }
    
    private function calculateAvgFuelConsumptionAll($carIds, $period, $dateFrom, $dateTo)
    {
        $refuelingsQuery = Refueling::whereIn('car_id', $carIds);
        $refuelingsQuery = $this->applyDateFilter($refuelingsQuery, $period, $dateFrom, $dateTo);
        $refuelings = $refuelingsQuery->orderBy('date', 'asc')->get();
        
        if ($refuelings->count() < 2) return 0;
        
        $totalLiters = 0;
        $totalDistance = 0;
        $prevOdometer = null;
        $prevCarId = null;
        
        foreach ($refuelings as $refueling) {
            if ($prevOdometer !== null && $prevCarId === $refueling->car_id) {
                $distance = $refueling->odometer - $prevOdometer;
                if ($distance > 0) {
                    $totalLiters += $refueling->liters;
                    $totalDistance += $distance;
                }
            }
            $prevOdometer = $refueling->odometer;
            $prevCarId = $refueling->car_id;
        }
        
        return $totalDistance > 0 ? round(($totalLiters / $totalDistance) * 100, 1) : 0;
    }
    
    private function calculateCostPerKm($carId, $totalExpenses, $period, $dateFrom, $dateTo)
    {
        $car = Car::find($carId);
        
        if ($period === 'all') {
            $maxOdometer = max(
                Expense::where('car_id', $carId)->max('odometer') ?? 0,
                Refueling::where('car_id', $carId)->max('odometer') ?? 0
            );
        } else {
            $lastRecord = Expense::where('car_id', $carId)
                ->when($period === 'today', fn($q) => $q->whereDate('date', today()))
                ->when($period === 'week', fn($q) => $q->whereDate('date', '>=', now()->subWeek()))
                ->when($period === 'month', fn($q) => $q->whereDate('date', '>=', now()->subMonth()))
                ->when($period === 'custom' && $dateFrom && $dateTo, fn($q) => $q->whereBetween('date', [$dateFrom, $dateTo]))
                ->orderBy('odometer', 'desc')
                ->first();
                
            $lastRefueling = Refueling::where('car_id', $carId)
                ->when($period === 'today', fn($q) => $q->whereDate('date', today()))
                ->when($period === 'week', fn($q) => $q->whereDate('date', '>=', now()->subWeek()))
                ->when($period === 'month', fn($q) => $q->whereDate('date', '>=', now()->subMonth()))
                ->when($period === 'custom' && $dateFrom && $dateTo, fn($q) => $q->whereBetween('date', [$dateFrom, $dateTo]))
                ->orderBy('odometer', 'desc')
                ->first();
                
            $maxOdometer = max(
                $lastRecord->odometer ?? 0,
                $lastRefueling->odometer ?? 0
            );
        }
        
        $totalDistance = ($maxOdometer > 0 ? $maxOdometer : ($car->initial_odometer ?? 0)) - ($car->initial_odometer ?? 0);
        
        return ($totalDistance > 0 && $totalExpenses > 0) ? round($totalExpenses / $totalDistance, 2) : 0;
    }
}