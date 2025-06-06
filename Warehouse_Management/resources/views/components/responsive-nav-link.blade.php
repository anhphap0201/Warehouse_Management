@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-3 border-l-4 border-blue-500 dark:border-blue-400 text-start text-base font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 focus:outline-none focus:text-blue-800 dark:focus:text-blue-300 focus:bg-blue-100 dark:focus:bg-blue-900/50 transition duration-150 ease-in-out cursor-pointer'
            : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 hover:border-blue-300 dark:hover:border-blue-600 focus:outline-none transition duration-150 ease-in-out cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} style="pointer-events: auto;">
    {{ $slot }}
</a>
