<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Reminder;
use App\Models\Income;
use App\Traits\ConvertsUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    use ConvertsUnits;
    
    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        
        $selectedCarId = $request->get('car_id');
        if (!$selectedCarId && session()->has('selected_car_id')) {
            $selectedCarId = session('selected_car_id');
        }
        if (!$selectedCarId) {
            $selectedCarId = $cars->first()?->id;
        }
        if ($selectedCarId) {
            session(['selected_car_id' => $selectedCarId]);
        }
        
        $selectedCar = $cars->find($selectedCarId);
        
        if (!$selectedCar) {
            return redirect()->route('cars.create');
        }
        
        // Текущий пробег в КМ (для расчётов)
        $maxOdometerKm = max(
            Expense::where('car_id', $selectedCarId)->max('odometer') ?? 0,
            Refueling::where('car_id', $selectedCarId)->max('odometer') ?? 0,
            Income::where('car_id', $selectedCarId)->max('odometer') ?? 0,
            $selectedCar->initial_odometer ?? 0
        );
        
        // Конвертированный пробег для отображения
        $convertedOdometer = $this->convertDistance($maxOdometerKm, $selectedCar);
        $distanceUnit = $this->getDistanceUnit($selectedCar);
        
        // Последнее обновление
        $lastExpense = Expense::where('car_id', $selectedCarId)->latest('date')->first();
        $lastRefueling = Refueling::where('car_id', $selectedCarId)->latest('date')->first();
        $lastIncome = Income::where('car_id', $selectedCarId)->latest('date')->first();
        $lastService = Reminder::where('car_id', $selectedCarId)->where('service_type', 'service')->latest('service_date')->first();
        
        $latestDate = null;
        if ($lastExpense) $latestDate = $lastExpense->date;
        if ($lastRefueling && (!$latestDate || $lastRefueling->date > $latestDate)) $latestDate = $lastRefueling->date;
        if ($lastIncome && (!$latestDate || $lastIncome->date > $latestDate)) $latestDate = $lastIncome->date;
        if ($lastService && $lastService->service_date && (!$latestDate || $lastService->service_date > $latestDate)) $latestDate = $lastService->service_date;
        if ($selectedCar->updated_at && (!$latestDate || $selectedCar->updated_at > $latestDate)) $latestDate = $selectedCar->updated_at;
        
        $lastUpdate = $latestDate;
        
        // Активные напоминания
        $activeReminders = Reminder::where('car_id', $selectedCarId)
            ->where('is_completed', false)
            ->where(function($q) {
                $q->whereNull('service_type')->orWhere('service_type', '!=', 'service');
            })
            ->orderBy('due_odometer', 'asc')
            ->get();
        
        // Лента событий
        $allEvents = collect();
        
        // Расходы
        $expenses = Expense::where('car_id', $selectedCarId)->with('category')->get();
        foreach ($expenses as $item) {
            $allEvents->push([
                'id' => $item->id,
                'type' => 'expense',
                'date' => $item->date,
                'title' => $item->category->name,
                'amount' => $this->convertCurrency($item->amount, $selectedCar),
                'currency' => $this->getCurrencySymbol($selectedCar),
                'odometer' => $this->convertDistance($item->odometer, $selectedCar),
                'distance_unit' => $this->getDistanceUnit($selectedCar),
                'description' => $item->description,
                'sort_date' => $item->date->timestamp,
            ]);
        }
        
        // Заправки
        $refuelings = Refueling::where('car_id', $selectedCarId)->get();
        foreach ($refuelings as $item) {
            $allEvents->push([
                'id' => $item->id,
                'type' => 'refueling',
                'date' => $item->date,
                'title' => 'Заправка',
                'amount' => $this->convertCurrency($item->total_amount, $selectedCar),
                'currency' => $this->getCurrencySymbol($selectedCar),
                'odometer' => $this->convertDistance($item->odometer, $selectedCar),
                'distance_unit' => $this->getDistanceUnit($selectedCar),
                'liters' => $this->convertVolume($item->liters, $selectedCar),
                'volume_unit' => $this->getVolumeUnit($selectedCar),
                'gas_station' => $item->gas_station,
                'sort_date' => $item->date->timestamp,
            ]);
        }
        
        // Доходы
        $incomes = Income::where('car_id', $selectedCarId)->get();
        foreach ($incomes as $item) {
            $allEvents->push([
                'id' => $item->id,
                'type' => 'income',
                'date' => $item->date,
                'title' => $item->title,
                'amount' => $this->convertCurrency($item->amount, $selectedCar),
                'currency' => $this->getCurrencySymbol($selectedCar),
                'odometer' => $this->convertDistance($item->odometer ?? 0, $selectedCar),
                'distance_unit' => $this->getDistanceUnit($selectedCar),
                'description' => $item->description,
                'sort_date' => $item->date->timestamp,
            ]);
        }
        
        // Обслуживание
        $services = Reminder::where('car_id', $selectedCarId)
            ->where('service_type', 'service')
            ->where('is_completed', true)
            ->get();
        foreach ($services as $item) {
            $serviceDate = $item->service_date ?? $item->created_at;
            // Преобразуем в Carbon если это строка
            if (!$serviceDate instanceof \Carbon\Carbon) {
                $serviceDate = \Carbon\Carbon::parse($serviceDate);
            }
            $allEvents->push([
                'id' => $item->id,
                'type' => 'service',
                'date' => $serviceDate,
                'title' => $item->title,
                'amount' => $this->convertCurrency($item->service_cost ?? 0, $selectedCar),
                'currency' => $this->getCurrencySymbol($selectedCar),
                'odometer' => $this->convertDistance($item->due_odometer, $selectedCar),
                'distance_unit' => $this->getDistanceUnit($selectedCar),
                'description' => $item->service_notes,
                'sort_date' => $serviceDate->timestamp,
            ]);
        }
        
        // Сортируем и берём последние 10
        $events = $allEvents->sortByDesc('sort_date')->take(10)->values();
        
        return view('overview.index', compact('cars', 'selectedCar', 'selectedCarId', 'maxOdometerKm', 'convertedOdometer', 'distanceUnit', 'lastUpdate', 'activeReminders', 'events'));
    }
}