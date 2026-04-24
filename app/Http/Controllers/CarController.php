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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class CarController extends Controller
{
    use ConvertsUnits;

    /**
     * Display a listing of the cars.
     */
    public function index()
    {
        $cars = Auth::user()->cars;
        
        foreach ($cars as $car) {
            $maxOdometerExpense = Expense::where('car_id', $car->id)->max('odometer');
            $maxOdometerRefueling = Refueling::where('car_id', $car->id)->max('odometer');
            $maxOdometerIncome = Income::where('car_id', $car->id)->max('odometer');
            $maxOdometer = max($maxOdometerExpense, $maxOdometerRefueling, $maxOdometerIncome, $car->initial_odometer);
            
            $car->current_odometer = $maxOdometer;
            $car->converted_initial_odometer = $this->convertDistance($car->initial_odometer, $car);
            $car->converted_current_odometer = $this->convertDistance($maxOdometer, $car);
            $car->distance_unit = $this->getDistanceUnit($car);
        }
        
        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new car.
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Альтернативная форма для создания автомобиля.
     */
    public function createForm()
    {
        return view('cars.create-form');
    }

    /**
     * Store a newly created car in storage.
     */
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
            $path = $request->file('photo')->store('car-photos', 'public');
            $car->photo = $path;
        }
        
        $car->save();

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно добавлен!');
    }

    /**
     * Show the form for editing the specified car.
     */
    public function edit(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified car in storage.
     */
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

    /**
     * Update the car photo.
     */
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

        $path = $request->file('photo')->store('car-photos', 'public');
        $car->update(['photo' => $path]);

        return redirect()->route('overview.index', ['car_id' => $car->id])
            ->with('success', 'Фото автомобиля обновлено!');
    }

    /**
     * Update the car odometer.
     */
    public function updateOdometer(Request $request, Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'odometer' => 'required|integer|min:0',
        ]);

        $odometerInKm = $this->convertDistanceFrom($validated['odometer'], $car);

        $category = \App\Models\ExpenseCategory::where('name', 'Прочее')->first();
        
        Expense::create([
            'car_id' => $car->id,
            'category_id' => $category->id ?? 1,
            'date' => now(),
            'amount' => 0,
            'odometer' => $odometerInKm,
            'description' => 'Ручное обновление пробега',
        ]);

        return redirect()->route('overview.index', ['car_id' => $car->id])
            ->with('success', 'Пробег обновлён!');
    }

    /**
     * Remove the specified car from storage.
     */
    public function destroy(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        if ($car->photo && Storage::disk('public')->exists($car->photo)) {
            Storage::disk('public')->delete($car->photo);
        }

        Expense::where('car_id', $car->id)->delete();
        Refueling::where('car_id', $car->id)->delete();
        Income::where('car_id', $car->id)->delete();
        Reminder::where('car_id', $car->id)->delete();

        $car->delete();

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно удалён!');
    }

    /**
     * Export cars to CSV.
     */
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