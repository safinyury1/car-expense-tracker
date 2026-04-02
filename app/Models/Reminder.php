<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'title',
        'due_odometer',
        'due_date',
        'is_completed',
    ];

    protected $casts = [
        'due_date' => 'date',
        'due_odometer' => 'integer',
        'is_completed' => 'boolean',
    ];

    // Связь: напоминание принадлежит автомобилю
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}