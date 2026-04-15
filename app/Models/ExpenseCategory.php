<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'user_id',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Исправлено: указываем правильное имя внешнего ключа
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

    public static function getCategoriesForUser($userId)
    {
        return self::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('is_default', true);
        })->orderBy('is_default', 'desc')->orderBy('name')->get();
    }
}