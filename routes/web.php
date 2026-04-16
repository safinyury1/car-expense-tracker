<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefuelingController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Дашборд (статистика, графики)
Route::middleware(['auth', 'has.car'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth', 'has.car'])->get('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');

// Редирект после логина/регистрации
Route::middleware('auth')->get('/home', function () {
    $user = Auth::user();
    if ($user->cars()->count() == 0) {
        return redirect()->route('cars.create');
    }
    return redirect()->route('overview.index');
})->name('home');

Route::middleware('auth')->group(function () {
    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index')->middleware('has.car');
    Route::delete('/history/{type}/{id}', [HistoryController::class, 'destroy'])->name('history.destroy')->middleware('has.car');
    

    // settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme');
    Route::post('/settings/language', [SettingsController::class, 'updateLanguage'])->name('settings.language');
    // Обзор
    Route::get('/overview', [OverviewController::class, 'index'])->name('overview.index')->middleware('has.car');
    
    // Автомобили
    Route::get('/cars/create-form', [CarController::class, 'createForm'])->name('cars.create.form');
    Route::resource('cars', CarController::class)->except(['show']);
    
    // Обновление фото и пробега
    Route::patch('/cars/{car}/update-photo', [CarController::class, 'updatePhoto'])->name('cars.update.photo');
    Route::patch('/cars/{car}/update-odometer', [CarController::class, 'updateOdometer'])->name('cars.update.odometer');
    
    // Расходы
    Route::resource('expenses', ExpenseController::class)->except(['show'])->middleware('has.car');
    Route::get('expenses/export-csv', [ExpenseController::class, 'exportCsv'])->name('expenses.export-csv')->middleware('has.car');
    
    // Заправки
    Route::resource('refuelings', RefuelingController::class)->except(['show'])->middleware('has.car');
    Route::get('refuelings/export-csv', [RefuelingController::class, 'exportCsv'])->name('refuelings.export-csv')->middleware('has.car');
    
    // Напоминания
    Route::resource('reminders', ReminderController::class)->except(['show'])->middleware('has.car');
    Route::patch('reminders/{reminder}/toggle', [ReminderController::class, 'toggle'])->name('reminders.toggle')->middleware('has.car');
    
    // Категории
    Route::resource('categories', CategoryController::class)->except(['show'])->middleware('has.car');
    
    // Сравнение
    Route::get('/compare', [CompareController::class, 'index'])->name('compare.index')->middleware('has.car');
    
    // Экспорт автомобилей
    Route::get('cars/export-csv', [CarController::class, 'exportCsv'])->name('cars.export-csv');
});

require __DIR__.'/auth.php';