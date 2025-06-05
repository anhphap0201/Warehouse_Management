@props(['align' => 'left', 'highlight' => false])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ][$align] ?? 'text-left';

    $baseClasses = "px-4 py-3 {$alignmentClasses}";
    
    $classes = $highlight 
        ? "{$baseClasses} bg-gray-50 dark:bg-slate-700"
        : $baseClasses;
@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>
