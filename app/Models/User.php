<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'theme',
        'language',
        'notify_reminders',
        'notify_expenses',
        'notify_refuelings',
        'notify_summary',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notify_reminders' => 'boolean',
            'notify_expenses' => 'boolean',
            'notify_refuelings' => 'boolean',
            'notify_summary' => 'boolean',
        ];
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}