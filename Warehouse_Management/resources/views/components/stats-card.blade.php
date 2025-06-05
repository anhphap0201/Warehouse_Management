@props([
    'title', 
    'value', 
    'icon', 
    'color' => 'blue',
    'change' => null,
    'isUp' => true
])

@php
    $colorClasses = [
        'blue' => 'from-blue-500 to-blue-600',
        'green' => 'from-green-500 to-green-600',
        'red' => 'from-red-500 to-red-600',
        'yellow' => 'from-yellow-500 to-yellow-600',
        'purple' => 'from-purple-500 to-purple-600',
        'pink' => 'from-pink-500 to-pink-600',
        'indigo' => 'from-indigo-500 to-indigo-600',
        'orange' => 'from-orange-500 to-orange-600',
    ][$color] ?? 'from-blue-500 to-blue-600';
@endphp

<div class="bg-gradient-to-br {{ $colorClasses }} rounded-lg shadow-lg overflow-hidden text-white">
    <div class="px-4 py-5 sm:p-6 flex justify-between items-center">
        <div>
            <dt class="text-sm font-medium truncate">
                {{ $title }}
            </dt>
            <dd class="mt-1 text-3xl font-semibold">
                {{ $value }}
            </dd>
            @if($change !== null)
            <p class="mt-2 text-sm flex items-center">
                @if($isUp)
                <span class="flex items-center text-white text-opacity-80">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    {{ $change }}%
                </span>
                @else
                <span class="flex items-center text-white text-opacity-80">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    {{ $change }}%
                </span>
                @endif
                <span class="ml-1 text-white text-opacity-70">from last period</span>
            </p>
            @endif
        </div>
        <div class="rounded-full bg-white bg-opacity-20 p-3">
            {!! $icon !!}
        </div>
    </div>
</div>
