@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-gray-200 dark:border-slate-700 overflow-hidden mb-6']) }}>
    @if($title || $description)
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>
    @endif
    <div class="px-4 py-5 sm:p-6">
        {{ $slot }}
    </div>
</div>
