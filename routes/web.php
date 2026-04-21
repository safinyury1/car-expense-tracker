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
use App\Http\Controllers\CarSettingsController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IncomeListController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Дашборд (статистика, графики)
Route::middleware(['auth', 'has.car'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    
    // Обзор
    Route::get('/overview', [OverviewController::class, 'index'])->name('overview.index')->middleware('has.car');
    
    // История
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index')->middleware('has.car');
    Route::delete('/history/{type}/{id}', [HistoryController::class, 'destroy'])->name('history.destroy')->middleware('has.car');
    
    // Настройки
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme');
    
    // Настройки авто
    Route::get('/car-settings', [CarSettingsController::class, 'index'])->name('car-settings.index');
    Route::post('/car-settings/distance-unit', [CarSettingsController::class, 'updateDistanceUnit'])->name('car-settings.distance-unit');
    Route::post('/car-settings/volume-unit', [CarSettingsController::class, 'updateVolumeUnit'])->name('car-settings.volume-unit');
    Route::post('/car-settings/currency', [CarSettingsController::class, 'updateCurrency'])->name('car-settings.currency');
    Route::post('/car-settings/delete-all', [CarSettingsController::class, 'deleteAllData'])->name('car-settings.delete-all');
    Route::post('/car-settings/delete-car', [CarSettingsController::class, 'deleteCar'])->name('car-settings.delete-car');
    
    // Автомобили
    Route::get('/cars/create-form', [CarController::class, 'createForm'])->name('cars.create.form');
    Route::resource('cars', CarController::class)->except(['show']);
    Route::patch('/cars/{car}/update-photo', [CarController::class, 'updatePhoto'])->name('cars.update.photo');
    Route::patch('/cars/{car}/update-odometer', [CarController::class, 'updateOdometer'])->name('cars.update.odometer');
    
    // Расходы
    Route::resource('expenses', ExpenseController::class)->except(['show'])->middleware('has.car');
    Route::get('expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show')->middleware('has.car');
    Route::get('expenses/export-csv', [ExpenseController::class, 'exportCsv'])->name('expenses.export-csv')->middleware('has.car');
    
    // Заправки
    Route::resource('refuelings', RefuelingController::class)->except(['show'])->middleware('has.car');
    Route::get('refuelings/{refueling}', [RefuelingController::class, 'show'])->name('refuelings.show')->middleware('has.car');
    Route::get('refuelings/export-csv', [RefuelingController::class, 'exportCsv'])->name('refuelings.export-csv')->middleware('has.car');
    
    // Напоминания
    Route::resource('reminders', ReminderController::class)->except(['show'])->middleware('has.car');
    Route::get('reminders/{reminder}', [ReminderController::class, 'show'])->name('reminders.show')->middleware('has.car');
    Route::patch('reminders/{reminder}/toggle', [ReminderController::class, 'toggle'])->name('reminders.toggle')->middleware('has.car');
    
    // Категории
    Route::resource('categories', CategoryController::class)->except(['show'])->middleware('has.car');
    
    // Сравнение
    Route::get('/compare', [CompareController::class, 'index'])->name('compare.index')->middleware('has.car');
    
    // Доходы (CRUD)
    Route::get('/incomes/create', [IncomeController::class, 'create'])->name('incomes.create')->middleware('has.car');
    Route::post('/incomes', [IncomeController::class, 'store'])->name('incomes.store')->middleware('has.car');
    Route::get('/incomes/{income}', [IncomeController::class, 'show'])->name('incomes.show')->middleware('has.car');
    Route::get('/incomes/{income}/edit', [IncomeController::class, 'edit'])->name('incomes.edit')->middleware('has.car');
    Route::put('/incomes/{income}', [IncomeController::class, 'update'])->name('incomes.update')->middleware('has.car');
    Route::delete('/incomes/{income}', [IncomeController::class, 'destroy'])->name('incomes.destroy')->middleware('has.car');
    
    // Доходы (список)
    Route::get('/incomes-list', [IncomeListController::class, 'index'])->name('incomes-list.index')->middleware('has.car');
    Route::delete('/incomes-list/{income}', [IncomeListController::class, 'destroy'])->name('incomes-list.destroy')->middleware('has.car');
    
    // Обслуживание (все маршруты в одном контроллере)
    Route::get('/service', [ServiceController::class, 'index'])->name('service.index')->middleware('has.car');
    Route::get('/service/create', [ServiceController::class, 'create'])->name('service.create')->middleware('has.car');
    Route::post('/service', [ServiceController::class, 'store'])->name('service.store')->middleware('has.car');
    Route::get('/service/{reminder}', [ServiceController::class, 'show'])->name('service.show')->middleware('has.car');
    Route::delete('/service/{service}', [ServiceController::class, 'destroy'])->name('service.destroy')->middleware('has.car');
    
    // Руководство
    Route::get('/guide', [GuideController::class, 'index'])->name('guide.index')->middleware('auth');
});

// Админ-панель
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'userShow'])->name('user.show');
    Route::get('/cars', [AdminController::class, 'cars'])->name('cars');
    Route::get('/cars/{id}', [AdminController::class, 'carShow'])->name('car.show');
    Route::get('/expenses', [AdminController::class, 'expenses'])->name('expenses');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete.user');
    Route::get('/users/{id}/make-admin', [AdminController::class, 'makeAdmin'])->name('make.admin');
    Route::get('/users/{id}/make-user', [AdminController::class, 'makeUser'])->name('make.user');
});

require __DIR__.'/auth.php';