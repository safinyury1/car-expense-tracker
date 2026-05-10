<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Не отслеживаем админку, логин, регистрацию и AJAX
        $excludePaths = ['admin', 'login', 'register', 'logout', 'sanctum', 'debugbar'];
        $shouldExclude = false;
        
        foreach ($excludePaths as $path) {
            if (str_contains($request->path(), $path)) {
                $shouldExclude = true;
                break;
            }
        }
        
        // Записываем только GET-запросы (просмотры страниц)
        if (!$shouldExclude && $request->isMethod('get') && !$request->ajax()) {
            Visit::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'url' => str_replace(['http://127.0.0.1:8000', 'http://localhost:8000'], '', $request->fullUrl()),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        return $next($request);
    }
}