@props(['align' => 'left'])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ][$align] ?? 'text-left';
@endphp

<th scope="col" {{ $attributes->merge(['class' => "px-4 py-3 {$alignmentClasses}"]) }}>
    {{ $slot }}
</th>
