<x-app-layout title="Fast Game Top-Up in Cambodia — Free Fire & Mobile Legends">

    <!-- Hero Section -->
    <section class="relative pt-6 pb-12 sm:pt-12 sm:pb-20 overflow-hidden">
        <div class="text-center max-w-3xl mx-auto space-y-6">
          
            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 dark:text-white leading-[1.15]">
                Level Up Your Game with <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 dark:from-blue-400 dark:via-indigo-300 dark:to-cyan-300 bg-clip-text text-transparent">
                    Instant Diamonds
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-xl mx-auto ">
                Cambodia's trusted destination for Free Fire & Mobile Legends top-up. <br class="hidden sm:inline"/>
                Scan with ABA Mobile and receive your diamonds in seconds.
            </p>

            <!-- Quick Action CTA -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <a href="#games">
                    <x-button variant="primary" size="lg" class="shadow-xl shadow-blue-500/25">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Top Up Now</span>
                    </x-button>
                </a>
                <a href="{{ route('orders.track') }}">
                    <x-button variant="glass" size="lg">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Track My Order</span>
                    </x-button>
                </a>
            </div>

            <!-- Trust Badges -->
            <div class="pt-8 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs font-semibold text-slate-500 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Instant Automated Delivery</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Official KHQR Payment</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Zero Login Required</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof Live Ticker (Recent Top-Ups) -->
    @if(count($recentDeliveries) > 0)
        <div class="mb-12 glass-card rounded-2xl p-3 sm:p-4 border border-white/40 dark:border-white/10 overflow-hidden">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold text-xs shrink-0">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Live Top-Ups</span>
                </span>

                <div class="flex-1 overflow-x-auto no-scrollbar flex items-center gap-4 text-xs whitespace-nowrap">
                    @foreach($recentDeliveries as $item)
                        <div class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $item['game'] }}</span>
                            <span class="text-blue-500 font-semibold">{{ $item['package'] }}</span>
                            <span class="text-slate-400 font-mono">{{ $item['player'] }}</span>
                            <span class="text-[10px] text-slate-400">&bull; {{ $item['time'] }}</span>
                        </div>
                        <span class="text-slate-300 dark:text-slate-700">/</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Featured Games Section -->
    <section id="games" class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Supported Games</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Select your favorite game to begin instant top-up</p>
            </div>

            <a href="{{ route('games.index') }}" class="text-xs sm:text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline">
                Browse Catalog &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
            @foreach($games as $game)
                <a href="{{ route('topup.show', $game->slug) }}" class="group block focus:outline-none">
                    <div class="glass-card rounded-3xl overflow-hidden border border-white/50 dark:border-white/10 group-hover:border-blue-500/40 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-blue-500/10">
                        <!-- Banner Image -->
                        <div class="relative h-44 sm:h-52 w-full overflow-hidden bg-slate-900">
                            <img
                                src="{{ $game->banner ?? $game->logo }}"
                                alt="{{ $game->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>

                            <!-- Publisher Tag Top Left -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-xl glass-panel text-[11px] font-bold text-white uppercase tracking-wider backdrop-blur-md">
                                    {{ $game->publisher }}
                                </span>
                            </div>

                            <!-- Instant Tag Top Right -->
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/90 text-[11px] font-bold text-white shadow-lg shadow-emerald-500/30 flex items-center gap-1.5 backdrop-blur-md">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span>Instant</span>
                                </span>
                            </div>

                            <!-- Title on Image Bottom -->
                            <div class="absolute bottom-4 left-4 right-4 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white/80 shadow-md shrink-0">
                                    <img src="{{ $game->logo }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-white tracking-tight drop-shadow-sm">{{ $game->name }}</h3>
                                    <p class="text-xs text-slate-300">Fast ABA KHQR Delivery</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 line-clamp-2 mb-4 leading-relaxed">
                                {{ $game->description }}
                            </p>

                            <!-- Popular packages sample preview -->
                            <div class="flex flex-wrap gap-2 mb-5">
                                @foreach($game->activePackages->take(3) as $pkg)
                                    <span class="px-2.5 py-1 rounded-xl bg-black/5 dark:bg-white/5 text-[11px] font-bold text-slate-700 dark:text-slate-300 border border-white/20 dark:border-white/5">
                                        {{ $pkg->name }} &bull; {{ $pkg->formatted_price_khr }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- CTA Button inside Card -->
                            <div class="flex items-center justify-between pt-2">
                                <span class="text-xs font-bold text-slate-400">
                                    {{ $game->activePackages->count() }} Diamond Packages
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
    </section>

    <!-- Promotions Carousel / Banner Section -->
    @if($promotions->count() > 0)
        <section class="mb-16">
            <div class="mb-6">
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Active Promotions & Vouchers</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Claim exclusive discount codes on your game top-ups</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($promotions as $promo)
                    <div class="glass-card rounded-3xl p-6 border border-white/40 dark:border-white/10 relative overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-extrabold text-xs border border-blue-500/20">
                                    Special Offer
                                </span>
                                @if($promo->code)
                                    <span class="font-mono font-bold text-xs text-slate-500 bg-black/5 dark:bg-white/5 px-2.5 py-1 rounded-lg">
                                        CODE: <span class="text-blue-600 dark:text-blue-400">{{ $promo->code }}</span>
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">{{ $promo->title }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Minimum spend of {{ number_format($promo->min_spend) }}៛. Apply code during checkout.
                            </p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/5 flex items-center justify-between">
                            <span class="text-xs font-semibold text-emerald-500">
                                @if($promo->discount_type === 'percentage')
                                    Save {{ $promo->discount_value }}% Off
                                @else
                                    Save {{ number_format($promo->discount_value) }}៛ Off
                                @endif
                            </span>
                            <a href="#games" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                Use Promo &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- How It Works (4 Steps) -->
    <section class="mb-16">
        <div class="text-center max-w-xl mx-auto mb-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">How It Works</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Fast 4-step guest checkout with zero registration</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Step 1 -->
            <div class="glass-card rounded-3xl p-6 text-center border border-white/40 dark:border-white/10 relative">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 font-black text-lg flex items-center justify-center mx-auto mb-4">
                    1
                </div>
                <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1">Select Game</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Choose Free Fire or Mobile Legends Bang Bang from our catalog.</p>
            </div>

            <!-- Step 2 -->
            <div class="glass-card rounded-3xl p-6 text-center border border-white/40 dark:border-white/10 relative">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 font-black text-lg flex items-center justify-center mx-auto mb-4">
                    2
                </div>
                <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1">Enter Player ID</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Provide your Free Fire UID or MLBB User ID & Zone ID.</p>
            </div>

            <!-- Step 3 -->
            <div class="glass-card rounded-3xl p-6 text-center border border-white/40 dark:border-white/10 relative">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 font-black text-lg flex items-center justify-center mx-auto mb-4">
                    3
                </div>
                <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1">Scan ABA KHQR</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Scan with ABA Mobile or any Bakong banking app in Cambodia.</p>
            </div>

            <!-- Step 4 -->
            <div class="glass-card rounded-3xl p-6 text-center border border-white/40 dark:border-white/10 relative">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-500 font-black text-lg flex items-center justify-center mx-auto mb-4">
                    4
                </div>
                <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1">Instant Delivery</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Diamonds credited automatically to your game character within seconds.</p>
            </div>
        </div>
    </section>

</x-app-layout>
