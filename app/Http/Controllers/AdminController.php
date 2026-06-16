<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Car;
use App\Models\Expense;
use App\Models\Refueling;
use App\Models\Income;
use App\Models\Reminder;
use App\Models\Visit;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'users' => User::count(),
            'cars' => Car::count(),
            'expenses' => Expense::count(),
            'refuelings' => Refueling::count(),
            'incomes' => Income::count(),
            'services' => Reminder::where('service_type', 'service')->count(),
        ];
        
        // ПАГИНАЦИЯ ДЛЯ ПОСЛЕДНИХ ПОЛЬЗОВАТЕЛЕЙ (10 на страницу)
        $recentUsers = User::orderBy('created_at', 'desc')->paginate(10);
        $recentCars = Car::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        
        // ==========================================
        // СТАТИСТИКА ПОСЕЩЕНИЙ (ВСЕ ПОСЕЩЕНИЯ)
        // ==========================================
        
        $today = today();
        $weekAgo = now()->subDays(7);
        $monthAgo = now()->subDays(30);
        
        // ВСЕГО ЗАПИСЕЙ
        $totalVisits = Visit::count();
        
        // ВСЕ ПОСЕЩЕНИЯ ЗА ПЕРИОДЫ (не уникальные)
        $todayVisits = Visit::whereDate('created_at', $today)->count();
        $yesterdayVisits = Visit::whereDate('created_at', today()->subDay())->count();
        $weekVisits = Visit::where('created_at', '>=', $weekAgo)->count();
        $monthVisits = Visit::where('created_at', '>=', $monthAgo)->count();
        
        // УНИКАЛЬНЫЕ ПОСЕТИТЕЛИ (для информации)
        $totalUniqueVisitors = Visit::distinct('user_id')->count('user_id');
        $activeUsers = Visit::where('created_at', '>=', $weekAgo)->distinct('user_id')->count('user_id');
        
        // УНИКАЛЬНЫЕ IP
        $totalUniqueIps = Visit::distinct('ip')->count('ip');
        
        // ПОСЕЩЕНИЯ ПО ДНЯМ (последние 7 дней) - ВСЕ посещения
        $visitsByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Visit::whereDate('created_at', $date)->count();
            $visitsByDay[] = [
                'date' => $date->format('d.m'),
                'count' => $count,
                'full_date' => $date->format('Y-m-d')
            ];
        }
        
        // ЛУЧШИЙ ДЕНЬ
        $bestDay = Visit::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('count', 'desc')
            ->first();
        $bestDayVisits = $bestDay ? $bestDay->count : 0;
        
        // СРЕДНЕЕ В ДЕНЬ
        $daysCount = Visit::select(DB::raw('DATE(created_at) as date'))
            ->distinct()
            ->get()
            ->count();
        $avgVisitsPerDay = $daysCount > 0 ? round($totalVisits / $daysCount, 1) : 0;

        // КАТЕГОРИИ РАСХОДОВ
        $categoryStats = Expense::join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->select('expense_categories.name', DB::raw('SUM(expenses.amount) as total'))
            ->groupBy('expense_categories.name')
            ->orderBy('total', 'desc')
            ->get();

        $categoryColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#14b8a6'];
        
        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentCars',
            // Статистика посещений
            'totalVisits',
            'todayVisits',
            'yesterdayVisits',
            'weekVisits',
            'monthVisits',
            'totalUniqueVisitors',
            'totalUniqueIps',
            'visitsByDay',
            'bestDayVisits',
            'avgVisitsPerDay',
            'activeUsers',
            'categoryStats',
            'categoryColors'
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

    // Сброс пароля пользователя
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Используйте раздел профиля для смены своего пароля');
        }
        
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Пароль пользователя успешно изменён!');
    }

    // Обновление email пользователя
    public function updateEmail(Request $request, $id)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Используйте раздел профиля для смены своего email');
        }
        
        $user->email = $request->new_email;
        $user->save();

        return redirect()->back()->with('success', 'Email пользователя успешно изменён на ' . $user->email);
    }
}