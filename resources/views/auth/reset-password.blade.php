<x-guest-layout>
    <div class="text-center mb-6">
        <div class="flex justify-center mb-4">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="AutoCost" class="h-16 w-auto">
            </a>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Сброс пароля</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Введите новый пароль</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Новый пароль</label>
            <input id="password" type="password" name="password" required
                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Подтвердите пароль</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-[#4B5563] dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition">
            Сбросить пароль
        </button>
    </form>
</x-guest-layout>