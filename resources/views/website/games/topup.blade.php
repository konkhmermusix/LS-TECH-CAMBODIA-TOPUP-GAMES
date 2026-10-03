<x-app-layout :title="'Top Up ' . $game->name . ' Diamonds — Fast Delivery'">

    <!-- Alpine Checkout State Component -->
    <div
        x-data="{
            gameId: {{ $game->id }},
            playerId: '',
            zoneId: '',
            selectedPackage: null,
            packageId: null,
            packageName: '',
            packagePriceKhr: 0,
            packagePriceUsd: 0,
            selectedPayment: 'aba_khqr',
            promoCode: '',
            discountAmount: 0,
            discountMessage: '',
            isVerifyingPlayer: false,
            playerVerified: false,
            playerNickname: '',
            playerError: '',
            isSubmitting: false,
            checkoutError: '',

            selectPackage(pkg) {
                this.selectedPackage = pkg;
                this.packageId = pkg.id;
                this.packageName = pkg.name;
                this.packagePriceKhr = parseFloat(pkg.price_khr);
                this.packagePriceUsd = parseFloat(pkg.price_usd);
            },

            get finalTotalKhr() {
                const total = Math.max(0, this.packagePriceKhr - this.discountAmount);
                return total;
            },

            get formattedTotalKhr() {
                return new Intl.NumberFormat('en-US').format(this.finalTotalKhr) + '៛';
            },

            async verifyPlayer() {
                if (!this.playerId) {
                    this.playerError = 'Please enter your Player ID.';
                    return;
                }
                @if($game->slug === 'mobile-legends')
                if (!this.zoneId) {
                    this.playerError = 'Please enter your Zone ID.';
                    return;
                }
                @endif

                this.isVerifyingPlayer = true;
                this.playerError = '';
                this.playerVerified = false;

                try {
                    const response = await fetch('{{ route('api.player.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            game_id: this.gameId,
                            player_id: this.playerId,
                            zone_id: this.zoneId
                        })
                    });

                    const data = await response.json();
                    if (response.ok && data.valid) {
                        this.playerVerified = true;
                        this.playerNickname = data.nickname || 'Verified Gamer';
                        $store.toast.success('Player ID verified: ' + this.playerNickname);
                    } else {
                        this.playerError = data.message || 'Invalid Player ID format.';
                        $store.toast.error(this.playerError);
                    }
                } catch (e) {
                    this.playerError = 'Connection error verifying Player ID.';
                } finally {
                    this.isVerifyingPlayer = false;
                }
            },

            async submitCheckout() {
                if (!this.playerId) {
                    $store.toast.error('Please enter your Player ID.');
                    document.getElementById('player_id_input')?.focus();
                    return;
                }
                @if($game->slug === 'mobile-legends')
                if (!this.zoneId) {
                    $store.toast.error('Please enter your Zone ID.');
                    document.getElementById('zone_id_input')?.focus();
                    return;
                }
                @endif
                if (!this.packageId) {
                    $store.toast.error('Please select a diamond package.');
                    window.location.hash = '#packages-step';
                    return;
                }

                this.isSubmitting = true;
                this.checkoutError = '';

                try {
                    const response = await fetch('{{ route('checkout.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            game_id: this.gameId,
                            package_id: this.packageId,
                            player_id: this.playerId,
                            zone_id: this.zoneId,
                            player_nickname: this.playerNickname,
                            promo_code: this.promoCode
                        })
                    });

                    const result = await response.json();
                    if (response.ok && result.success) {
                        window.location.href = result.redirect_url;
                    } else {
                        this.checkoutError = result.message || 'Failed to initialize checkout.';
                        $store.toast.error(this.checkoutError);
                        this.isSubmitting = false;
                    }
                } catch (err) {
                    this.checkoutError = 'Network error while initiating payment.';
                    $store.toast.error(this.checkoutError);
                    this.isSubmitting = false;
                }
            }
        }"
        class="max-w-4xl mx-auto pb-28 md:pb-16"
    >
        <!-- Game Header Card -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 mb-8 border border-white/50 dark:border-white/10 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl overflow-hidden bg-slate-900 border-2 border-white/80 dark:border-white/10 shadow-xl shrink-0">
                    <img src="{{ $game->logo }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                </div>

                <div class="flex-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-between gap-2 mb-2">
                        <span class="px-3 py-1 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 font-bold text-xs uppercase tracking-wider border border-blue-500/20">
                            {{ $game->publisher }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-500">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <span>ABA KHQR Instant Delivery</span>
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ $game->name }} Top-Up
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-2 leading-relaxed">
                        {{ $game->description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Multi-Step Top-Up Container -->
        <div class="space-y-8">

            <!-- STEP 1: Enter Player Information -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold text-sm flex items-center justify-center shadow-md shadow-blue-500/30">
                            1
                        </span>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Player Information</h2>
                    </div>

                    <button
                        type="button"
                        x-on:click="$dispatch('open-modal', 'guide-modal')"
                        class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Where is my ID?</span>
                    </button>
                </div>

                <!-- Input Fields dynamically loaded for the game -->
                @if($game->slug === 'free-fire')
                    <div class="space-y-3">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Free Fire Player UID <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-1">
                                <input
                                    id="player_id_input"
                                    type="text"
                                    x-model="playerId"
                                    @keydown.enter.prevent="verifyPlayer()"
                                    placeholder="e.g. 3245770826"
                                    class="w-full rounded-2xl text-sm font-mono px-4 py-3 glass-input border border-gray-200 dark:border-white/10"
                                >
                            </div>
                            <x-button
                                type="button"
                                variant="glass"
                                @click="verifyPlayer()"
                                :loading="false"
                                class="shrink-0"
                            >
                                <span x-show="!isVerifyingPlayer">Verify UID</span>
                                <span x-show="isVerifyingPlayer">Checking...</span>
                            </x-button>
                        </div>
                        <p class="text-[11px] text-slate-400">Found in Free Fire Profile page (8-10 digits). Test UID: <button type="button" @click="playerId = '3245770826'; verifyPlayer();" class="text-blue-500 font-mono underline">3245770826</button></p>
                    </div>
                @elseif($game->slug === 'mobile-legends')
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                User ID <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="player_id_input"
                                type="text"
                                x-model="playerId"
                                placeholder="e.g. 12345678"
                                class="w-full rounded-2xl text-sm font-mono px-4 py-3 glass-input border border-gray-200 dark:border-white/10"
                            >
                            <p class="text-[11px] text-slate-400">Enter your 8-9 digit User ID</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                Zone ID <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <input
                                    id="zone_id_input"
                                    type="text"
                                    x-model="zoneId"
                                    placeholder="e.g. 2024"
                                    class="w-full rounded-2xl text-sm font-mono px-4 py-3 glass-input border border-gray-200 dark:border-white/10"
                                >
                            </div>
                            <p class="text-[11px] text-slate-400">4-5 digits in brackets</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <x-button type="button" variant="glass" size="sm" @click="verifyPlayer()">
                            <span x-show="!isVerifyingPlayer">Verify Account</span>
                            <span x-show="isVerifyingPlayer">Checking...</span>
                        </x-button>
                    </div>
                @endif

                <!-- Verified Account Feedback -->
                <div x-show="playerVerified" x-transition class="mt-4 p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center justify-between" style="display: none;">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Account Verified: <span class="font-black" x-text="playerNickname"></span></span>
                    </div>
                    <span class="text-[10px] uppercase tracking-wider text-emerald-500 bg-emerald-500/20 px-2 py-0.5 rounded-md">Ready</span>
                </div>

                <!-- Error feedback -->
                <div x-show="playerError" x-transition class="mt-3 text-xs text-rose-500 font-semibold" x-text="playerError" style="display: none;"></div>
            </div>

            <!-- STEP 2: Select Package -->
            <div id="packages-step" class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold text-sm flex items-center justify-center shadow-md shadow-blue-500/30">
                        2
                    </span>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Select Diamond Package</h2>
                        <p class="text-xs text-slate-400">All prices in Cambodian Riel (៛) &bull; Database-driven rates</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 sm:gap-4">
                    @foreach($game->activePackages as $pkg)
                        <div
                            @click="selectPackage({{ json_encode($pkg) }})"
                            :class="{
                                'border-blue-500 bg-blue-50/50 dark:bg-blue-950/30 ring-2 ring-blue-500/30 shadow-md': packageId === {{ $pkg->id }},
                                'border-white/40 dark:border-white/10 hover:border-blue-500/40 bg-white/40 dark:bg-slate-900/40': packageId !== {{ $pkg->id }}
                            }"
                            class="rounded-2xl p-4 border transition-all duration-200 cursor-pointer relative flex flex-col justify-between select-none active:scale-[0.98]"
                        >
                            <!-- Badge if any -->
                            @if($pkg->badge)
                                <span class="absolute -top-2.5 right-3 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-sm">
                                    {{ $pkg->badge }}
                                </span>
                            @endif

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 9l10 13 10-13-10-7zm0 3.2L18.4 9H5.6L12 5.2z"/></svg>
                                    </div>
                                    <h4 class="font-extrabold text-sm text-slate-900 dark:text-white line-clamp-1">{{ $pkg->name }}</h4>
                                </div>

                                @if($pkg->bonus_value > 0)
                                    <span class="inline-block text-[11px] font-bold text-emerald-500 mb-1">
                                        +{{ $pkg->bonus_value }} Bonus
                                    </span>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-gray-100 dark:border-white/5 flex items-baseline justify-between">
                                <span class="font-black text-sm text-blue-600 dark:text-blue-400">
                                    {{ $pkg->formatted_price_khr }}
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    {{ $pkg->formatted_price_usd }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- STEP 3: Payment Method -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold text-sm flex items-center justify-center shadow-md shadow-blue-500/30">
                        3
                    </span>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Payment Method</h2>
                        <p class="text-xs text-slate-400">Instant QR scan in Cambodia</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($paymentMethods as $pm)
                        <div class="rounded-2xl p-4 border border-blue-500 bg-blue-50/50 dark:bg-blue-950/20 ring-2 ring-blue-500/20 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 p-1 flex items-center justify-center shadow-sm shrink-0 border border-gray-100 dark:border-white/10">
                                    <span class="font-black text-xs text-blue-600">ABA</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $pm->name }}</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ $pm->description }}</p>
                                </div>
                            </div>

                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">
                                &check;
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- STEP 4: Optional Discount Voucher -->
            <div class="glass-card rounded-3xl p-6 border border-white/40 dark:border-white/10">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow-md shadow-indigo-500/30">
                        4
                    </span>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Have a Promo Voucher? (Optional)</h3>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <input
                        type="text"
                        x-model="promoCode"
                        placeholder="Enter voucher code (e.g. KHMERNEWYEAR)"
                        class="w-full rounded-2xl text-xs uppercase font-mono px-4 py-2.5 glass-input border border-gray-200 dark:border-white/10"
                    >
                    <span class="text-xs text-slate-400 self-center">Codes applied at checkout</span>
                </div>
            </div>

            <!-- Error Banner -->
            <div x-show="checkoutError" x-transition class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-semibold" x-text="checkoutError" style="display: none;"></div>

            <!-- Desktop Large Checkout Button -->
            <div class="hidden md:block">
                <x-button
                    type="button"
                    variant="primary"
                    size="xl"
                    class="w-full shadow-2xl shadow-blue-500/30"
                    @click="submitCheckout()"
                    x-bind:disabled="isSubmitting"
                >
                    <span x-show="!isSubmitting" class="flex items-center justify-center gap-2">
                        <span>Continue to ABA KHQR</span>
                        <span x-show="packageId" class="opacity-90 font-mono">(&bull; <span x-text="formattedTotalKhr"></span>)</span>
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                    <span x-show="isSubmitting">Generating KHQR Invoice...</span>
                </x-button>
            </div>
        </div>

        <!-- Sticky Mobile Bottom Checkout Bar -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 glass-panel border-t border-white/40 dark:border-white/10 px-4 py-3 safe-pb shadow-2xl">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block" x-text="packageName || 'Select Package'"></span>
                    <span class="text-lg font-black text-slate-900 dark:text-white" x-text="packageId ? formattedTotalKhr : '0៛'"></span>
                </div>

                <x-button
                    type="button"
                    variant="primary"
                    size="md"
                    @click="submitCheckout()"
                    x-bind:disabled="isSubmitting || !packageId"
                    class="shadow-lg shadow-blue-500/25 shrink-0"
                >
                    <span x-show="!isSubmitting">Checkout Now</span>
                    <span x-show="isSubmitting">Loading...</span>
                </x-button>
            </div>
        </div>

        <!-- Help Guide Modal -->
        <x-modal name="guide-modal" title="How to find your {{ $game->name }} ID">
            <div class="space-y-4 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                <p>{{ $game->instruction }}</p>
                <div class="p-4 rounded-2xl bg-black/5 dark:bg-white/5 space-y-2">
                    <p class="font-bold text-slate-900 dark:text-white">Need personal assistance?</p>
                    <p>Contact our Cambodian support on Telegram: <a href="https://t.me/lstechcambodiagroup" target="_blank" class="text-blue-500 font-bold underline">@lstech_support</a></p>
                </div>
            </div>
        </x-modal>
    </div>

</x-app-layout>
