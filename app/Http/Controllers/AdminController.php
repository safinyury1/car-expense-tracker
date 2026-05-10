<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Income;
use App\Models\Reminder;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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
        
        // Статистика посещений
        $totalVisits = Visit::count();
        $uniqueVisitors = Visit::distinct('user_id')->count('user_id');
        $uniqueIps = Visit::distinct('ip')->count('ip');
        
        $todayVisits = Visit::whereDate('created_at', today())->count();
        $yesterdayVisits = Visit::whereDate('created_at', today()->subDay())->count();
        $weekVisits = Visit::where('created_at', '>=', now()->subWeek())->count();
        $monthVisits = Visit::where('created_at', '>=', now()->subMonth())->count();
        
        $recentVisits = Visit::with('user')->latest()->limit(20)->get();
        
        // Динамика по дням (последние 7 дней)
        $visitsByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Visit::whereDate('created_at', $date)->count();
            $visitsByDay[] = [
                'date' => now()->subDays($i)->format('d.m'),
                'count' => $count,
                'full_date' => $date
            ];
        }
        
        // Лучший день
        $bestDay = Visit::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('count', 'desc')
            ->first();
        $bestDayVisits = $bestDay ? $bestDay->count : 0;
        
        // Среднее посещений в день
        $daysCount = Visit::select(DB::raw('DATE(created_at) as date'))
            ->distinct()
            ->get()
            ->count();
        $avgVisitsPerDay = $daysCount > 0 ? round($totalVisits / $daysCount, 1) : 0;
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'recentCars',
            'totalVisits',
            'uniqueVisitors',
            'uniqueIps',
            'todayVisits',
            'yesterdayVisits',
            'weekVisits',
            'monthVisits',
            'recentVisits',
            'visitsByDay',
            'bestDayVisits',
            'avgVisitsPerDay'
        ));
    }
    
    public function users(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        
        $query = User::orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }
        
        $users = $query->paginate(20)->withQueryString();
        
        return view('admin.users', compact('users', 'search', 'role'));
    }
    
    public function cars(Request $request)
    {
        $userId = $request->get('user_id');
        $search = $request->get('search');
        
        $query = Car::with('user');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('vin', 'like', "%{$search}%");
            });
        }
        
        $cars = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $users = User::all();
        
        return view('admin.cars', compact('cars', 'users', 'userId', 'search'));
    }
    
    public function expenses(Request $request)
    {
        $userId = $request->get('user_id');
        $search = $request->get('search');
        
        $query = Expense::with('car', 'category');
        
        if ($userId) {
            $query->whereHas('car', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%");
            });
        }
        
        $expenses = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $users = User::all();
        
        return view('admin.expenses', compact('expenses', 'users', 'userId', 'search'));
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
        
        $carIds = $user->cars->pluck('id')->toArray();
        
        $totalExpenses = Expense::whereIn('car_id', $carIds)->sum('amount');
        $totalRefuelings = Refueling::whereIn('car_id', $carIds)->sum('total_amount');
        $totalIncomes = Income::whereIn('car_id', $carIds)->sum('amount');
        $totalServices = Reminder::whereIn('car_id', $carIds)->where('service_type', 'service')->count();
        
        $allExpenses = $totalExpenses + $totalRefuelings;
        $netProfit = $totalIncomes - $allExpenses;
        
        return view('admin.user-show', compact(
            'user', 
            'totalExpenses', 
            'totalRefuelings', 
            'totalIncomes', 
            'totalServices', 
            'allExpenses', 
            'netProfit'
        ));
    }
}