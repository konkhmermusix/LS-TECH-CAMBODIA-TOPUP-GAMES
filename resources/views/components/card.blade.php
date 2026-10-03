@props([
    'header' => null,
    'footer' => null,
    'padding' => 'p-6',
    'hover' => true,
])

<div {{ $attributes->merge([
    'class' => "glass-card rounded-3xl overflow-hidden transition-all duration-300 " .
        ($hover ? 'hover:-translate-y-1 ' : '')
]) }}>
    @if($header)
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/10 flex items-center justify-between">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/10 bg-gray-50/50 dark:bg-slate-900/40">
            {{ $footer }}
        </div>
    @endif
</div>
