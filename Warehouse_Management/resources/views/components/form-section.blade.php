@props(['title' => null, 'description' => null, 'grid' => false])

@php
    $containerClasses = $grid 
        ? 'bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-gray-200 dark:border-slate-700 overflow-hidden mb-4 sm:mb-6'
        : 'bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-gray-200 dark:border-slate-700 overflow-hidden mb-4 sm:mb-6';
@endphp

<div {{ $attributes->merge(['class' => $containerClasses]) }}>
    @if($title || $description)
    <div class="px-3 py-4 sm:px-4 sm:py-5 lg:px-6 border-b border-gray-200 dark:border-slate-700">
        <h3 class="text-base sm:text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>
    @endif
    <div class="px-3 py-4 sm:px-4 sm:py-5 lg:p-6">
        {{ $slot }}
    </div>
</div>
