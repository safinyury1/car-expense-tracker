<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Refueling;
use App\Traits\ConvertsUnits;
use App\Traits\ValidatesOdometer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    use ConvertsUnits, ValidatesOdometer;

    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        $maxOdometer = 0;
        $lastOdometer = null;
        $lastOdometerByCar = [];
        
        // Для каждого автомобиля получаем последний пробег
        foreach ($cars as $car) {
            $lastOdometerByCar[$car->id] = max(
                Expense::where('car_id', $car->id)->max('odometer') ?? 0,
                Refueling::where('car_id', $car->id)->max('odometer') ?? 0,
                Income::where('car_id', $car->id)->max('odometer') ?? 0,
                $car->initial_odometer ?? 0
            );
        }
        
        if ($selectedCar) {
            $lastOdometer = $lastOdometerByCar[$selectedCarId] ?? 0;
            $maxOdometerKm = max(
                Expense::where('car_id', $selectedCarId)->max('odometer') ?? 0,
                Refueling::where('car_id', $selectedCarId)->max('odometer') ?? 0,
                Income::where('car_id', $selectedCarId)->max('odometer') ?? 0
            );
            $maxOdometer = $this->convertDistance($maxOdometerKm, $selectedCar);
        }
        
        return view('incomes.create', compact('cars', 'selectedCar', 'maxOdometer', 'lastOdometer', 'lastOdometerByCar'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'odometer' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'category' => 'required|string',
        ]);
        
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Валидация пробега (если указан)
        if (!empty($validated['odometer'])) {
            $this->validateOdometer($validated['car_id'], $validated['odometer'], null, 'income');
            
            // Обновляем пробег в автомобиле
            $car->odometer = $validated['odometer'];
            $car->save();
        }
        
        Income::create($validated);
        
        return redirect()->route('overview.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Доход добавлен!');
    }
    
    public function show(Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        return view('incomes.show', compact('income', 'cars'));
    }
    
    public function edit(Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        $maxOdometerKm = max(
            Expense::where('car_id', $income->car_id)->max('odometer') ?? 0,
            Refueling::where('car_id', $income->car_id)->max('odometer') ?? 0,
            Income::where('car_id', $income->car_id)->where('id', '!=', $income->id)->max('odometer') ?? 0
        );
        $maxOdometer = $this->convertDistance($maxOdometerKm, $income->car);
        
        // Получаем последний пробег для каждого автомобиля
        $lastOdometerByCar = [];
        foreach ($cars as $car) {
            $lastOdometerByCar[$car->id] = max(
                Expense::where('car_id', $car->id)->max('odometer') ?? 0,
                Refueling::where('car_id', $car->id)->max('odometer') ?? 0,
                Income::where('car_id', $car->id)->max('odometer') ?? 0,
                $car->initial_odometer ?? 0
            );
        }
        
        // Последний пробег для текущего автомобиля
        $lastOdometer = $lastOdometerByCar[$income->car_id] ?? 0;
        
        return view('incomes.edit', compact('income', 'cars', 'maxOdometer', 'lastOdometer', 'lastOdometerByCar'));
    }
    
    public function update(Request $request, Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'odometer' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'category' => 'required|string',
        ]);
        
        // Валидация пробега (если указан, исключаем текущую запись)
        if (!empty($validated['odometer'])) {
            $this->validateOdometer($validated['car_id'], $validated['odometer'], $income->id, 'income');
            
            // Обновляем пробег в автомобиле
            $car = Car::find($income->car_id);
            $car->odometer = $validated['odometer'];
            $car->save();
        }
        
        $income->update($validated);
        
        return redirect()->route('overview.index', ['car_id' => $income->car_id])
            ->with('success', 'Доход обновлён!');
    }
    
    public function destroy(Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $income->car_id;
        $income->delete();
        
        return redirect()->route('history.index', ['car_id' => $carId])
            ->with('success', 'Доход удалён!');
    }
}