<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Income;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarSettingsController extends Controller
{
    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        return view('car-settings.index', compact('cars', 'selectedCar', 'selectedCarId'));
    }
    
    /**
     * Обновить единицу измерения расстояния
     */
    public function updateDistanceUnit(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'distance_unit' => 'required|in:km,miles',
        ]);
        
        $car = Car::where('id', $validated['car_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $car->distance_unit = $validated['distance_unit'];
        $car->save();
        
        return redirect()->back()->with('success', 'Единица измерения расстояния обновлена!');
    }
    
    /**
     * Обновить единицу измерения объёма
     */
    public function updateVolumeUnit(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'volume_unit' => 'required|in:liters,gallons',
        ]);
        
        $car = Car::where('id', $validated['car_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $car->volume_unit = $validated['volume_unit'];
        $car->save();
        
        return redirect()->back()->with('success', 'Единица измерения объёма обновлена!');
    }
    
    /**
     * Обновить валюту
     */
    public function updateCurrency(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'currency' => 'required|in:rub,usd,eur',
        ]);
        
        $car = Car::where('id', $validated['car_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $car->currency = $validated['currency'];
        $car->save();
        
        return redirect()->back()->with('success', 'Валюта обновлена!');
    }
    
    /**
     * Удалить все данные (расходы, заправки, напоминания, доходы)
     */
    public function deleteAllData(Request $request)
    {
        $carId = $request->get('car_id');
        
        if (!$carId) {
            return redirect()->back()->with('error', 'Выберите автомобиль');
        }
        
        $car = Car::where('id', $carId)->where('user_id', Auth::id())->first();
        
        if (!$car) {
            return redirect()->back()->with('error', 'Автомобиль не найден');
        }
        
        // Удаляем расходы
        Expense::where('car_id', $carId)->delete();
        
        // Удаляем заправки
        Refueling::where('car_id', $carId)->delete();
        
        // Удаляем доходы
        Income::where('car_id', $carId)->delete();
        
        // Удаляем напоминания (обслуживание и т.д.)
        Reminder::where('car_id', $carId)->delete();
        
        return redirect()->back()->with('success', 'Все данные (расходы, заправки, доходы, напоминания) успешно удалены!');
    }
    
    /**
     * Удалить автомобиль
     */
    public function deleteCar(Request $request)
    {
        $carId = $request->get('car_id');
        
        if (!$carId) {
            return redirect()->back()->with('error', 'Выберите автомобиль');
        }
        
        $car = Car::where('id', $carId)->where('user_id', Auth::id())->first();
        
        if (!$car) {
            return redirect()->back()->with('error', 'Автомобиль не найден');
        }
        
        // Удаляем все связанные данные
        Expense::where('car_id', $carId)->delete();
        Refueling::where('car_id', $carId)->delete();
        Income::where('car_id', $carId)->delete();
        Reminder::where('car_id', $carId)->delete();
        
        // Удаляем автомобиль
        $car->delete();
        
        return redirect()->route('cars.index')->with('success', 'Автомобиль и все связанные данные удалены!');
    }
}