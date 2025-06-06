{{-- Structure for a consistent table design --}}
<div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-slate-700">
    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700 dark:text-gray-300">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
    
    @isset($footer)
    <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-gray-200 dark:border-slate-700">
        {{ $footer }}
    </div>
    @endisset
</div>
