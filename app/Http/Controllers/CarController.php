<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Reminder;
use App\Traits\ConvertsUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    use ConvertsUnits;

    public function index()
    {
        $cars = Auth::user()->cars;
        
        foreach ($cars as $car) {
            $car->converted_initial_odometer = $this->convertDistance($car->initial_odometer, $car);
            
            // Получаем текущий пробег из последней записи
            $lastExpense = Expense::where('car_id', $car->id)->orderBy('date', 'desc')->first();
            $lastRefueling = Refueling::where('car_id', $car->id)->orderBy('date', 'desc')->first();
            $lastOdometer = 0;
            
            if ($lastExpense && $lastRefueling) {
                $lastOdometer = max($lastExpense->odometer, $lastRefueling->odometer);
            } elseif ($lastExpense) {
                $lastOdometer = $lastExpense->odometer;
            } elseif ($lastRefueling) {
                $lastOdometer = $lastRefueling->odometer;
            }
            
            $car->current_odometer = $lastOdometer > 0 ? $lastOdometer : $car->initial_odometer;
            $car->converted_current_odometer = $this->convertDistance($car->current_odometer, $car);
        }
        
        return view('cars.index', compact('cars'));
    }

    public function createForm()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'vin' => 'nullable|string|max:17',
            'initial_odometer' => 'nullable|integer|min:0',
        ]);

        $car = new Car($validated);
        $car->user_id = Auth::id();
        $car->save();

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно добавлен!');
    }

    public function edit(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Получаем текущий пробег
        $lastExpense = Expense::where('car_id', $car->id)->orderBy('date', 'desc')->first();
        $lastRefueling = Refueling::where('car_id', $car->id)->orderBy('date', 'desc')->first();
        $lastOdometer = 0;
        
        if ($lastExpense && $lastRefueling) {
            $lastOdometer = max($lastExpense->odometer, $lastRefueling->odometer);
        } elseif ($lastExpense) {
            $lastOdometer = $lastExpense->odometer;
        } elseif ($lastRefueling) {
            $lastOdometer = $lastRefueling->odometer;
        }
        
        $car->current_odometer = $lastOdometer > 0 ? $lastOdometer : $car->initial_odometer;
        $car->converted_current_odometer = $this->convertDistance($car->current_odometer, $car);
        $car->converted_initial_odometer = $this->convertDistance($car->initial_odometer, $car);
        
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'vin' => 'nullable|string|max:17',
            'initial_odometer' => 'nullable|integer|min:0',
            'current_odometer' => 'nullable|integer|min:0',
        ]);

        $car->update($validated);

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно обновлён!');
    }

    public function destroy(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        // Удаляем фото если есть
        if ($car->photo && Storage::disk('public')->exists($car->photo)) {
            Storage::disk('public')->delete($car->photo);
        }

        // Удаляем связанные данные
        Expense::where('car_id', $car->id)->delete();
        Refueling::where('car_id', $car->id)->delete();
        Reminder::where('car_id', $car->id)->delete();

        $car->delete();

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно удалён!');
    }

    public function updatePhoto(Request $request, Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Удаляем старое фото
        if ($car->photo && Storage::disk('public')->exists($car->photo)) {
            Storage::disk('public')->delete($car->photo);
        }

        // Сохраняем новое фото
        $path = $request->file('photo')->store('car-photos', 'public');
        $car->update(['photo' => $path]);

        return redirect()->back()->with('success', 'Фото обновлено!');
    }

    public function updateOdometer(Request $request, Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'odometer' => 'required|integer|min:0',
        ]);

        // Создаем запись в расходах для отслеживания изменения пробега
        Expense::create([
            'car_id' => $car->id,
            'category_id' => 1, // категория "Прочее"
            'date' => now(),
            'amount' => 0,
            'odometer' => $request->odometer,
            'description' => 'Ручное обновление пробега',
        ]);

        return redirect()->back()->with('success', 'Пробег обновлён!');
    }

    public function exportCsv()
    {
        $cars = Auth::user()->cars;
        
        $filename = 'cars_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, ['ID', 'Марка', 'Модель', 'Год выпуска', 'VIN-код', 'Начальный пробег (км)'], ';');
        
        foreach ($cars as $car) {
            fputcsv($handle, [
                $car->id,
                $car->brand,
                $car->model,
                $car->year ?? '',
                $car->vin ?? '',
                $car->initial_odometer,
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