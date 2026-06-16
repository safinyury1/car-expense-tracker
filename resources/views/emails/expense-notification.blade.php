<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новый расход</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e2e8f0; border-top: none; }
        .expense-card { background: white; padding: 20px; border-radius: 10px; border-left: 4px solid #ef4444; margin: 15px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .btn { display: inline-block; background: #3b82f6; color: white; padding: 10px 25px; border-radius: 8px; text-decoration: none; }
        .btn:hover { background: #1d4ed8; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; }
        .amount { color: #dc2626; font-weight: bold; font-size: 18px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">💰 AutoCost</h1>
        <p style="margin: 5px 0 0; opacity: 0.9;">Новый расход</p>
    </div>

    <div class="content">
        <h2>Здравствуйте, {{ $user->name }}!</h2>
        
        <p>Был добавлен новый расход для вашего автомобиля:</p>

        <div class="expense-card">
            <h3 style="margin-top: 0; color: #1e293b;">🚗 {{ $car->brand }} {{ $car->model }}</h3>
            
            <p><strong>📌 Название:</strong> {{ $expense->title ?? $expense->category->name }}</p>
            <p><strong>📂 Категория:</strong> {{ $expense->category->name }}</p>
            <p><strong>💰 Сумма:</strong> <span class="amount">{{ number_format($expense->amount, 2) }} {{ $car->currency ?? '₽' }}</span></p>
            
            @if($expense->odometer)
                <p><strong>📊 Пробег:</strong> {{ number_format($expense->odometer) }} км</p>
            @endif
            
            <p><strong>📅 Дата:</strong> {{ $expense->date->format('d.m.Y') }}</p>
            
            @if($expense->description)
                <p><strong>📝 Описание:</strong> {{ $expense->description }}</p>
            @endif
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ route('expenses.show', $expense) }}" class="btn">
                📋 Перейти к расходу
            </a>
        </div>
    </div>
</body>
</html>