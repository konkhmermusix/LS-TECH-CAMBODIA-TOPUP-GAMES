<x-app-layout :title="'Order #' . $order->order_number . ' Status'">

    <div
        x-data="{
            orderNumber: '{{ $order->order_number }}',
            orderStatus: '{{ $order->status }}',
            paymentStatus: '{{ $order->latestPayment?->status ?? 'pending' }}',
            topupStatus: '{{ $order->latestTopupTransaction?->status ?? 'pending' }}',
            isCompleted: {{ $order->isCompleted() ? 'true' : 'false' }},
            isPaid: {{ $order->isPaid() ? 'true' : 'false' }},
            isFailed: {{ $order->isFailed() ? 'true' : 'false' }},
            pollTimer: null,

            init() {
                if (!this.isCompleted && !this.isFailed) {
                    this.startPolling();
                }
            },

            startPolling() {
                this.pollTimer = setInterval(async () => {
                    try {
                        const res = await fetch('/order/' + this.orderNumber + '/status', {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();

                        this.orderStatus = data.order_status;
                        this.paymentStatus = data.payment_status;
                        this.topupStatus = data.topup_status;
                        this.isCompleted = data.is_completed;
                        this.isPaid = data.is_paid;
                        this.isFailed = data.is_failed;

                        if (this.isCompleted) {
                            clearInterval(this.pollTimer);
                            $store.toast.success('Diamonds successfully delivered!');
                        } else if (this.isFailed) {
                            clearInterval(this.pollTimer);
                            $store.toast.error('Top-up issue encountered.');
                        }
                    } catch (e) {
                        console.error('Polling error', e);
                    }
                }, 2500);
            },

            copyOrderNumber() {
                navigator.clipboard.writeText(this.orderNumber);
                $store.toast.success('Order Number copied to clipboard!');
            }
        }"
        class="max-w-2xl mx-auto py-6 sm:py-10"
    >
        <!-- Top Status Hero Box -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/50 dark:border-white/10 text-center shadow-xl mb-8">
            <!-- Dynamic State Icon -->
            <div class="mb-4">
                <template x-if="isCompleted">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </template>
                <template x-if="!isCompleted && !isFailed">
                    <div class="w-16 h-16 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center mx-auto animate-pulse">
                        <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                </template>
                <template x-if="isFailed">
                    <div class="w-16 h-16 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto shadow-lg shadow-rose-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                </template>
            </div>

            <!-- Status Title -->
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                <template x-if="isCompleted">
                    <span>Top-Up Delivered Successfully!</span>
                </template>
                <template x-if="!isCompleted && isPaid">
                    <span>Delivering Diamonds...</span>
                </template>
                <template x-if="!isPaid && !isFailed">
                    <span>Payment Pending</span>
                </template>
                <template x-if="isFailed">
                    <span>Top-Up Encountered an Issue</span>
                </template>
            </h1>

            <!-- Order Number Pill with Copy -->
            <div class="mt-3 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/5 dark:bg-white/5 border border-white/20 dark:border-white/10 text-xs font-mono">
                <span class="text-slate-400">Order #</span>
                <span class="font-bold text-slate-900 dark:text-white" x-text="orderNumber"></span>
                <button type="button" @click="copyOrderNumber()" class="text-blue-500 hover:text-blue-600 ml-1" title="Copy Order Number">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>

            <!-- 4-Stage Lifecycle Stepper -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/10">
                <div class="grid grid-cols-4 gap-2 text-center">
                    <!-- Step 1: Created -->
                    <div>
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center mx-auto mb-1.5 shadow-sm">
                            &check;
                        </div>
                        <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200 block">Created</span>
                    </div>

                    <!-- Step 2: Payment -->
                    <div>
                        <div
                            class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center mx-auto mb-1.5 shadow-sm"
                            :class="isPaid ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-slate-800 text-slate-400'"
                        >
                            <span x-show="isPaid">&check;</span>
                            <span x-show="!isPaid">2</span>
                        </div>
                        <span class="text-[11px] font-bold block" :class="isPaid ? 'text-slate-800 dark:text-slate-200' : 'text-slate-400'">
                            Payment
                        </span>
                    </div>

                    <!-- Step 3: Top-Up Process -->
                    <div>
                        <div
                            class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center mx-auto mb-1.5 shadow-sm"
                            :class="isCompleted ? 'bg-blue-600 text-white' : (isPaid ? 'bg-amber-500 text-white animate-pulse' : 'bg-gray-200 dark:bg-slate-800 text-slate-400')"
                        >
                            <span x-show="isCompleted">&check;</span>
                            <span x-show="!isCompleted">3</span>
                        </div>
                        <span class="text-[11px] font-bold block" :class="isPaid ? 'text-slate-800 dark:text-slate-200' : 'text-slate-400'">
                            Top-Up
                        </span>
                    </div>

                    <!-- Step 4: Delivered -->
                    <div>
                        <div
                            class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center mx-auto mb-1.5 shadow-sm"
                            :class="isCompleted ? 'bg-emerald-500 text-white' : 'bg-gray-200 dark:bg-slate-800 text-slate-400'"
                        >
                            <span x-show="isCompleted">&check;</span>
                            <span x-show="!isCompleted">4</span>
                        </div>
                        <span class="text-[11px] font-bold block" :class="isCompleted ? 'text-emerald-500' : 'text-slate-400'">
                            Delivered
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Card -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10 space-y-6 mb-8">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-white/10">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Transaction Receipt</h3>
                <span class="text-xs text-slate-400">{{ $order->created_at->format('M d, Y H:i:s') }}</span>
            </div>

            <!-- Game & Target Player -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-900 shrink-0 shadow-md">
                    <img src="{{ $order->game->logo }}" alt="{{ $order->game->name }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h4 class="font-extrabold text-base text-slate-900 dark:text-white">{{ $order->game->name }}</h4>
                    <p class="text-xs font-bold text-blue-600 dark:text-blue-400">{{ $order->package_name }}</p>
                </div>
            </div>

            <!-- Details List -->
            <div class="space-y-3 text-xs pt-2">
                <div class="flex justify-between py-1.5 border-b border-gray-100 dark:border-white/5">
                    <span class="text-slate-500">Player UID</span>
                    <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $order->player_id }}</span>
                </div>

                @if($order->zone_id)
                    <div class="flex justify-between py-1.5 border-b border-gray-100 dark:border-white/5">
                        <span class="text-slate-500">Zone / Server ID</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $order->zone_id }}</span>
                    </div>
                @endif

                @if($order->player_nickname)
                    <div class="flex justify-between py-1.5 border-b border-gray-100 dark:border-white/5">
                        <span class="text-slate-500">Account Nickname</span>
                        <span class="font-bold text-emerald-500">{{ $order->player_nickname }}</span>
                    </div>
                @endif

                <div class="flex justify-between py-1.5 border-b border-gray-100 dark:border-white/5">
                    <span class="text-slate-500">Payment Status</span>
                    <span class="font-bold uppercase" :class="isPaid ? 'text-emerald-500' : 'text-amber-500'" x-text="paymentStatus"></span>
                </div>

                <div class="flex justify-between py-1.5 border-b border-gray-100 dark:border-white/5">
                    <span class="text-slate-500">Top-Up Status</span>
                    <span class="font-bold uppercase" :class="isCompleted ? 'text-emerald-500' : (isFailed ? 'text-rose-500' : 'text-blue-500')" x-text="topupStatus"></span>
                </div>

                <div class="flex justify-between py-2 items-baseline text-sm">
                    <span class="font-extrabold text-slate-900 dark:text-white">Amount Paid</span>
                    <span class="font-black text-lg text-blue-600 dark:text-blue-400">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        <!-- Action Links -->
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('home') }}">
                <x-button variant="primary" size="md">
                    Top Up Another Game
                </x-button>
            </a>

            <a href="https://t.me/lstechcambodiagroup" target="_blank" class="px-5 py-2.5 rounded-2xl glass-card text-xs font-bold text-blue-500 hover:scale-105 transition-transform">
                Need Help? Telegram
            </a>
        </div>
    </div>

</x-app-layout>
