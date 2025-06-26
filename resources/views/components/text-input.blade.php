@props(['disabled' => false, 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'px-2 py-1.5 text-sm',
        'md' => 'px-3 py-2',
        'lg' => 'px-4 py-3 text-lg',
    ][$size] ?? 'px-3 py-2';
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => "block w-full {$sizeClasses} border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-300 ease-in-out touch-target"]) }}>
