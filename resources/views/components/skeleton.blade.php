@props([
    'height' => 'h-4',
    'width' => 'w-full',
    'rounded' => 'rounded-xl',
])

<div {{ $attributes->merge([
    'class' => "animate-pulse bg-gray-200/80 dark:bg-slate-700/60 {$height} {$width} {$rounded}"
]) }}></div>
