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

    /**
     * Проверка, был ли визит сегодня от пользователя или IP
     */
    public static function hasVisitedToday($userId = null, $ip = null)
    {
        $query = self::whereDate('created_at', today());
        
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($ip) {
            $query->where('ip', $ip);
        }
        
        return $query->exists();
    }

    /**
     * Получить количество уникальных посетителей за период
     */
    public static function uniqueVisitors($startDate, $endDate = null)
    {
        $query = self::whereDate('created_at', '>=', $startDate);
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return $query->distinct('user_id')->count('user_id');
    }

    /**
     * Получить количество уникальных IP за период
     */
    public static function uniqueIps($startDate, $endDate = null)
    {
        $query = self::whereDate('created_at', '>=', $startDate);
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return $query->distinct('ip')->count('ip');
    }
}