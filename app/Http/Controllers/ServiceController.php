<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Refueling;
use App\Models\Reminder;
use App\Traits\ConvertsUnits;
use App\Traits\ValidatesOdometer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    use ConvertsUnits, ValidatesOdometer;
    
    // Список обслуживаний
    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        
        $query = Reminder::with('car')
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('service_type', 'service')
            ->where('is_completed', true);
        
        if ($carId) {
            $query->where('car_id', $carId);
            $car = Car::find($carId);
        }
        
        $services = $query->orderBy('service_date', 'desc')->paginate(20);
        
        foreach ($services as $service) {
            if (isset($car)) {
                $service->converted_odometer = $this->convertDistance($service->due_odometer, $car);
                $service->converted_cost = $this->convertCurrency($service->service_cost ?? 0, $car);
                $service->currency = $this->getCurrencySymbol($car);
                $service->distance_unit = $this->getDistanceUnit($car);
            } else {
                $service->converted_odometer = $service->due_odometer;
                $service->converted_cost = $service->service_cost ?? 0;
                $service->currency = '₽';
                $service->distance_unit = 'км';
            }
        }
        
        $cars = Auth::user()->cars;
        
        return view('service.index', compact('services', 'cars', 'carId'));
    }
    
    // Форма создания обслуживания
    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        $maxOdometer = 0;
        if ($selectedCar) {
            $maxOdometerKm = max(
                Expense::where('car_id', $selectedCarId)->max('odometer') ?? 0,
                Refueling::where('car_id', $selectedCarId)->max('odometer') ?? 0,
                Income::where('car_id', $selectedCarId)->max('odometer') ?? 0
            );
            $maxOdometer = $this->convertDistance($maxOdometerKm, $selectedCar);
        }
        
        return view('service.create', compact('cars', 'selectedCar', 'maxOdometer'));
    }
    
    // Сохранение обслуживания
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
        
        // Валидация пробега
        $this->validateOdometer($validated['car_id'], $validated['odometer'], null, 'service');
        
        // Находим категорию
        $category = \App\Models\ExpenseCategory::where('name', 'Обслуживание')->first();
        if (!$category) {
            $category = \App\Models\ExpenseCategory::where('name', 'Ремонт')->first();
        }
        
        // Создаём запись в расходах
        Expense::create([
            'car_id' => $validated['car_id'],
            'category_id' => $category->id ?? 1,
            'date' => $validated['service_date'],
            'amount' => $validated['cost'] ?? 0,
            'odometer' => $validated['odometer'],
            'description' => 'Обслуживание: ' . $validated['title'] . ($validated['notes'] ? '. ' . $validated['notes'] : ''),
        ]);
        
        // Создаём запись в напоминаниях как выполненное обслуживание
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
        
        return redirect()->route('service.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Обслуживание добавлено!');
    }
    
    // Просмотр обслуживания
    public function show(Reminder $reminder)
    {
        if ($reminder->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        return view('service.show', compact('reminder', 'cars'));
    }
    
    // Удаление обслуживания
    public function destroy(Reminder $service)
    {
        if ($service->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $service->car_id;
        $service->delete();
        
        return redirect()->route('service.index', ['car_id' => $carId])
            ->with('success', 'Обслуживание удалено!');
    }
}