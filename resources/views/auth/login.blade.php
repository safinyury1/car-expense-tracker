<x-guest-layout>
    <div class="text-center mb-6">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="AutoCost" class="h-20 w-auto">
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Вход в аккаунт</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Введите свои данные для входа</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Пароль')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Forgot Password Link -->
        <div class="flex justify-end mb-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" href="{{ route('password.request') }}">
                    {{ __('Забыли пароль?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-md">
            Войти
        </button>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Нет аккаунта?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition">
                    Зарегистрироваться
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>