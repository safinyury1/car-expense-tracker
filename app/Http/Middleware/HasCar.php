<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasCar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Пропускаем маршруты, которые не требуют наличия автомобиля
        if ($request->routeIs('cars.create') || 
            $request->routeIs('cars.create.form') || 
            $request->routeIs('cars.store') || 
            $request->routeIs('cars.index') ||
            $request->routeIs('cars.export-csv')) {
            return $next($request);
        }
        
        if (Auth::check() && Auth::user()->cars()->count() == 0) {
            return redirect()->route('cars.create')->with('warning', 'Сначала добавьте автомобиль!');
        }

        return $next($request);
    }
}