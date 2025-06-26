@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-blue-500 dark:border-blue-400 text-sm font-medium leading-5 text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-700 transition duration-150 ease-in-out cursor-pointer'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-700 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition duration-150 ease-in-out cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} style="pointer-events: auto;">
    {{ $slot }}
</a>
