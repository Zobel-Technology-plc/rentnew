@props(['title', 'value', 'icon', 'color'])

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
    <div class="flex items-center">
        <div class="p-3 rounded-full bg-{{ $color }}-500 bg-opacity-10">
            <svg class="w-6 h-6 text-{{ $color }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div class="ml-4">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h2>
            <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($value) }}</p>
        </div>
    </div>
</div> 