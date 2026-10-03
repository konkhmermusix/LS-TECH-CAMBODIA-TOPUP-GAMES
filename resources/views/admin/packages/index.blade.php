<x-admin-layout title="Package Management">
    <x-slot name="header">
        Top-Up Packages
    </x-slot>

    <!-- Top Controls: Filter by Game & Add Package Button -->
    <div class="glass-card rounded-3xl p-5 mb-6 border border-white/40 dark:border-white/10 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.packages.index') }}" class="flex items-center gap-3">
            <x-select name="game_id" class="w-64" onchange="this.form.submit()">
                <option value="">All Games</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }}
                    </option>
                @endforeach
            </x-select>

            @if(request('game_id'))
                <a href="{{ route('admin.packages.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white">
                    Clear filter
                </a>
            @endif
        </form>

        <a href="{{ route('admin.packages.create', ['game_id' => request('game_id')]) }}">
            <x-button variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Package</span>
            </x-button>
        </a>
    </div>

    <!-- Packages Table -->
    @if($packages->count() > 0)
        <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-5 py-3.5">Game</th>
                            <th class="px-5 py-3.5">Package Name</th>
                            <th class="px-5 py-3.5">Diamonds / Amount</th>
                            <th class="px-5 py-3.5">Price (KHR)</th>
                            <th class="px-5 py-3.5">Price (USD)</th>
                            <th class="px-5 py-3.5">Badge</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($packages as $pkg)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ $pkg->game->name }}
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-blue-600 dark:text-blue-400">
                                    {{ $pkg->name }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs">
                                    {{ number_format($pkg->amount_value) }}
                                    @if($pkg->bonus_value > 0)
                                        <span class="text-emerald-500 font-bold">(+{{ $pkg->bonus_value }} bonus)</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ number_format($pkg->price_khr) }}៛
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    ${{ number_format($pkg->price_usd, 2) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($pkg->badge)
                                        <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                            {{ $pkg->badge }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($pkg->is_active)
                                        <x-badge color="emerald" :dot="true">Active</x-badge>
                                    @else
                                        <x-badge color="gray" :dot="true">Inactive</x-badge>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="px-3 py-1 rounded-xl glass-card text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-blue-500 transition-colors inline-block">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $packages->links() }}
        </div>
    @else
        <x-empty-state title="No packages found" description="Create packages for your games to allow customer top-ups." />
    @endif
</x-admin-layout>
