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
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('expense_categories', 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'icon' => 'nullable|string|max:10',
        ]);

        ExpenseCategory::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? '📌',
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

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('expense_categories', 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($category->id),
            ],
            'icon' => 'nullable|string|max:10',
        ]);

        $category->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? $category->icon,
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