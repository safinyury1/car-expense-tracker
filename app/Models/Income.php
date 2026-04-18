<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'date',
        'title',
        'amount',
        'odometer',
        'description',
        'category',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'odometer' => 'integer',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}