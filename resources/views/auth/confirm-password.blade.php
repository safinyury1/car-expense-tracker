<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Подтверждение пароля') }}</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Это защищённая область приложения. Пожалуйста, подтвердите ваш пароль для продолжения.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Пароль')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-[#6B727F] dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-md">
            {{ __('Подтвердить') }}
        </button>
    </form>
</x-guest-layout>
