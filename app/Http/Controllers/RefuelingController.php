<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Refueling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefuelingController extends Controller
{
    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        
        $query = Refueling::with('car')
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
        }
        
        $refuelings = $query->orderBy('date', 'desc')->paginate(20);
        $cars = Auth::user()->cars;
        
        return view('refuelings.index', compact('refuelings', 'cars', 'carId'));
    }

    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCar = $request->get('car_id');
        
        return view('refuelings.create', compact('cars', 'selectedCar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'odometer' => 'required|integer|min:0',
            'gas_station' => 'nullable|string|max:255',
        ]);
        
        // Автоматический расчёт общей суммы
        $validated['total_amount'] = $validated['liters'] * $validated['price_per_liter'];
        
        // Проверяем, что автомобиль принадлежит пользователю
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        Refueling::create($validated);
        
        return redirect()->route('refuelings.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Заправка успешно добавлена!');
    }

    public function edit(Refueling $refueling)
    {
        if ($refueling->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        return view('refuelings.edit', compact('refueling', 'cars'));
    }

    public function update(Request $request, Refueling $refueling)
    {
        if ($refueling->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'odometer' => 'required|integer|min:0',
            'gas_station' => 'nullable|string|max:255',
        ]);
        
        $validated['total_amount'] = $validated['liters'] * $validated['price_per_liter'];
        
        $refueling->update($validated);
        
        return redirect()->route('refuelings.index', ['car_id' => $refueling->car_id])
            ->with('success', 'Заправка успешно обновлена!');
    }

    public function destroy(Refueling $refueling)
    {
        if ($refueling->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $refueling->car_id;
        $refueling->delete();
        
        return redirect()->route('refuelings.index', ['car_id' => $carId])
            ->with('success', 'Заправка успешно удалена!');
    }
}