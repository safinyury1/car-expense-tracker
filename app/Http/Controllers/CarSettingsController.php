<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarSettingsController extends Controller
{
    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        return view('car-settings.index', compact('cars', 'selectedCar'));
    }
    
    public function updateDistanceUnit(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'unit' => 'required|in:km,miles'
        ]);
        
        $car = Car::findOrFail($request->car_id);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $car->distance_unit = $request->unit;
        $car->save();
        
        return response()->json(['success' => true]);
    }
    
    public function updateVolumeUnit(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'unit' => 'required|in:liters,gallons'
        ]);
        
        $car = Car::findOrFail($request->car_id);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $car->volume_unit = $request->unit;
        $car->save();
        
        return response()->json(['success' => true]);
    }
    
    public function updateCurrency(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'currency' => 'required|in:RUB,USD,EUR'
        ]);
        
        $car = Car::findOrFail($request->car_id);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $car->currency = $request->currency;
        $car->save();
        
        return response()->json(['success' => true]);
    }
    
    public function deleteAllData(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id'
        ]);
        
        $car = Car::findOrFail($request->car_id);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Удаляем все расходы автомобиля
        Expense::where('car_id', $car->id)->delete();
        
        // Удаляем все заправки автомобиля
        Refueling::where('car_id', $car->id)->delete();
        
        // Удаляем все напоминания автомобиля
        Reminder::where('car_id', $car->id)->delete();
        
        // Удаляем фото автомобиля если есть
        if ($car->photo && Storage::disk('public')->exists($car->photo)) {
            Storage::disk('public')->delete($car->photo);
        }
        
        // Сбрасываем настройки и пробег автомобиля
        $car->initial_odometer = 0;
        $car->photo = null;
        $car->distance_unit = 'km';
        $car->volume_unit = 'liters';
        $car->currency = 'RUB';
        $car->save();
        
        return response()->json(['success' => true, 'message' => 'Все данные автомобиля удалены']);
    }
    
    public function deleteCar(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id'
        ]);
        
        $car = Car::findOrFail($request->car_id);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Удаляем все связанные данные
        Expense::where('car_id', $car->id)->delete();
        Refueling::where('car_id', $car->id)->delete();
        Reminder::where('car_id', $car->id)->delete();
        
        // Удаляем фото
        if ($car->photo && Storage::disk('public')->exists($car->photo)) {
            Storage::disk('public')->delete($car->photo);
        }
        
        // Удаляем сам автомобиль
        $car->delete();
        
        return response()->json(['success' => true, 'message' => 'Автомобиль удален']);
    }
}