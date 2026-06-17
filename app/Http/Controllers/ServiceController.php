<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Refueling;
use App\Models\Reminder;
use App\Models\ExpenseCategory;
use App\Models\Attachment;
use App\Traits\ConvertsUnits;
use App\Traits\ValidatesOdometer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    use ConvertsUnits, ValidatesOdometer;
    
    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        
        $query = Reminder::with(['car', 'attachments'])
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
    
    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCarId = $request->get('car_id', $cars->first()?->id);
        $selectedCar = $cars->find($selectedCarId);
        
        $maxOdometer = 0;
        $lastOdometer = null;
        $lastOdometerByCar = [];
        
        foreach ($cars as $car) {
            $lastOdometerByCar[$car->id] = max(
                Expense::where('car_id', $car->id)->max('odometer') ?? 0,
                Refueling::where('car_id', $car->id)->max('odometer') ?? 0,
                Income::where('car_id', $car->id)->max('odometer') ?? 0,
                $car->initial_odometer ?? 0
            );
        }
        
        if ($selectedCar) {
            $lastOdometer = $lastOdometerByCar[$selectedCarId] ?? 0;
            $maxOdometerKm = max(
                Expense::where('car_id', $selectedCarId)->max('odometer') ?? 0,
                Refueling::where('car_id', $selectedCarId)->max('odometer') ?? 0,
                Income::where('car_id', $selectedCarId)->max('odometer') ?? 0
            );
            $maxOdometer = $this->convertDistance($maxOdometerKm, $selectedCar);
        }
        
        return view('service.create', compact('cars', 'selectedCar', 'maxOdometer', 'lastOdometer', 'lastOdometerByCar'));
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
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->validateOdometer($validated['car_id'], $validated['odometer'], null, 'service');
        
        $category = ExpenseCategory::where('name', 'Обслуживание')->first();
        if (!$category) {
            $category = ExpenseCategory::where('name', 'Ремонт')->first();
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
        
        // Создаём запись обслуживания
        $service = Reminder::create([
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
        
        // ==========================================
        // СОХРАНЕНИЕ ВЛОЖЕНИЙ (ЧЕРЕЗ МОДЕЛЬ ATTACHMENT)
        // ==========================================
        if ($request->hasFile('attachments')) {
            $count = 0;
            foreach ($request->file('attachments') as $file) {
                if ($count >= 4) break;
                
                $path = $file->store('attachments/services', 'public');
                
                Attachment::create([
                    'attachable_id' => $service->id,
                    'attachable_type' => Reminder::class,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
                $count++;
            }
        }
        
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
            ->with('success', '✅ Обслуживание успешно добавлено! Напоминание о следующем ТО создано автоматически.');
    }
    
    public function show(Reminder $service)
    {
        if ($service->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $car = $service->car;
        
        $service->converted_odometer = $this->convertDistance($service->due_odometer, $car);
        $service->converted_cost = $this->convertCurrency($service->service_cost ?? 0, $car);
        $service->currency = $this->getCurrencySymbol($car);
        $service->distance_unit = $this->getDistanceUnit($car);
        
        $service->load('attachments');
        
        $cars = Auth::user()->cars;
        
        return view('service.show', compact('service', 'cars'));
    }
    
    public function addAttachment(Request $request, Reminder $service)
    {
        if ($service->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'attachments' => 'required|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        $currentCount = $service->attachments->count();
        $maxFiles = 4;
        $availableSlots = $maxFiles - $currentCount;
        
        if ($availableSlots <= 0) {
            return redirect()->route('service.show', $service)
                ->with('attachment_error', 'Достигнут лимит в 4 файла');
        }
        
        $uploaded = 0;
        foreach ($request->file('attachments') as $file) {
            if ($uploaded >= $availableSlots) break;
            
            $path = $file->store('attachments/services', 'public');
            
            Attachment::create([
                'attachable_id' => $service->id,
                'attachable_type' => Reminder::class,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
            $uploaded++;
        }
        
        return redirect()->route('service.show', $service)
            ->with('attachment_success', 'Добавлено файлов: ' . $uploaded);
    }
    
    public function destroy(Reminder $service)
    {
        if ($service->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $service->car_id;
        
        foreach ($service->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }
        
        $service->delete();
        
        return redirect()->route('service.index', ['car_id' => $carId])
            ->with('success', 'Обслуживание удалено!');
    }
}