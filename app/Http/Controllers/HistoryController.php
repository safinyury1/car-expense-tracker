<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $cars = Auth::user()->cars;
        
        // Выбранный автомобиль
        $selectedCarId = $request->get('car_id', 'all');
        
        // Выбранная категория для фильтрации
        $categoryFilter = $request->get('category', 'all');
        
        // Выбранный период
        $period = $request->get('period', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // Получаем все операции (расходы + заправки)
        $operations = collect();
        
        if ($selectedCarId === 'all') {
            $carIds = Auth::user()->cars->pluck('id')->toArray();
            
            $expensesQuery = Expense::whereIn('car_id', $carIds)->with('category', 'car');
            $refuelingsQuery = Refueling::whereIn('car_id', $carIds)->with('car');
        } else {
            $expensesQuery = Expense::where('car_id', $selectedCarId)->with('category', 'car');
            $refuelingsQuery = Refueling::where('car_id', $selectedCarId)->with('car');
        }
        
        // Применяем фильтр по дате
        if ($period === 'today') {
            $expensesQuery->whereDate('date', today());
            $refuelingsQuery->whereDate('date', today());
        } elseif ($period === 'week') {
            $expensesQuery->whereDate('date', '>=', now()->subWeek());
            $refuelingsQuery->whereDate('date', '>=', now()->subWeek());
        } elseif ($period === 'month') {
            $expensesQuery->whereDate('date', '>=', now()->subMonth());
            $refuelingsQuery->whereDate('date', '>=', now()->subMonth());
        } elseif ($period === 'custom' && $dateFrom && $dateTo) {
            $expensesQuery->whereDate('date', '>=', $dateFrom)->whereDate('date', '<=', $dateTo);
            $refuelingsQuery->whereDate('date', '>=', $dateFrom)->whereDate('date', '<=', $dateTo);
        }
        
        $expenses = $expensesQuery->get()->map(function ($item) use ($selectedCarId) {
            return [
                'id' => $item->id,
                'type' => 'expense',
                'date' => $item->date,
                'title' => $item->category->name,
                'amount' => $item->amount,
                'odometer' => $item->odometer,
                'description' => $item->description,
                'car_name' => $selectedCarId === 'all' ? $item->car->brand . ' ' . $item->car->model : null,
                'category' => $item->category->name,
                'liters' => null,
                'price_per_liter' => null,
                'gas_station' => null,
            ];
        });
        
        $refuelings = $refuelingsQuery->get()->map(function ($item) use ($selectedCarId) {
            return [
                'id' => $item->id,
                'type' => 'refueling',
                'date' => $item->date,
                'title' => 'Заправка',
                'amount' => $item->total_amount,
                'odometer' => $item->odometer,
                'description' => null,
                'car_name' => $selectedCarId === 'all' ? $item->car->brand . ' ' . $item->car->model : null,
                'category' => 'Топливо',
                'liters' => $item->liters,
                'price_per_liter' => $item->price_per_liter,
                'gas_station' => $item->gas_station,
            ];
        });
        
        // Объединяем и сортируем по дате
        $allOperations = $expenses->concat($refuelings);
        
        // Применяем фильтр по категории
        if ($categoryFilter !== 'all') {
            if ($categoryFilter === 'Топливо') {
                $allOperations = $allOperations->filter(function ($item) {
                    return $item['type'] === 'refueling';
                });
            } else {
                $allOperations = $allOperations->filter(function ($item) use ($categoryFilter) {
                    return $item['type'] === 'expense' && $item['category'] === $categoryFilter;
                });
            }
        }
        
        $operations = $allOperations->sortByDesc('date')->values();
        
        // Уникальные категории для фильтра
        $categories = collect();
        foreach ($expenses as $expense) {
            if (!$categories->contains($expense['category'])) {
                $categories->push($expense['category']);
            }
        }
        
        // Добавляем "Топливо" если есть заправки
        if ($refuelings->count() > 0 && !$categories->contains('Топливо')) {
            $categories->push('Топливо');
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
        }
        
        return redirect()->route('history.index')
            ->with('success', 'Запись успешно удалена!');
    }
}