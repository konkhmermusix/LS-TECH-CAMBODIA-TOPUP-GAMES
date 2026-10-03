<x-app-layout title="Track Game Top-Up Order">

    <div class="max-w-2xl mx-auto py-8 sm:py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Track Your Top-Up Order</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2">
                Enter your Order Number (e.g. TOP-20261003-XXXXXX) or your Player UID to check your delivery status.
            </p>
        </div>

        <!-- Search Form -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/50 dark:border-white/10 shadow-xl mb-8">
            <form method="GET" action="{{ route('orders.track') }}" class="space-y-4">
                <x-input
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Enter Order Number or Player UID..."
                    required
                    icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                />

                <x-button type="submit" variant="primary" size="lg" class="w-full">
                    Track Order Now
                </x-button>
            </form>

            @if(isset($error) && $error)
                <div class="mt-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-semibold">
                    {{ $error }}
                </div>
            @endif
        </div>

        <!-- Result if Found -->
        @if(isset($order) && $order)
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10 shadow-xl space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-white/10">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400">Order Number</span>
                        <h3 class="font-mono text-base font-bold text-slate-900 dark:text-white">{{ $order->order_number }}</h3>
                    </div>
                    @php $b = $order->status_badge; @endphp
                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full border {{ $b['class'] }}">
                        {{ $b['label'] }}
                    </span>
                </div>

                <div class="flex items-center gap-4 py-2">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-900 shrink-0">
                        <img src="{{ $order->game->logo }}" alt="{{ $order->game->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $order->game->name }}</h4>
                        <p class="text-xs text-blue-500 font-semibold">{{ $order->package_name }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-white/5 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Created: {{ $order->created_at->format('M d, Y H:i') }}</span>
                    <a href="{{ route('order.status', $order->order_number) }}">
                        <x-button variant="primary" size="sm">
                            View Full Details &rarr;
                        </x-button>
                    </a>
                </div>
            </div>
        @endif
    </div>

</x-app-layout>
