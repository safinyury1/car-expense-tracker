<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Восстановление пароля</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Введите ваш email, и мы отправим ссылку для сброса пароля
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-md">
            Отправить ссылку для сброса
        </button>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Вспомнили пароль?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition">
                    Вернуться ко входу
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>