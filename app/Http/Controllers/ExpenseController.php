<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Список расходов
     */
    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        
        $query = Expense::with(['car', 'category'])
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
        }
        
        $expenses = $query->orderBy('date', 'desc')->paginate(20);
        $cars = Auth::user()->cars;
        
        return view('expenses.index', compact('expenses', 'cars', 'carId'));
    }

    /**
     * Форма создания расхода
     */
    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $categories = ExpenseCategory::all();
        $selectedCar = $request->get('car_id');
        
        return view('expenses.create', compact('cars', 'categories', 'selectedCar'));
    }

    /**
     * Сохранение расхода
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'category_id' => 'required|exists:expense_categories,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'odometer' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        // Проверяем, что автомобиль принадлежит пользователю
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        Expense::create($validated);
        
        return redirect()->route('expenses.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Расход успешно добавлен!');
    }

    /**
     * Форма редактирования расхода
     */
    public function edit(Expense $expense)
    {
        // Проверяем, что расход принадлежит автомобилю пользователя
        if ($expense->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        $categories = ExpenseCategory::all();
        
        return view('expenses.edit', compact('expense', 'cars', 'categories'));
    }

    /**
     * Обновление расхода
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'category_id' => 'required|exists:expense_categories,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'odometer' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        $expense->update($validated);
        
        return redirect()->route('expenses.index', ['car_id' => $expense->car_id])
            ->with('success', 'Расход успешно обновлён!');
    }

    /**
     * Удаление расхода
     */
    public function destroy(Expense $expense)
    {
        if ($expense->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $expense->car_id;
        $expense->delete();
        
        return redirect()->route('expenses.index', ['car_id' => $carId])
            ->with('success', 'Расход успешно удалён!');
    }
}