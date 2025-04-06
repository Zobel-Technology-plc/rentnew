@props(['icon', 'label', 'route', 'active'])

<a href="{{ route($route) }}" 
   class="flex items-center px-4 py-2 text-gray-600 dark:text-gray-300 rounded-lg transition-colors duration-200 
          {{ $active ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 mr-3"/>
    <span>{{ $label }}</span>
</a> 