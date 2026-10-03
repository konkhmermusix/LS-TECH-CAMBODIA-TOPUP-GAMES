@props([
    'headers' => [],
])

<div class="w-full overflow-hidden rounded-3xl glass-card border border-gray-200/60 dark:border-white/10 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            @if(count($headers) > 0)
                <thead>
                    <tr class="border-b border-gray-200/60 dark:border-white/10 bg-gray-50/50 dark:bg-slate-900/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        @foreach($headers as $header)
                            <th scope="col" class="px-6 py-4">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-gray-700 dark:text-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
