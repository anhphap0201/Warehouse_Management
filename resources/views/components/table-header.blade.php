@props(['align' => 'left', 'mobile' => true, 'sortable' => false])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ][$align] ?? 'text-left';
    
    $responsiveClasses = $mobile ? '' : 'table-col-mobile-hidden';
    $sortableClasses = $sortable ? 'cursor-pointer hover:bg-gray-200 transition-colors' : '';
@endphp

<th scope="col" {{ $attributes->merge(['class' => "px-3 py-3 sm:px-4 sm:py-3 {$alignmentClasses} {$responsiveClasses} {$sortableClasses} text-xs font-medium text-gray-500 uppercase tracking-wider"]) }}>
    @if($sortable)
        <div class="flex items-center space-x-1">
            <span>{{ $slot }}</span>
            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
        </div>
    @else
        {{ $slot }}
    @endif
</th>
