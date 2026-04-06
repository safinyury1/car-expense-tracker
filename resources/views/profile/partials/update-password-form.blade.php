<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Смена пароля') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Пароль должен быть не менее 8 символов.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <x-input-label for="current_password" :value="__('Текущий пароль')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Новый пароль')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Подтвердите пароль')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-600">{{ __('Пароль обновлён!') }}</p>
            @endif
        </div>
    </form>
</section>