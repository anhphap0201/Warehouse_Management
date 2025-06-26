@props(['highlight' => false])

@php
    $rowClass = $highlight ? 'bg-gray-50' : 'bg-white';
@endphp

<tr {{ $attributes->merge(['class' => "{$rowClass} border-b border-gray-200 hover:bg-gray-50"]) }}>
    {{ $slot }}
</tr>
