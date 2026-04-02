<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'category_id',
        'date',
        'amount',
        'odometer',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'odometer' => 'integer',
    ];

    // Связь: расход принадлежит автомобилю
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Связь: расход принадлежит категории
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}