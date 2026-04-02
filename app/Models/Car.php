<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'year',
        'vin',
        'initial_odometer',
    ];

    protected $casts = [
        'year' => 'integer',
        'initial_odometer' => 'integer',
    ];

    // Связь: автомобиль принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь: автомобиль имеет много расходов
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Связь: автомобиль имеет много заправок
    public function refuelings()
    {
        return $this->hasMany(Refueling::class);
    }

    // Связь: автомобиль имеет много напоминаний
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}