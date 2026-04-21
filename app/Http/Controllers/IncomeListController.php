<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Income;
use App\Traits\ConvertsUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeListController extends Controller
{
    use ConvertsUnits;

    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        
        $query = Income::with('car')
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
            $car = Car::find($carId);
        }
        
        $incomes = $query->orderBy('date', 'desc')->paginate(20);
        
        foreach ($incomes as $income) {
            if (isset($car)) {
                $income->converted_amount = $this->convertCurrency($income->amount, $car);
                $income->converted_odometer = $this->convertDistance($income->odometer ?? 0, $car);
                $income->currency = $this->getCurrencySymbol($car);
                $income->distance_unit = $this->getDistanceUnit($car);
            } else {
                $income->converted_amount = $income->amount;
                $income->converted_odometer = $income->odometer ?? 0;
                $income->currency = '₽';
                $income->distance_unit = 'км';
            }
        }
        
        $cars = Auth::user()->cars;
        
        return view('incomes-list.index', compact('incomes', 'cars', 'carId'));
    }
    
    public function destroy(Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $income->car_id;
        $income->delete();
        
        return redirect()->route('incomes-list.index', ['car_id' => $carId])
            ->with('success', 'Доход удалён!');
    }
}