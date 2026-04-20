<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Income;
use App\Traits\ConvertsUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    use ConvertsUnits;

    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        return view('incomes.create', compact('cars', 'selectedCar'));
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
        
        return view('incomes.edit', compact('income', 'cars'));
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