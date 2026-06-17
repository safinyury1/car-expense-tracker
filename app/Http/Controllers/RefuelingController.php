<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Refueling;
use App\Mail\RefuelingNotification;
use App\Traits\ConvertsUnits;
use App\Traits\ValidatesOdometer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class RefuelingController extends Controller
{
    use ConvertsUnits, ValidatesOdometer;

    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['date', 'liters', 'total_amount', 'odometer', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'date';
        }
        
        $query = Refueling::with('car')
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
            $car = Car::find($carId);
        }
        
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('gas_station', 'like', "%{$search}%")
                ->orWhereHas('car', function ($car) use ($search) {
                    $car->where('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            });
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        $refuelings = $query->paginate(20)->appends($request->all());
        
        foreach ($refuelings as $refueling) {
            if (isset($car)) {
                $refueling->converted_amount = $this->convertCurrency($refueling->total_amount, $car);
                $refueling->converted_odometer = $this->convertDistance($refueling->odometer, $car);
                $refueling->converted_liters = $this->convertVolume($refueling->liters, $car);
                $refueling->converted_price = $this->convertCurrency($refueling->price_per_liter, $car);
                $refueling->currency = $this->getCurrencySymbol($car);
                $refueling->distance_unit = $this->getDistanceUnit($car);
                $refueling->volume_unit = $this->getVolumeUnit($car);
            } else {
                $refueling->converted_amount = $refueling->total_amount;
                $refueling->converted_odometer = $refueling->odometer;
                $refueling->converted_liters = $refueling->liters;
                $refueling->converted_price = $refueling->price_per_liter;
                $refueling->currency = '₽';
                $refueling->distance_unit = 'км';
                $refueling->volume_unit = 'л';
            }
        }
        
        $cars = Auth::user()->cars;
        
        return view('refuelings.index', compact('refuelings', 'cars', 'carId', 'search', 'dateFrom', 'dateTo', 'sortBy', 'sortOrder'));
    }

    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCar = $request->get('car_id');
        
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
            $car = Car::find($selectedCar);
            if ($car) {
                $lastOdometer = $lastOdometerByCar[$selectedCar] ?? 0;
                $maxOdometer = $this->convertDistance($lastOdometer, $car);
            }
        }
        
        return view('refuelings.create', compact('cars', 'selectedCar', 'maxOdometer', 'lastOdometer', 'lastOdometerByCar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'odometer' => 'nullable|integer|min:0',
            'gas_station' => 'nullable|string|max:255',
        ]);
        
        $validated['total_amount'] = $validated['liters'] * $validated['price_per_liter'];
        
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        if (!empty($validated['odometer'])) {
            $this->validateOdometer($validated['car_id'], $validated['odometer'], null, 'refueling');
            $car->odometer = $validated['odometer'];
            $car->save();
        }
        
        $refueling = Refueling::create($validated);
        
        // Отправка email уведомления ТОЛЬКО ЕСЛИ ВКЛЮЧЕНО
        try {
            $user = Auth::user();
            if ($user->notify_refuelings) {
                Mail::to($user->email)->send(new RefuelingNotification($refueling, $user, $car));
            }
        } catch (\Exception $e) {
            \Log::error('Ошибка отправки email уведомления: ' . $e->getMessage());
        }
        
        return redirect()->route('refuelings.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Заправка успешно добавлена!');
    }

    public function show(Refueling $refueling)
    {
        if ($refueling->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $car = $refueling->car;
        $refueling->converted_amount = $this->convertCurrency($refueling->total_amount, $car);
        $refueling->converted_odometer = $this->convertDistance($refueling->odometer, $car);
        $refueling->converted_liters = $this->convertVolume($refueling->liters, $car);
        $refueling->converted_price = $this->convertCurrency($refueling->price_per_liter, $car);
        $refueling->currency = $this->getCurrencySymbol($car);
        $refueling->distance_unit = $this->getDistanceUnit($car);
        $refueling->volume_unit = $this->getVolumeUnit($car);
        
        $cars = Auth::user()->cars;
        
        return view('refuelings.show', compact('refueling', 'cars'));
    }

    public function edit(Refueling $refueling)
    {
        if ($refueling->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        $car = $refueling->car;
        $refueling->converted_amount = $this->convertCurrency($refueling->total_amount, $car);
        $refueling->converted_odometer = $this->convertDistance($refueling->odometer, $car);
        $refueling->converted_liters = $this->convertVolume($refueling->liters, $car);
        $refueling->converted_price = $this->convertCurrency($refueling->price_per_liter, $car);
        $refueling->currency = $this->getCurrencySymbol($car);
        $refueling->distance_unit = $this->getDistanceUnit($car);
        $refueling->volume_unit = $this->getVolumeUnit($car);
        
        $maxOdometerKm = max(
            Expense::where('car_id', $refueling->car_id)->max('odometer') ?? 0,
            Refueling::where('car_id', $refueling->car_id)->where('id', '!=', $refueling->id)->max('odometer') ?? 0,
            Income::where('car_id', $refueling->car_id)->max('odometer') ?? 0
        );
        $maxOdometer = $this->convertDistance($maxOdometerKm, $refueling->car);
        
        // Получаем последний пробег для каждого автомобиля
        $lastOdometerByCar = [];
        foreach ($cars as $carItem) {
            $lastOdometerByCar[$carItem->id] = max(
                Expense::where('car_id', $carItem->id)->max('odometer') ?? 0,
                Refueling::where('car_id', $carItem->id)->max('odometer') ?? 0,
                Income::where('car_id', $carItem->id)->max('odometer') ?? 0,
                $carItem->initial_odometer ?? 0
            );
        }
        
        // Последний пробег для текущего автомобиля
        $lastOdometer = $lastOdometerByCar[$refueling->car_id] ?? 0;
        
        return view('refuelings.edit', compact('refueling', 'cars', 'maxOdometer', 'lastOdometer', 'lastOdometerByCar'));
    }

    public function update(Request $request, Refueling $refueling)
    {
        if ($refueling->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date' => 'required|date',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'odometer' => 'nullable|integer|min:0',
            'gas_station' => 'nullable|string|max:255',
        ]);
        
        $validated['total_amount'] = $validated['liters'] * $validated['price_per_liter'];
        
        if (!empty($validated['odometer'])) {
            $this->validateOdometer($validated['car_id'], $validated['odometer'], $refueling->id, 'refueling');
            $car = Car::find($refueling->car_id);
            $car->odometer = $validated['odometer'];
            $car->save();
        }
        
        $refueling->update($validated);
        
        return redirect()->route('refuelings.index', ['car_id' => $refueling->car_id])
            ->with('success', 'Заправка успешно обновлена!');
    }

    public function destroy(Refueling $refueling)
    {
        if ($refueling->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $refueling->car_id;
        $refueling->delete();
        
        return redirect()->route('refuelings.index', ['car_id' => $carId])
            ->with('success', 'Заправка успешно удалена!');
    }

    public function exportCsv(Request $request)
    {
        $carId = $request->get('car_id');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = Refueling::with('car')
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
        }
        
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('gas_station', 'like', "%{$search}%")
                ->orWhereHas('car', function ($car) use ($search) {
                    $car->where('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            });
        }
        
        $refuelings = $query->orderBy('date', 'desc')->get();
        
        $filename = 'refuelings_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, ['ID', 'Дата', 'Автомобиль', 'Литры', 'Цена/л (₽)', 'Сумма (₽)', 'Пробег (км)', 'АЗС'], ';');
        
        foreach ($refuelings as $refueling) {
            fputcsv($handle, [
                $refueling->id,
                $refueling->date->format('d.m.Y'),
                $refueling->car->brand . ' ' . $refueling->car->model,
                $refueling->liters,
                $refueling->price_per_liter,
                $refueling->total_amount,
                $refueling->odometer,
                $refueling->gas_station ?? '',
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