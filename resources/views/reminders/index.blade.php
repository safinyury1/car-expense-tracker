<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Напоминания о ТО') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Фильтр по автомобилям -->
                    <div class="mb-4 flex justify-between items-center">
                        <form method="GET" action="{{ route('reminders.index') }}" class="flex gap-2">
                            <select name="car_id" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Все автомобили</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ ($carId ?? '') == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Фильтр</button>
                        </form>
                        
                        <a href="{{ route('reminders.create', ['car_id' => $carId ?? '']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Добавить напоминание
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($reminders->isEmpty())
                        <p class="text-gray-500">У вас пока нет добавленных напоминаний.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Статус</th>
                                        <th class="px-4 py-2 text-left">Автомобиль</th>
                                        <th class="px-4 py-2 text-left">Напоминание</th>
                                        <th class="px-4 py-2 text-left">Пробег (км)</th>
                                        <th class="px-4 py-2 text-left">Дата</th>
                                        <th class="px-4 py-2 text-left">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reminders as $reminder)
                                        <tr class="border-b {{ $reminder->is_completed ? 'bg-gray-50' : '' }}">
                                            <td class="px-4 py-2">
                                                <form action="{{ route('reminders.toggle', $reminder) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-2xl {{ $reminder->is_completed ? 'text-green-600' : 'text-gray-400 hover:text-green-600' }}" title="{{ $reminder->is_completed ? 'Отметить как невыполненное' : 'Отметить как выполненное' }}">
                                                        {{ $reminder->is_completed ? '✓' : '◯' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-2">{{ $reminder->car->brand }} {{ $reminder->car->model }}</td>
                                            <td class="px-4 py-2 {{ $reminder->is_completed ? 'line-through text-gray-500' : 'font-medium' }}">
                                                {{ $reminder->title }}
                                                @if($reminder->service_type === 'service' && $reminder->service_cost > 0)
                                                    <span class="text-xs text-gray-400 ml-2">({{ number_format($reminder->service_cost, 2) }} ₽)</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">{{ number_format($reminder->due_odometer) }} км</td>
                                            <td class="px-4 py-2">{{ $reminder->due_date ? $reminder->due_date->format('d.m.Y') : '—' }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('reminders.edit', $reminder) }}" class="text-blue-600 hover:text-blue-900 mr-3">✏️ Ред.</a>
                                                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="inline-block" onsubmit="return confirm('Вы уверены?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">🗑️ Удалить</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $reminders->appends(['car_id' => $carId ?? ''])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>