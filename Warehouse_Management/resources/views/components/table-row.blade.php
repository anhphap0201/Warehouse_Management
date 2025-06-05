@props(['highlight' => false])

@php
    $rowClass = $highlight ? 'bg-gray-50 dark:bg-slate-700' : 'bg-white dark:bg-slate-800';
@endphp

<tr {{ $attributes->merge(['class' => "{$rowClass} border-b border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/70"]) }}>
    {{ $slot }}
</tr>
