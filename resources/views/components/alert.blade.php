@props([
    'type' => 'info',
    'dismissible' => false,
])

@php
$config = match($type) {
    'success' => [
        'bg' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-800 dark:text-emerald-200',
        'icon' => '<svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
    ],
    'error', 'danger' => [
        'bg' => 'bg-rose-500/10 border-rose-500/20 text-rose-800 dark:text-rose-200',
        'icon' => '<svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    ],
    'warning' => [
        'bg' => 'bg-amber-500/10 border-amber-500/20 text-amber-800 dark:text-amber-200',
        'icon' => '<svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
    ],
    default => [
        'bg' => 'bg-blue-500/10 border-blue-500/20 text-blue-800 dark:text-blue-200',
        'icon' => '<svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    ],
};
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    class="flex items-start gap-3 p-4 rounded-2xl border backdrop-blur-md {{ $config['bg'] }} text-sm"
>
    <div>{!! $config['icon'] !!}</div>
    <div class="flex-1 font-medium">
        {{ $slot }}
    </div>
    @if($dismissible)
        <button type="button" @click="show = false" class="text-current opacity-60 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    @endif
</div>
