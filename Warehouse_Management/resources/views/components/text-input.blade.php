@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full px-3 py-2 border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 rounded-lg shadow-sm transition duration-300 ease-in-out']) }}>
