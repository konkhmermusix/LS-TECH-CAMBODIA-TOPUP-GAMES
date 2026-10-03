@props([
    'disabled' => false,
    'error' => null,
    'label' => null,
    'help' => null,
    'icon' => null,
    'id' => null,
    'required' => false,
])

@php
$id = $id ?? 'input-' . \Illuminate\Support\Str::random(6);
$errorClass = $error ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900 dark:text-rose-100' : 'border-gray-200 dark:border-white/10 focus:border-blue-500 focus:ring-blue-500/20';
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-2xl">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                {!! $icon !!}
            </div>
        @endif

        <input
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => "w-full rounded-2xl text-sm transition-all duration-200 placeholder:text-gray-400 dark:placeholder:text-gray-500 " .
                    ($icon ? 'pl-10 ' : 'pl-4 ') .
                    "pr-4 py-2.5 glass-input " .
                    $errorClass .
                    ($disabled ? ' opacity-50 cursor-not-allowed bg-gray-100 dark:bg-gray-800' : '')
            ]) }}
        >
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
            <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $error }}
        </p>
    @elseif($help)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>
