<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Refueling;
use App\Models\Attachment;
use App\Traits\ConvertsUnits;
use App\Traits\ValidatesOdometer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncomeController extends Controller
{
    use ConvertsUnits, ValidatesOdometer;

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
        
        return view('incomes.create', compact('cars', 'selectedCar', 'maxOdometer', 'lastOdometer', 'lastOdometerByCar'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'odometer' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        if (!empty($validated['odometer'])) {
            $this->validateOdometer($validated['car_id'], $validated['odometer'], null, 'income');
            $car->odometer = $validated['odometer'];
            $car->save();
        }
        
        $income = Income::create($validated);
        
        // ==========================================
        // СОХРАНЕНИЕ ВЛОЖЕНИЙ (МАКСИМУМ 4)
        // ==========================================
        if ($request->hasFile('attachments')) {
            $count = 0;
            $files = $request->file('attachments');
            
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $file) {
                if ($count >= 4) break;
                
                $path = $file->store('attachments/incomes', 'public');
                
                Attachment::create([
                    'attachable_id' => $income->id,
                    'attachable_type' => 'App\Models\Income',
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
                $count++;
            }
        }
        
        return redirect()->route('overview.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Доход добавлен!');
    }
    
    public function show(Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $car = $income->car;
        
        // Конвертируем значения для отображения
        $income->converted_amount = $this->convertCurrency($income->amount, $car);
        $income->converted_odometer = $this->convertDistance($income->odometer, $car);
        $income->currency = $this->getCurrencySymbol($car);
        $income->distance_unit = $this->getDistanceUnit($car);
        
        $income->load('attachments');
        
        $cars = Auth::user()->cars;
        
        return view('incomes.show', compact('income', 'cars'));
    }
    
    public function addAttachment(Request $request, Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'attachments' => 'required|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        $currentCount = $income->attachments->count();
        $maxFiles = 4;
        $availableSlots = $maxFiles - $currentCount;
        
        if ($availableSlots <= 0) {
            return redirect()->route('incomes.show', $income)
                ->with('attachment_error', 'Достигнут лимит в 4 файла');
        }
        
        $uploaded = 0;
        $files = $request->file('attachments');
        
        if (!is_array($files)) {
            $files = [$files];
        }
        
        foreach ($files as $file) {
            if ($uploaded >= $availableSlots) break;
            
            $path = $file->store('attachments/incomes', 'public');
            
            Attachment::create([
                'attachable_id' => $income->id,
                'attachable_type' => 'App\Models\Income',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
            $uploaded++;
        }
        
        return redirect()->route('incomes.show', $income)
            ->with('attachment_success', 'Добавлено файлов: ' . $uploaded);
    }
    
    public function edit(Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        $maxOdometerKm = max(
            Expense::where('car_id', $income->car_id)->max('odometer') ?? 0,
            Refueling::where('car_id', $income->car_id)->max('odometer') ?? 0,
            Income::where('car_id', $income->car_id)->where('id', '!=', $income->id)->max('odometer') ?? 0
        );
        $maxOdometer = $this->convertDistance($maxOdometerKm, $income->car);
        
        return view('incomes.edit', compact('income', 'cars', 'maxOdometer'));
    }
    
    public function update(Request $request, Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'odometer' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        if (!empty($validated['odometer'])) {
            $this->validateOdometer($validated['car_id'], $validated['odometer'], $income->id, 'income');
            $car = Car::find($income->car_id);
            $car->odometer = $validated['odometer'];
            $car->save();
        }
        
        $income->update($validated);
        
        if ($request->hasFile('attachments')) {
            $count = 0;
            $currentCount = $income->attachments->count();
            $availableSlots = 4 - $currentCount;
            $files = $request->file('attachments');
            
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $file) {
                if ($count >= $availableSlots) break;
                $path = $file->store('attachments/incomes', 'public');
                Attachment::create([
                    'attachable_id' => $income->id,
                    'attachable_type' => 'App\Models\Income',
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
                $count++;
            }
        }
        
        return redirect()->route('overview.index', ['car_id' => $income->car_id])
            ->with('success', 'Доход обновлён!');
    }
    
    public function destroy(Income $income)
    {
        if ($income->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $income->car_id;
        
        foreach ($income->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }
        
        $income->delete();
        
        return redirect()->route('history.index', ['car_id' => $carId])
            ->with('success', 'Доход удалён!');
    }
}