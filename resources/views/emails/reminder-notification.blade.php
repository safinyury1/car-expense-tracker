<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Напоминание о ТО</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e2e8f0; border-top: none; }
        .reminder-card { background: white; padding: 20px; border-radius: 10px; border-left: 4px solid #3b82f6; margin: 15px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .btn { display: inline-block; background: #3b82f6; color: white; padding: 10px 25px; border-radius: 8px; text-decoration: none; }
        .btn:hover { background: #1d4ed8; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; }
        .urgent { color: #dc2626; font-weight: bold; }
        .soon { color: #f59e0b; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">🔔 AutoCost</h1>
        <p style="margin: 5px 0 0; opacity: 0.9;">Напоминание о техническом обслуживании</p>
    </div>

    <div class="content">
        <h2>Здравствуйте, {{ $user->name }}!</h2>
        
        <p>Напоминаем вам о запланированном техническом обслуживании:</p>

        <div class="reminder-card">
            <h3 style="margin-top: 0; color: #1e293b;">🚗 {{ $car->brand }} {{ $car->model }}</h3>
            
            <p><strong>📌 Задача:</strong> {{ $reminder->title }}</p>
            
            @if($reminder->due_odometer)
                <p><strong>📊 Пробег:</strong> {{ number_format($reminder->due_odometer) }} км</p>
            @endif
            
            @if($reminder->due_date)
                @php
                   $daysLeft = (int) now()->diffInDays($reminder->due_date, false);
                @endphp
                <p>
                    <strong>📅 Дата:</strong> {{ $reminder->due_date->format('d.m.Y') }}
                    @if($daysLeft == 0)
                        <span class="urgent">(СЕГОДНЯ!)</span>
                    @elseif($daysLeft == 1)
                        <span class="soon">(ЗАВТРА!)</span>
                    @elseif($daysLeft > 1)
                        <span>(через {{ $daysLeft }} дн.)</span>
                    @else
                        <span class="urgent">(ПРОСРОЧЕНО на {{ abs($daysLeft) }} дн.)</span>
                    @endif
                </p>
            @endif

            @if($reminder->service_notes)
                <p><strong>📝 Примечания:</strong> {{ $reminder->service_notes }}</p>
            @endif
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ route('reminders.show', $reminder) }}" class="btn">
                📋 Перейти к напоминанию
            </a>
        </div>

        <p style="color: #64748b; font-size: 14px;">
            💡 Вы можете отметить это напоминание как выполненное в вашем аккаунте AutoCost.
        </p>
    </div>
</body>
</html>