<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои категории расходов') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Создать категорию
                        </a>
                    </div>

                    <!-- Только пользовательские категории -->
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-3 pb-2 border-b">Мои категории</h3>
                        @if($userCategories->isEmpty())
                            <p class="text-gray-500 text-center py-8">У вас пока нет своих категорий. Создайте первую!</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($userCategories as $category)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl">{{ $category->icon }}</span>
                                            <span class="text-gray-700">{{ $category->name }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900">✏️</a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Удалить категорию?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>