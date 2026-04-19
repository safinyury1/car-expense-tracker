<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::where('is_default', true)->get();
        $userCategories = ExpenseCategory::where('user_id', Auth::id())->get();
        
        return view('categories.index', compact('categories', 'userCategories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Проверяем, существует ли уже категория с таким названием у пользователя
        $existingCategory = ExpenseCategory::where('name', $request->name)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('is_default', true);
            })
            ->first();
        
        if ($existingCategory) {
            return redirect()->back()
                ->with('error', 'Категория с таким названием уже существует!')
                ->withInput();
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        ExpenseCategory::create([
            'name' => $validated['name'],
            'user_id' => Auth::id(),
            'is_default' => false,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно создана!');
    }

    public function edit(ExpenseCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, ExpenseCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Проверяем, существует ли уже категория с таким названием (исключая текущую)
        $existingCategory = ExpenseCategory::where('name', $request->name)
            ->where('id', '!=', $category->id)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('is_default', true);
            })
            ->first();
        
        if ($existingCategory) {
            return redirect()->back()
                ->with('error', 'Категория с таким названием уже существует!')
                ->withInput();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно обновлена!');
    }

    public function destroy(ExpenseCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        if ($category->expenses()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Нельзя удалить категорию, которая используется в расходах!');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Категория успешно удалена!');
    }
}