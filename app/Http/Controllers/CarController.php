<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CarController extends Controller
{
    /**
     * Список автомобилей пользователя
     */
    public function index()
    {
        $cars = Auth::user()->cars()->orderBy('id', 'desc')->get();
        return view('cars.index', compact('cars'));
    }

    /**
     * Форма создания нового автомобиля
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Сохранение нового автомобиля
     */
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

    /**
     * Форма редактирования автомобиля
     */
    public function edit(Car $car)
    {
        // Проверяем, что автомобиль принадлежит текущему пользователю
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }
        return view('cars.edit', compact('car'));
    }

    /**
     * Обновление данных автомобиля
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
     * Удаление автомобиля
     */
    public function destroy(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        $car->delete();

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно удалён!');
    }

    /**
 * Экспорт автомобилей в CSV
 */
public function exportCsv()
{
    $cars = Auth::user()->cars;
    
    $filename = 'cars_' . date('Y-m-d_H-i-s') . '.csv';
    
    $handle = fopen('php://temp', 'w+');
    
    // Добавляем BOM для правильного отображения кириллицы в Excel
    fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Заголовки (разделитель — точка с запятой)
    fputcsv($handle, ['ID', 'Марка', 'Модель', 'Год выпуска', 'VIN-код', 'Начальный пробег (км)'], ';');
    
    // Данные
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