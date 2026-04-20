<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Income;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Конструктор удалён! Проверка admin теперь в маршрутах

    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'cars' => Car::count(),
            'expenses' => Expense::count(),
            'refuelings' => Refueling::count(),
            'incomes' => Income::count(),
            'services' => Reminder::where('service_type', 'service')->count(),
        ];
        
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recentCars = Car::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCars'));
    }
    
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }
    
    public function cars(Request $request)
    {
        $userId = $request->get('user_id');
        $query = Car::with('user');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $cars = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::all();
        
        return view('admin.cars', compact('cars', 'users', 'userId'));
    }
    
    public function expenses(Request $request)
    {
        $userId = $request->get('user_id');
        $query = Expense::with('car', 'category');
        
        if ($userId) {
            $query->whereHas('car', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        
        $expenses = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::all();
        
        return view('admin.expenses', compact('expenses', 'users', 'userId'));
    }
    
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Нельзя удалить самого себя');
        }
        
        foreach ($user->cars as $car) {
            $car->expenses()->delete();
            $car->refuelings()->delete();
            $car->incomes()->delete();
            $car->reminders()->delete();
            $car->delete();
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Пользователь удалён');
    }
    
    public function makeAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'admin';
        $user->save();
        
        return redirect()->back()->with('success', 'Пользователь назначен администратором');
    }
    
    public function makeUser($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'user';
        $user->save();
        
        return redirect()->back()->with('success', 'Права администратора сняты');
    }

    public function carShow($id)
{
    $car = Car::with('user', 'expenses.category', 'refuelings', 'incomes', 'reminders')->findOrFail($id);
    
    $totalExpenses = $car->expenses->sum('amount') + $car->refuelings->sum('total_amount');
    $totalIncome = $car->incomes->sum('amount');
    $netProfit = $totalIncome - $totalExpenses;
    
    return view('admin.car-show', compact('car', 'totalExpenses', 'totalIncome', 'netProfit'));
}

public function userShow($id)
{
    $user = User::with('cars')->findOrFail($id);
    
    // Статистика по пользователю
    $carIds = $user->cars->pluck('id')->toArray();
    
    $totalExpenses = Expense::whereIn('car_id', $carIds)->sum('amount');
    $totalRefuelings = Refueling::whereIn('car_id', $carIds)->sum('total_amount');
    $totalIncomes = Income::whereIn('car_id', $carIds)->sum('amount');
    $totalServices = Reminder::whereIn('car_id', $carIds)->where('service_type', 'service')->count();
    
    $allExpenses = $totalExpenses + $totalRefuelings;
    $netProfit = $totalIncomes - $allExpenses;
    
    return view('admin.user-show', compact('user', 'totalExpenses', 'totalRefuelings', 'totalIncomes', 'totalServices', 'allExpenses', 'netProfit'));
}
}