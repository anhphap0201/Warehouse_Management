@props(['align' => 'left', 'highlight' => false, 'mobile' => true])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ][$align] ?? 'text-left';

    $baseClasses = "px-3 py-3 sm:px-4 sm:py-4 {$alignmentClasses} text-sm text-gray-900";
    $responsiveClasses = $mobile ? '' : 'table-col-mobile-hidden';
    
    $classes = $highlight 
        ? "{$baseClasses} {$responsiveClasses} bg-gray-50"
        : "{$baseClasses} {$responsiveClasses}";
@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>
