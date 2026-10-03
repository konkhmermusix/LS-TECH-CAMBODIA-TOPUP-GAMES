<x-app-layout title="All Supported Games — Fast Top-Up Cambodia">
    <div class="max-w-5xl mx-auto py-6">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Game Catalog</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-md mx-auto">
                Select your game to top up diamonds instantly with verified ABA KHQR payment in Cambodia.
            </p>

            <!-- Search Bar -->
            <div class="max-w-md mx-auto mt-6">
                <form method="GET" action="{{ route('games.index') }}">
                    <x-input
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Free Fire, Mobile Legends..."
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                    />
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($games as $game)
                <a href="{{ route('topup.show', $game->slug) }}" class="group block focus:outline-none">
                    <div class="glass-card rounded-3xl overflow-hidden border border-white/50 dark:border-white/10 group-hover:border-blue-500/40 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-blue-500/10">
                        <div class="relative h-48 w-full overflow-hidden bg-slate-900">
                            <img
                                src="{{ $game->banner ?? $game->logo }}"
                                alt="{{ $game->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>

                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-xl glass-panel text-[11px] font-bold text-white uppercase tracking-wider backdrop-blur-md">
                                    {{ $game->publisher }}
                                </span>
                            </div>

                            <div class="absolute bottom-4 left-4 right-4 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white/80 shadow-md shrink-0">
                                    <img src="{{ $game->logo }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-white tracking-tight drop-shadow-sm">{{ $game->name }}</h3>
                                    <p class="text-xs text-slate-300">Automated ABA KHQR Delivery</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 line-clamp-2 mb-4 leading-relaxed">
                                {{ $game->description }}
                            </p>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-white/5">
                                <span class="text-xs font-bold text-slate-400">
                                    {{ $game->activePackages->count() }} Diamond Packages Available
                                </span>
                                <span class="inline-flex items-center gap-1 text-sm font-bold text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform">
                                    <span>Top Up Now</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
