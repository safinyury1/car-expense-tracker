<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Refueling;
use App\Models\Income;
use App\Traits\ConvertsUnits;
use App\Traits\ValidatesOdometer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ExpenseController extends Controller
{
    use ConvertsUnits, ValidatesOdometer;

    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['date', 'amount', 'odometer', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'date';
        }
        
        $query = Expense::with(['car', 'category'])
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
            $car = Car::find($carId);
        }
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cat) use ($search) {
                      $cat->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('car', function ($car) use ($search) {
                      $car->where('brand', 'like', "%{$search}%")
                          ->orWhere('model', 'like', "%{$search}%");
                  });
            });
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        $expenses = $query->paginate(20)->appends($request->all());
        
        foreach ($expenses as $expense) {
            if (isset($car)) {
                $expense->converted_amount = $this->convertCurrency($expense->amount, $car);
                $expense->converted_odometer = $this->convertDistance($expense->odometer, $car);
                $expense->currency = $this->getCurrencySymbol($car);
                $expense->distance_unit = $this->getDistanceUnit($car);
            } else {
                $expense->converted_amount = $expense->amount;
                $expense->converted_odometer = $expense->odometer;
                $expense->currency = '₽';
                $expense->distance_unit = 'км';
            }
        }
        
        $cars = Auth::user()->cars;
        $categories = ExpenseCategory::getCategoriesForUser(Auth::id());
        
        return view('expenses.index', compact('expenses', 'cars', 'categories', 'carId', 'search', 'categoryId', 'dateFrom', 'dateTo', 'sortBy', 'sortOrder'));
    }

    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $categories = ExpenseCategory::getCategoriesForUser(Auth::id());
        $selectedCar = $request->get('car_id');
        
        $maxOdometer = 0;
        if ($selectedCar) {
            $car = Car::find($selectedCar);
            if ($car) {
                $maxOdometerKm = max(
                    Expense::where('car_id', $selectedCar)->max('odometer') ?? 0,
                    Refueling::where('car_id', $selectedCar)->max('odometer') ?? 0,
                    Income::where('car_id', $selectedCar)->max('odometer') ?? 0
                );
                $maxOdometer = $this->convertDistance($maxOdometerKm, $car);
            }
        }
        
        return view('expenses.create', compact('cars', 'categories', 'selectedCar', 'maxOdometer'));
    }

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
        
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Валидация пробега
        $this->validateOdometer($validated['car_id'], $validated['odometer'], null, 'expense');
        
        Expense::create($validated);
        
        return redirect()->route('expenses.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Расход успешно добавлен!');
    }

    public function show(Expense $expense)
    {
        if ($expense->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        return view('expenses.show', compact('expense', 'cars'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        $categories = ExpenseCategory::getCategoriesForUser(Auth::id());
        
        $maxOdometerKm = max(
            Expense::where('car_id', $expense->car_id)->where('id', '!=', $expense->id)->max('odometer') ?? 0,
            Refueling::where('car_id', $expense->car_id)->max('odometer') ?? 0,
            Income::where('car_id', $expense->car_id)->max('odometer') ?? 0
        );
        $maxOdometer = $this->convertDistance($maxOdometerKm, $expense->car);
        
        return view('expenses.edit', compact('expense', 'cars', 'categories', 'maxOdometer'));
    }

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
        
        // Валидация пробега (исключаем текущую запись)
        $this->validateOdometer($validated['car_id'], $validated['odometer'], $expense->id, 'expense');
        
        $expense->update($validated);
        
        return redirect()->route('expenses.index', ['car_id' => $expense->car_id])
            ->with('success', 'Расход успешно обновлён!');
    }

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

    public function exportCsv(Request $request)
    {
        $carId = $request->get('car_id');
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = Expense::with(['car', 'category'])
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
        }
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cat) use ($search) {
                      $cat->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('car', function ($car) use ($search) {
                      $car->where('brand', 'like', "%{$search}%")
                          ->orWhere('model', 'like', "%{$search}%");
                  });
            });
        }
        
        $expenses = $query->orderBy('date', 'desc')->get();
        
        $filename = 'expenses_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, ['ID', 'Дата', 'Автомобиль', 'Категория', 'Сумма (₽)', 'Пробег (км)', 'Описание'], ';');
        
        foreach ($expenses as $expense) {
            fputcsv($handle, [
                $expense->id,
                $expense->date->format('d.m.Y'),
                $expense->car->brand . ' ' . $expense->car->model,
                $expense->category->name,
                $expense->amount,
                $expense->odometer,
                $expense->description ?? '',
            ], ';');
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}