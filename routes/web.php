<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RefuelingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Ресурсный маршрут для автомобилей с проверкой авторизации
    Route::resource('cars', CarController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('refuelings', RefuelingController::class);
    Route::resource('reminders', ReminderController::class);
    Route::patch('reminders/{reminder}/toggle', [ReminderController::class, 'toggle'])->name('reminders.toggle');
});

require __DIR__.'/auth.php';