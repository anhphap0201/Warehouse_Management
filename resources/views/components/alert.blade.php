@props([
    'type' => 'info',
    'dismissible' => false
])

@php
$types = [
    'info' => 'bg-blue-50 border-blue-500 text-blue-700 dark:bg-blue-900/40 dark:border-blue-500 dark:text-blue-300',
    'success' => 'bg-green-50 border-green-500 text-green-700 dark:bg-green-900/40 dark:border-green-500 dark:text-green-300',
    'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-700 dark:bg-yellow-900/40 dark:border-yellow-500 dark:text-yellow-300',
    'error' => 'bg-red-50 border-red-500 text-red-700 dark:bg-red-900/40 dark:border-red-500 dark:text-red-300',
];

$icons = [
    'info' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>',
    'success' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>',
    'warning' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>',
    'error' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>',
];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="border-l-4 p-4 mb-4 rounded-r {{ $types[$type] }}"
    role="alert"
>
    <div class="flex items-center">
        <div class="flex-shrink-0 mr-3">
            {!! $icons[$type] !!}
        </div>
        <div class="flex-grow">
            {{ $slot }}
        </div>
        @if($dismissible)
        <div class="flex-shrink-0 ml-3">
            <button @click="show = false" type="button" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif
    </div>
</div>
