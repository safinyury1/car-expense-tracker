<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasCar
{
    public function handle(Request $request, Closure $next)
    {
        // Пропускаем страницы создания автомобиля и сохранения
        if ($request->routeIs('cars.create') || 
            $request->routeIs('cars.create.form') || 
            $request->routeIs('cars.store')) {
            return $next($request);
        }
        
        if (Auth::check() && Auth::user()->cars()->count() == 0) {
            return redirect()->route('cars.create')->with('warning', 'Сначала добавьте автомобиль!');
        }

        return $next($request);
    }
}