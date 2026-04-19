<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reminder;
use App\Traits\ConvertsUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    use ConvertsUnits;

    public function index(Request $request)
    {
        $carId = $request->get('car_id');
        
        $query = Reminder::with('car')
            ->whereHas('car', function ($q) {
                $q->where('user_id', Auth::id());
            });
        
        if ($carId) {
            $query->where('car_id', $carId);
            $car = Car::find($carId);
        }
        
        $reminders = $query->orderBy('is_completed', 'asc')
            ->orderBy('due_odometer', 'asc')
            ->paginate(20);
        
        foreach ($reminders as $reminder) {
            if (isset($car)) {
                $reminder->converted_odometer = $this->convertDistance($reminder->due_odometer, $car);
                $reminder->distance_unit = $this->getDistanceUnit($car);
            } else {
                $reminder->converted_odometer = $reminder->due_odometer;
                $reminder->distance_unit = 'км';
            }
        }
        
        $cars = Auth::user()->cars;
        
        return view('reminders.index', compact('reminders', 'cars', 'carId'));
    }

    public function create(Request $request)
    {
        $cars = Auth::user()->cars;
        $selectedCar = $request->get('car_id');
        
        return view('reminders.create', compact('cars', 'selectedCar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'title' => 'required|string|max:255',
            'due_odometer' => 'required|integer|min:0',
            'due_date' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);
        
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        
        Reminder::create($validated);
        
        return redirect()->route('reminders.index', ['car_id' => $validated['car_id']])
            ->with('success', 'Напоминание успешно добавлено!');
    }

    public function show(Reminder $reminder)
    {
        if ($reminder->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        return view('reminders.show', compact('reminder', 'cars'));
    }

    public function edit(Reminder $reminder)
    {
        if ($reminder->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cars = Auth::user()->cars;
        
        return view('reminders.edit', compact('reminder', 'cars'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'title' => 'required|string|max:255',
            'due_odometer' => 'required|integer|min:0',
            'due_date' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);
        
        $reminder->update($validated);
        
        return redirect()->route('reminders.index', ['car_id' => $reminder->car_id])
            ->with('success', 'Напоминание успешно обновлено!');
    }

    public function toggle(Request $request, Reminder $reminder)
    {
        if ($reminder->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $reminder->is_completed = !$reminder->is_completed;
        $reminder->save();
        
        return redirect()->route('reminders.index', ['car_id' => $reminder->car_id])
            ->with('success', $reminder->is_completed ? 'Напоминание отмечено как выполненное!' : 'Напоминание отмечено как невыполненное!');
    }

    public function destroy(Reminder $reminder)
    {
        if ($reminder->car->user_id !== Auth::id()) {
            abort(403);
        }
        
        $carId = $reminder->car_id;
        $reminder->delete();
        
        return redirect()->route('reminders.index', ['car_id' => $carId])
            ->with('success', 'Напоминание успешно удалено!');
    }
}