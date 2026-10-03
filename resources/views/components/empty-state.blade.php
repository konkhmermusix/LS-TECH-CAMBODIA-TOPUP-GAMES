@props([
    'title' => 'No items found',
    'description' => 'There are no records to display at this moment.',
    'icon' => null,
    'action' => null,
])

<div class="flex flex-col items-center justify-center p-8 sm:p-12 text-center rounded-3xl glass-card border border-dashed border-gray-300 dark:border-white/10">
    <div class="w-16 h-16 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center mb-4">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        @endif
    </div>
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ $title }}</h3>
    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-6">{{ $description }}</p>

    @if($action)
        <div>
            {{ $action }}
        </div>
    @endif
</div>
