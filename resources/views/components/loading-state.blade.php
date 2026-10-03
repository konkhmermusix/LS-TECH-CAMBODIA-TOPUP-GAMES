@props([
    'text' => 'Loading data...',
])

<div class="flex flex-col items-center justify-center p-12 text-center">
    <x-spinner size="lg" color="text-blue-500" />
    <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400 animate-pulse">{{ $text }}</p>
</div>
