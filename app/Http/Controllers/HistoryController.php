<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Income;
use App\Models\Reminder;
use App\Traits\ConvertsUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    use ConvertsUnits;

    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        
        $selectedCarId = $request->get('car_id', 'all');
        $categoryFilter = $request->get('category', 'all');
        $period = $request->get('period', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $allOperations = collect();
        
        if ($selectedCarId === 'all') {
            $carIds = Auth::user()->cars->pluck('id')->toArray();
            
            // Расходы (исключаем ручное обновление пробега)
            $expenses = Expense::whereIn('car_id', $carIds)
                ->where(function($q) {
                    $q->where('description', '!=', 'Ручное обновление пробега')
                      ->orWhereNull('description');
                })
                ->with('category', 'car')
                ->get();
            foreach ($expenses as $item) {
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'expense',
                    'date' => $item->date,
                    'title' => $item->category->name,
                    'amount' => $item->amount,
                    'odometer' => $item->odometer,
                    'description' => $item->description,
                    'car_name' => $item->car->brand . ' ' . $item->car->model,
                    'category' => $item->category->name,
                    'currency' => '₽',
                    'distance_unit' => 'км',
                    'sort_date' => $item->date->timestamp,
                ]);
            }
            
            // Заправки
            $refuelings = Refueling::whereIn('car_id', $carIds)->with('car')->get();
            foreach ($refuelings as $item) {
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'refueling',
                    'date' => $item->date,
                    'title' => 'Заправка',
                    'amount' => $item->total_amount,
                    'odometer' => $item->odometer,
                    'description' => null,
                    'car_name' => $item->car->brand . ' ' . $item->car->model,
                    'category' => 'Топливо',
                    'liters' => $item->liters,
                    'price_per_liter' => $item->price_per_liter,
                    'gas_station' => $item->gas_station,
                    'currency' => '₽',
                    'distance_unit' => 'км',
                    'volume_unit' => 'л',
                    'sort_date' => $item->date->timestamp,
                ]);
            }
            
            // Доходы
            $incomes = Income::whereIn('car_id', $carIds)->with('car')->get();
            foreach ($incomes as $item) {
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'income',
                    'date' => $item->date,
                    'title' => $item->title,
                    'amount' => $item->amount,
                    'odometer' => $item->odometer,
                    'description' => $item->description,
                    'car_name' => $item->car->brand . ' ' . $item->car->model,
                    'category' => 'Доходы',
                    'currency' => '₽',
                    'distance_unit' => 'км',
                    'sort_date' => $item->date->timestamp,
                ]);
            }
            
            // Обслуживание
            $services = Reminder::whereIn('car_id', $carIds)
                ->where('service_type', 'service')
                ->where('is_completed', true)
                ->with('car')
                ->get();
            foreach ($services as $item) {
                $serviceDate = $item->service_date ?? $item->created_at;
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'service',
                    'date' => $serviceDate,
                    'title' => $item->title,
                    'amount' => $item->service_cost ?? 0,
                    'odometer' => $item->due_odometer,
                    'description' => $item->service_notes,
                    'car_name' => $item->car->brand . ' ' . $item->car->model,
                    'category' => 'Обслуживание',
                    'currency' => '₽',
                    'distance_unit' => 'км',
                    'sort_date' => $serviceDate->timestamp,
                ]);
            }
        } else {
            $car = Car::find($selectedCarId);
            
            // Расходы (исключаем ручное обновление пробега)
            $expenses = Expense::where('car_id', $selectedCarId)
                ->where(function($q) {
                    $q->where('description', '!=', 'Ручное обновление пробега')
                      ->orWhereNull('description');
                })
                ->with('category', 'car')
                ->get();
            foreach ($expenses as $item) {
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'expense',
                    'date' => $item->date,
                    'title' => $item->category->name,
                    'amount' => $this->convertCurrency($item->amount, $car),
                    'odometer' => $this->convertDistance($item->odometer, $car),
                    'description' => $item->description,
                    'car_name' => null,
                    'category' => $item->category->name,
                    'currency' => $this->getCurrencySymbol($car),
                    'distance_unit' => $this->getDistanceUnit($car),
                    'sort_date' => $item->date->timestamp,
                ]);
            }
            
            // Заправки
            $refuelings = Refueling::where('car_id', $selectedCarId)->with('car')->get();
            foreach ($refuelings as $item) {
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'refueling',
                    'date' => $item->date,
                    'title' => 'Заправка',
                    'amount' => $this->convertCurrency($item->total_amount, $car),
                    'odometer' => $this->convertDistance($item->odometer, $car),
                    'description' => null,
                    'car_name' => null,
                    'category' => 'Топливо',
                    'liters' => $this->convertVolume($item->liters, $car),
                    'price_per_liter' => $this->convertCurrency($item->price_per_liter, $car),
                    'gas_station' => $item->gas_station,
                    'currency' => $this->getCurrencySymbol($car),
                    'distance_unit' => $this->getDistanceUnit($car),
                    'volume_unit' => $this->getVolumeUnit($car),
                    'sort_date' => $item->date->timestamp,
                ]);
            }
            
            // Доходы
            $incomes = Income::where('car_id', $selectedCarId)->with('car')->get();
            foreach ($incomes as $item) {
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'income',
                    'date' => $item->date,
                    'title' => $item->title,
                    'amount' => $this->convertCurrency($item->amount, $car),
                    'odometer' => $this->convertDistance($item->odometer ?? 0, $car),
                    'description' => $item->description,
                    'car_name' => null,
                    'category' => 'Доходы',
                    'currency' => $this->getCurrencySymbol($car),
                    'distance_unit' => $this->getDistanceUnit($car),
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
                $allOperations->push([
                    'id' => $item->id,
                    'type' => 'service',
                    'date' => $serviceDate,
                    'title' => $item->title,
                    'amount' => $this->convertCurrency($item->service_cost ?? 0, $car),
                    'odometer' => $this->convertDistance($item->due_odometer, $car),
                    'description' => $item->service_notes,
                    'car_name' => null,
                    'category' => 'Обслуживание',
                    'currency' => $this->getCurrencySymbol($car),
                    'distance_unit' => $this->getDistanceUnit($car),
                    'sort_date' => $serviceDate->timestamp,
                ]);
            }
        }
        
        // Фильтр по дате
        if ($period === 'today') {
            $allOperations = $allOperations->filter(fn($item) => \Carbon\Carbon::parse($item['date'])->isToday());
        } elseif ($period === 'week') {
            $allOperations = $allOperations->filter(fn($item) => \Carbon\Carbon::parse($item['date']) >= now()->subWeek());
        } elseif ($period === 'month') {
            $allOperations = $allOperations->filter(fn($item) => \Carbon\Carbon::parse($item['date']) >= now()->subMonth());
        } elseif ($period === 'custom' && $dateFrom && $dateTo) {
            $allOperations = $allOperations->filter(fn($item) => \Carbon\Carbon::parse($item['date']) >= $dateFrom && \Carbon\Carbon::parse($item['date']) <= $dateTo);
        }
        
        // Фильтр по категории
        if ($categoryFilter !== 'all') {
            $allOperations = $allOperations->filter(fn($item) => $item['category'] === $categoryFilter);
        }
        
        // Сортировка
        $operations = $allOperations->sortByDesc('sort_date')->values();
        
        // Категории для фильтра
        $categories = collect();
        foreach ($allOperations as $op) {
            if (!$categories->contains($op['category'])) {
                $categories->push($op['category']);
            }
        }
        $sortedCategories = $categories->sort()->values();
        
        return view('history.index', compact('cars', 'selectedCarId', 'operations', 'sortedCategories', 'categoryFilter', 'period', 'dateFrom', 'dateTo'));
    }
    
    public function destroy($type, $id)
    {
        if ($type === 'expense') {
            $expense = Expense::findOrFail($id);
            if ($expense->car->user_id !== Auth::id()) {
                abort(403);
            }
            $expense->delete();
        } elseif ($type === 'refueling') {
            $refueling = Refueling::findOrFail($id);
            if ($refueling->car->user_id !== Auth::id()) {
                abort(403);
            }
            $refueling->delete();
        } elseif ($type === 'income') {
            $income = Income::findOrFail($id);
            if ($income->car->user_id !== Auth::id()) {
                abort(403);
            }
            $income->delete();
        } elseif ($type === 'service') {
            $service = Reminder::findOrFail($id);
            if ($service->car->user_id !== Auth::id()) {
                abort(403);
            }
            $service->delete();
        }
        
        return redirect()->route('history.index')
            ->with('success', 'Запись успешно удалена!');
    }
}