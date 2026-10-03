@props([
    'disabled' => false,
    'error' => null,
    'label' => null,
    'help' => null,
    'id' => null,
    'required' => false,
])

@php
$id = $id ?? 'select-' . \Illuminate\Support\Str::random(6);
$errorClass = $error ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/20' : 'border-gray-200 dark:border-white/10 focus:border-blue-500 focus:ring-blue-500/20';
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
        <select
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => "w-full appearance-none rounded-2xl text-sm transition-all duration-200 px-4 py-2.5 glass-input pr-10 cursor-pointer " .
                    $errorClass .
                    ($disabled ? ' opacity-50 cursor-not-allowed' : '')
            ]) }}
        >
            {{ $slot }}
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-400">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $error }}</p>
    @elseif($help)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>
