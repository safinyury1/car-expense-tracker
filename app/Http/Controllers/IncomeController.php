<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
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