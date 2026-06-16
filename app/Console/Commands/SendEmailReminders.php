<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Mail\ReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailReminders extends Command
{
    protected $signature = 'reminders:email';
    protected $description = 'Send email reminders about upcoming maintenance';

    public function handle()
    {
        $this->info('🔄 Проверка напоминаний для email...');

        // Получаем напоминания на сегодня и завтра
        $reminders = Reminder::where('is_completed', false)
            ->where('due_date', '<=', now()->addDay())
            ->where('due_date', '>=', now()->subDay())
            ->with('car.user')
            ->get();

        $this->info('📋 Найдено напоминаний: ' . $reminders->count());

        $sentCount = 0;

        foreach ($reminders as $reminder) {
            $user = $reminder->car->user;
            
            if (!$user->email) {
                $this->warn("⚠️ У пользователя нет email");
                continue;
            }

            try {
                // Проверяем, включены ли уведомления у пользователя
                if (isset($user->notify_reminders) && !$user->notify_reminders) {
                    $this->warn("⚠️ У пользователя {$user->email} отключены уведомления о ТО");
                    continue;
                }

                Mail::to($user->email)->send(new ReminderNotification($reminder, $user, $reminder->car));

                $sentCount++;
                $this->info("✅ Отправлено пользователю: {$user->email}");
                
            } catch (\Exception $e) {
                $this->error("❌ Ошибка отправки: {$e->getMessage()}");
            }
        }

        $this->info("✅ Отправлено напоминаний: {$sentCount}");
        return 0;
    }
}