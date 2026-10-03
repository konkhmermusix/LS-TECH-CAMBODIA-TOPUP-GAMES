@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-2xl transition-all duration-200 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none disabled:active:scale-100 select-none cursor-pointer';

$sizeClasses = match($size) {
    'xs' => 'px-2.5 py-1 text-xs gap-1',
    'sm' => 'px-3.5 py-1.5 text-xs gap-1.5',
    'md' => 'px-4 py-2.5 text-sm gap-2',
    'lg' => 'px-6 py-3 text-base gap-2.5',
    'xl' => 'px-8 py-3.5 text-lg gap-3',
    'icon' => 'p-2.5 text-sm',
    default => 'px-4 py-2.5 text-sm gap-2',
};

$variantClasses = match($variant) {
    'primary' => 'bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/25 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400',
    'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-800 focus:ring-gray-400 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-gray-100',
    'glass' => 'glass-panel hover:bg-white/80 text-gray-800 dark:text-white dark:hover:bg-slate-800/80 focus:ring-blue-500 border border-white/40 dark:border-white/10 shadow-sm',
    'ghost' => 'bg-transparent hover:bg-black/5 text-gray-700 dark:text-gray-300 dark:hover:bg-white/5 focus:ring-gray-400',
    'success' => 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-500/25 focus:ring-emerald-500',
    'danger' => 'bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-500/25 focus:ring-rose-500',
    default => 'bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/25 focus:ring-blue-500',
};
@endphp

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $variantClasses"]) }}
>
    @if($loading)
        <svg class="animate-spin -ml-0.5 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon)
        <span>{!! $icon !!}</span>
    @endif

    {{ $slot }}
</button>
