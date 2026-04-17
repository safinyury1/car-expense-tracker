<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        return view('service.create', compact('cars', 'selectedCar'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'title' => 'required|string|max:255',
            'service_date' => 'required|date',
            'odometer' => 'required|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'next_due_odometer' => 'nullable|integer|min:0',
            'next_due_date' => 'nullable|date',
        ]);
        
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Создаём запись в расходах
        $expense = Expense::create([
            'car_id' => $validated['car_id'],
            'category_id' => $this->getCategoryId($validated['title']),
            'date' => $validated['service_date'],
            'amount' => $validated['cost'] ?? 0,
            'odometer' => $validated['odometer'],
            'description' => 'Обслуживание: ' . $validated['title'] . ($validated['notes'] ? '. ' . $validated['notes'] : ''),
        ]);
        
        // Создаём напоминание о выполненном обслуживании
        $reminder = Reminder::create([
            'car_id' => $validated['car_id'],
            'title' => $validated['title'],
            'due_odometer' => $validated['odometer'],
            'due_date' => $validated['service_date'],
            'is_completed' => true,
            'service_type' => 'service',
            'service_date' => $validated['service_date'],
            'service_cost' => $validated['cost'] ?? 0,
            'service_notes' => $validated['notes'] ?? null,
            'next_due_odometer' => $validated['next_due_odometer'] ?? null,
            'next_due_date' => $validated['next_due_date'] ?? null,
        ]);
        
        // Создаём напоминание для следующего ТО если указано
        if ($validated['next_due_odometer'] || $validated['next_due_date']) {
            Reminder::create([
                'car_id' => $validated['car_id'],
                'title' => $validated['title'] . ' (следующее)',
                'due_odometer' => $validated['next_due_odometer'] ?? 0,
                'due_date' => $validated['next_due_date'] ?? null,
                'is_completed' => false,
                'service_type' => 'reminder',
            ]);
        }
        
        return redirect()->route('overview.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Обслуживание добавлено!');
    }
    
    private function getCategoryId($title)
    {
        // Пытаемся найти подходящую категорию
        $categoryMap = [
            'масло' => 'Ремонт',
            'ТО' => 'Ремонт',
            'шины' => 'Шины',
            'страховка' => 'Страховка',
            'налог' => 'Налог',
        ];
        
        $categoryName = 'Ремонт';
        foreach ($categoryMap as $key => $value) {
            if (stripos($title, $key) !== false) {
                $categoryName = $value;
                break;
            }
        }
        
        $category = \App\Models\ExpenseCategory::where('name', $categoryName)->first();
        return $category?->id ?? 1; // 1 - дефолтная категория
    }
}