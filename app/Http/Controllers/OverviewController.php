<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        
        // Выбранный автомобиль (по умолчанию первый)
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        if (!$selectedCar) {
            return redirect()->route('cars.create');
        }
        
        // Текущий пробег (максимальный из всех записей)
        $maxOdometer = max(
            Expense::where('car_id', $selectedCarId)->max('odometer') ?? 0,
            Refueling::where('car_id', $selectedCarId)->max('odometer') ?? 0,
            $selectedCar->initial_odometer ?? 0
        );
        
        // Дата последнего обновления пробега
        $lastExpense = Expense::where('car_id', $selectedCarId)->latest('date')->first();
        $lastRefueling = Refueling::where('car_id', $selectedCarId)->latest('date')->first();
        
        $lastUpdate = null;
        if ($lastExpense && $lastRefueling) {
            $lastUpdate = $lastExpense->date > $lastRefueling->date ? $lastExpense->date : $lastRefueling->date;
        } elseif ($lastExpense) {
            $lastUpdate = $lastExpense->date;
        } elseif ($lastRefueling) {
            $lastUpdate = $lastRefueling->date;
        }
        
        // Активные напоминания
        $activeReminders = Reminder::where('car_id', $selectedCarId)
            ->where('is_completed', false)
            ->orderBy('due_odometer', 'asc')
            ->get();
        
        // Лента событий (последние 10 записей: расходы + заправки)
        $expenses = Expense::where('car_id', $selectedCarId)
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'expense',
                    'date' => $item->date,
                    'title' => $item->category->name,
                    'amount' => $item->amount,
                    'odometer' => $item->odometer,
                    'description' => $item->description,
                ];
            });
        
        $refuelings = Refueling::where('car_id', $selectedCarId)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'refueling',
                    'date' => $item->date,
                    'title' => 'Заправка',
                    'liters' => $item->liters,
                    'amount' => $item->total_amount,
                    'odometer' => $item->odometer,
                    'gas_station' => $item->gas_station,
                ];
            });
        
        $events = $expenses->concat($refuelings)
            ->sortByDesc('date')
            ->take(10);
        
        return view('overview.index', compact('cars', 'selectedCar', 'selectedCarId', 'maxOdometer', 'lastUpdate', 'activeReminders', 'events'));
    }
}