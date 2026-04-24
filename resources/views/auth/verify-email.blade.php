<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Подтверждение email') }}</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Спасибо за регистрацию! Перед началом работы подтвердите ваш адрес электронной почты, нажав на ссылку, которую мы отправили вам. Если вы не получили письмо, мы с удовольствием отправим вам новое.') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300">
            {{ __('Новая ссылка подтверждения отправлена на адрес электронной почты, указанный вами при регистрации.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row items-center gap-4 justify-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition shadow-md">
                {{ __('Отправить ссылку подтверждения') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full sm:w-auto text-center underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 rounded-md transition">
                {{ __('Выйти') }}
            </button>
        </form>
    </div>
</x-guest-layout>
