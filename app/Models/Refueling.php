<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refueling extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'date',
        'liters',
        'price_per_liter',
        'total_amount',
        'odometer',
        'gas_station',
    ];

    protected $casts = [
        'date' => 'date',
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'odometer' => 'integer',
    ];

    // Связь: заправка принадлежит автомобилю
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}