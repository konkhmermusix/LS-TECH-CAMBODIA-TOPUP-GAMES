<x-admin-layout title="Game Management">
    <x-slot name="header">
        Game Catalog
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xs text-slate-500 dark:text-slate-400">Configure supported games, input validation schemas, and guidelines.</p>
        <a href="{{ route('admin.games.create') }}">
            <x-button variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Game</span>
            </x-button>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($games as $game)
            <div class="glass-card rounded-3xl p-6 border border-white/40 dark:border-white/10 relative overflow-hidden">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-800 shrink-0 shadow-md">
                        <img src="{{ $game->logo }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">{{ $game->name }}</h3>
                            @if($game->is_active)
                                <x-badge color="emerald" :dot="true">Active</x-badge>
                            @else
                                <x-badge color="gray" :dot="true">Inactive</x-badge>
                            @endif
                        </div>
                        <p class="text-xs font-semibold text-slate-400 mt-0.5">Publisher: {{ $game->publisher }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2">{{ $game->description }}</p>

                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/5 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-4 text-slate-500">
                                <span><strong>{{ $game->packages_count }}</strong> Packages</span>
                                <span><strong>{{ $game->orders_count }}</strong> Orders</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.packages.index', ['game_id' => $game->id]) }}" class="px-2.5 py-1 rounded-xl glass-card text-blue-600 dark:text-blue-400 font-semibold hover:scale-105 transition-transform">
                                    Packages
                                </a>
                                <a href="{{ route('admin.games.edit', $game->id) }}" class="px-2.5 py-1 rounded-xl glass-card text-slate-700 dark:text-slate-200 font-semibold hover:scale-105 transition-transform">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
