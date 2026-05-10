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
        'odometer',
        'photo',
        'distance_unit',
        'volume_unit',
        'currency',
    ];

    protected $casts = [
        'year' => 'integer',
        'initial_odometer' => 'integer',
        'odometer' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function refuelings()
    {
        return $this->hasMany(Refueling::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function getCurrentOdometerAttribute()
    {
        $maxExpenseOdometer = $this->expenses()->max('odometer') ?? 0;
        $maxRefuelingOdometer = $this->refuelings()->max('odometer') ?? 0;
        $maxIncomeOdometer = $this->incomes()->max('odometer') ?? 0;
        
        $maxOdometer = max(
            $maxExpenseOdometer, 
            $maxRefuelingOdometer, 
            $maxIncomeOdometer, 
            $this->odometer, 
            $this->initial_odometer
        );
        
        return $maxOdometer;
    }
}