<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->language) {
            App::setLocale(Auth::user()->language);
        } elseif (session()->has('locale')) {
            App::setLocale(session('locale'));
        } else {
            App::setLocale('ru');
        }
        
        return $next($request);
    }
}