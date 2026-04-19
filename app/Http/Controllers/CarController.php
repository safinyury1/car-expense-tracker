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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    use ConvertsUnits;

    public function index()
    {
        $cars = Auth::user()->cars()->orderBy('id', 'desc')->get();
        
        if ($cars->isEmpty()) {
            return redirect()->route('cars.create')->with('warning', 'Сначала добавьте автомобиль!');
        }
        
        foreach ($cars as $car) {
            $car->converted_initial_odometer = $this->convertDistance($car->initial_odometer, $car);
            
            $maxOdometerKm = max(
                Expense::where('car_id', $car->id)->max('odometer') ?? 0,
                Refueling::where('car_id', $car->id)->max('odometer') ?? 0,
                Income::where('car_id', $car->id)->max('odometer') ?? 0,
                Reminder::where('car_id', $car->id)->max('due_odometer') ?? 0,
                $car->initial_odometer ?? 0
            );
            
            $car->converted_current_odometer = $this->convertDistance($maxOdometerKm, $car);
            $car->distance_unit = $this->getDistanceUnit($car);
        }
        
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        return view('cars.create');
    }

    public function createForm()
    {
        return view('cars.create-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'vin' => 'nullable|string|max:17',
            'initial_odometer' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $car = new Car($validated);
        $car->user_id = Auth::id();
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('cars', 'public');
            $car->photo = $path;
        }
        
        $car->save();

        return redirect()->route('overview.index')
            ->with('success', 'Автомобиль успешно добавлен!');
    }

    public function edit(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $car->converted_initial_odometer = $this->convertDistance($car->initial_odometer, $car);
        $car->distance_unit = $this->getDistanceUnit($car);
        
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

        if ($car->photo && Storage::disk('public')->exists($car->photo)) {
            Storage::disk('public')->delete($car->photo);
        }

        $car->delete();

        return redirect()->route('cars.create')
            ->with('success', 'Автомобиль успешно удалён!');
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

    public function updatePhoto(Request $request, Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($car->photo && Storage::disk('public')->exists($car->photo)) {
            Storage::disk('public')->delete($car->photo);
        }
        
        $path = $request->file('photo')->store('cars', 'public');
        $car->update(['photo' => $path]);
        
        return redirect()->route('overview.index', ['car_id' => $car->id])
            ->with('success', 'Фото обновлено!');
    }

    public function updateOdometer(Request $request, Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'odometer' => 'required|integer|min:0',
        ]);
        
        // Просто обновляем начальный пробег автомобиля, НЕ создаём запись в расходах
        $car->initial_odometer = $request->odometer;
        $car->save();
        
        return redirect()->route('overview.index', ['car_id' => $car->id])
            ->with('success', 'Пробег обновлён!');
    }
}