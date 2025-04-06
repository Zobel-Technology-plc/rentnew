@props(['title', 'value', 'icon', 'color'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center">
        <div class="p-3 rounded-lg bg-{{ $color }}-50 dark:bg-{{ $color }}-900/50">
            <x-dynamic-component 
                :component="'heroicon-o-' . $icon"
                class="w-6 h-6 text-{{ $color }}-500 dark:text-{{ $color }}-400"/>
        </div>
        <div class="ml-5">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h3>
            <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($value) }}</p>
        </div>
    </div>
</div> 