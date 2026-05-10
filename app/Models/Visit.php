<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'url',
        'method',
        'user_agent',
        'page_title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}