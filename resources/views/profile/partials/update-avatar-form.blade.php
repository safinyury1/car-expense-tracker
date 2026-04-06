<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Аватар профиля') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Загрузите изображение для аватара. Поддерживаются JPEG, PNG, GIF (максимум 2 МБ).') }}
        </p>
    </header>

    <div class="mt-6 flex items-center gap-6">
        <!-- Текущий аватар -->
        <div class="shrink-0">
            @if(Auth::user()->avatar)
                <img class="h-20 w-20 rounded-full object-cover" 
                     src="{{ Storage::url(Auth::user()->avatar) }}" 
                     alt="{{ Auth::user()->name }}">
            @else
                <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-2xl">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
        </div>

        <!-- Форма загрузки аватара -->
        <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="flex items-center gap-4">
            @csrf
            @method('PATCH')
            
            <input type="file" name="avatar" id="avatar" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*" required>
            
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Загрузить') }}
            </button>
        </form>

        <!-- Кнопка удаления аватара -->
        @if(Auth::user()->avatar)
            <form method="POST" action="{{ route('profile.avatar.delete') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Вы уверены, что хотите удалить аватар?')">
                    {{ __('Удалить') }}
                </button>
            </form>
        @endif
    </div>

    @if(session('status') === 'avatar-updated')
        <p class="mt-2 text-sm text-green-600">{{ __('Аватар успешно обновлён!') }}</p>
    @endif
    
    @if(session('status') === 'avatar-deleted')
        <p class="mt-2 text-sm text-green-600">{{ __('Аватар удалён!') }}</p>
    @endif

    @error('avatar')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</section>