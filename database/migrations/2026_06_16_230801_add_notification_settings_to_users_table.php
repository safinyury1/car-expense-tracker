<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Добавляем колонки без привязки к telegram_notifications
            if (!Schema::hasColumn('users', 'notify_reminders')) {
                $table->boolean('notify_reminders')->default(true);
            }
            if (!Schema::hasColumn('users', 'notify_expenses')) {
                $table->boolean('notify_expenses')->default(true)->after('notify_reminders');
            }
            if (!Schema::hasColumn('users', 'notify_refuelings')) {
                $table->boolean('notify_refuelings')->default(true)->after('notify_expenses');
            }
            if (!Schema::hasColumn('users', 'notify_summary')) {
                $table->boolean('notify_summary')->default(true)->after('notify_refuelings');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_reminders',
                'notify_expenses',
                'notify_refuelings',
                'notify_summary'
            ]);
        });
    }
};