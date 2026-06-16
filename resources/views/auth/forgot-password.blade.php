<x-guest-layout>
    <div class="text-center mb-6">
        <div class="flex justify-center mb-4">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="AutoCost" class="h-16 w-auto">
            </a>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Восстановление пароля</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Введите ваш email, и мы отправим ссылку для сброса пароля
        </p>
    </div>

    @if(session('status'))
        <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-3 rounded-lg mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition">
            Отправить ссылку для сброса
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                Вспомнили пароль? Войти
            </a>
        </div>
    </form>
</x-guest-layout>