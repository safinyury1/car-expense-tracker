@props(['active' => false])

@php
$classes = ($active ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300') . ' dropdown-item block w-full px-4 py-2 text-left text-sm leading-5 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>