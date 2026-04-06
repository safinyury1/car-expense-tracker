<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefuelingController;
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
    
    // Ресурсные маршруты без метода show
    Route::resource('cars', CarController::class)->except(['show']);
    Route::resource('expenses', ExpenseController::class)->except(['show']);
    Route::resource('refuelings', RefuelingController::class)->except(['show']);
    Route::resource('reminders', ReminderController::class)->except(['show']);
    
    // Экспорт CSV
    Route::get('expenses/export-csv', [ExpenseController::class, 'exportCsv'])->name('expenses.export-csv');
    Route::get('refuelings/export-csv', [RefuelingController::class, 'exportCsv'])->name('refuelings.export-csv');
    Route::get('cars/export-csv', [CarController::class, 'exportCsv'])->name('cars.export-csv');
    
    // Переключение статуса напоминания
    Route::patch('reminders/{reminder}/toggle', [ReminderController::class, 'toggle'])->name('reminders.toggle');
});

require __DIR__.'/auth.php';