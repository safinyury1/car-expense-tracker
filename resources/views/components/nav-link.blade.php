@props(['active' => false])

@php
$classes = ($active ? 'nav-link-active' : '') . ' nav-link inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 focus:outline-none focus:ring-0 focus:ring-offset-0 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>