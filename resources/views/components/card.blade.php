@props(['title' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-lg border border-gray-200 dark:border-slate-700']) }}>
    @if($title || $icon)
    <div class="border-b border-gray-200 dark:border-slate-700 px-6 py-4 flex items-center">
        @if($icon)
        <div class="mr-2 text-blue-600 dark:text-blue-400">
            {!! $icon !!}
        </div>
        @endif
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            {{ $title }}
        </h3>
    </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
