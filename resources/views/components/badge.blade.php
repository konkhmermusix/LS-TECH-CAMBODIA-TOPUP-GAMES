@props([
    'color' => 'blue',
    'dot' => false,
    'size' => 'sm',
])

@php
$colorClasses = match($color) {
    'emerald', 'green', 'success' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
    'amber', 'yellow', 'warning' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
    'rose', 'red', 'danger' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
    'blue', 'info' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20',
    'purple' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20',
    default => 'bg-gray-500/10 text-gray-600 dark:text-gray-400 border-gray-500/20',
};

$dotClasses = match($color) {
    'emerald', 'green', 'success' => 'bg-emerald-500',
    'amber', 'yellow', 'warning' => 'bg-amber-500',
    'rose', 'red', 'danger' => 'bg-rose-500',
    'blue', 'info' => 'bg-blue-500',
    'purple' => 'bg-purple-500',
    default => 'bg-gray-400',
};

$sizeClasses = match($size) {
    'xs' => 'px-2 py-0.5 text-[10px]',
    'sm' => 'px-2.5 py-1 text-xs',
    'md' => 'px-3 py-1.5 text-sm',
    default => 'px-2.5 py-1 text-xs',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 font-semibold rounded-full border backdrop-blur-sm $colorClasses $sizeClasses"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>
    @endif
    {{ $slot }}
</span>
