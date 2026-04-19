<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Traits\ConvertsUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompareController extends Controller
{
    use ConvertsUnits;

    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        
        $selectedCarIds = $request->get('cars', []);
        if (!is_array($selectedCarIds)) {
            $selectedCarIds = [];
        }
        
        $selectedCarIds = array_slice($selectedCarIds, 0, 4);
        
        $selectedCars = Car::whereIn('id', $selectedCarIds)
            ->where('user_id', Auth::id())
            ->get();
        
        $comparisonData = [];
        
        foreach ($selectedCars as $car) {
            $comparisonData[$car->id] = $this->getCarStatistics($car->id);
        }
        
        $chartData = $this->getComparisonChartData($selectedCarIds);
        $expenseDistributionData = [];
        foreach ($selectedCars as $car) {
            $expenseDistributionData[$car->id] = $this->getExpenseDistribution($car->id);
        }
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
    
    private function getCarStatistics($carId)
    {
        $car = Car::find($carId);
        
        $expensesSum = Expense::where('car_id', $carId)->sum('amount');
        $refuelingsSum = Refueling::where('car_id', $carId)->sum('total_amount');
        $totalExpenses = $expensesSum + $refuelingsSum;
        $totalFuelCost = Refueling::where('car_id', $carId)->sum('total_amount');
        $avgFuelConsumption = $this->calculateAvgFuelConsumption($carId);
        $costPerKm = $this->calculateCostPerKm($carId, $totalExpenses);
        $expensesCount = Expense::where('car_id', $carId)->count();
        $refuelingsCount = Refueling::where('car_id', $carId)->count();
        
        $maxOdometer = Refueling::where('car_id', $carId)->max('odometer');
        if (!$maxOdometer) {
            $maxOdometer = Expense::where('car_id', $carId)->max('odometer');
        }
        $totalDistance = ($maxOdometer ?? $car->initial_odometer) - $car->initial_odometer;
        
        return [
            'car' => $car,
            'totalExpenses' => $this->convertCurrency($totalExpenses, $car),
            'totalFuelCost' => $this->convertCurrency($totalFuelCost, $car),
            'avgFuelConsumption' => $this->convertFuelConsumption($avgFuelConsumption, $car),
            'costPerKm' => $this->convertCurrency($costPerKm, $car),
            'expensesCount' => $expensesCount,
            'refuelingsCount' => $refuelingsCount,
            'totalDistance' => $this->convertDistance(max($totalDistance, 0), $car),
            'currency' => $this->getCurrencySymbol($car),
            'distance_unit' => $this->getDistanceUnit($car),
            'fuel_unit' => ($car->distance_unit === 'miles' && $car->volume_unit === 'gallons') ? 'mpg' : 'л/100 км',
        ];
    }
    
    private function calculateAvgFuelConsumption($carId)
    {
        $refuelings = Refueling::where('car_id', $carId)->orderBy('date', 'asc')->get();
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
        
        if ($totalDistance == 0) return 0;
        return round(($totalLiters / $totalDistance) * 100, 1);
    }
    
    private function calculateCostPerKm($carId, $totalExpenses)
    {
        $car = Car::find($carId);
        $maxOdometer = Refueling::where('car_id', $carId)->max('odometer');
        if (!$maxOdometer) {
            $maxOdometer = Expense::where('car_id', $carId)->max('odometer');
        }
        $totalDistance = ($maxOdometer ?? $car->initial_odometer) - $car->initial_odometer;
        if ($totalDistance <= 0 || $totalExpenses <= 0) return 0;
        return round($totalExpenses / $totalDistance, 2);
    }
    
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
                'expenses' => $this->convertCurrency($expensesSum + $refuelingsSum, $car),
                'fuel' => $this->convertCurrency($refuelingsSum, $car),
                'currency' => $this->getCurrencySymbol($car),
            ];
        }
        return $data;
    }
    
    private function getExpenseDistribution($carId)
    {
        $car = Car::find($carId);
        $expensesByCat = Expense::where('car_id', $carId)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get();
        
        $fuelTotal = Refueling::where('car_id', $carId)->sum('total_amount');
        
        $result = [];
        foreach ($expensesByCat as $item) {
            if ($item->category) {
                $result[] = [
                    'name' => $item->category->name,
                    'amount' => round($this->convertCurrency($item->total, $car), 2),
                ];
            }
        }
        if ($fuelTotal > 0) {
            $result[] = [
                'name' => 'Топливо',
                'amount' => round($this->convertCurrency($fuelTotal, $car), 2),
            ];
        }
        return $result;
    }
    
    private function getMonthlyTrendData($carIds)
    {
        $months = collect();
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
                $seriesData[] = round($this->convertCurrency($expensesSum + $refuelingsSum, $car), 2);
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