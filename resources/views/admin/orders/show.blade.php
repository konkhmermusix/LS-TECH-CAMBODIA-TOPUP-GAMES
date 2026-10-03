<x-admin-layout title="Order #{{ $order->order_number }}">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-xl glass-card text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <span>Order #{{ $order->order_number }}</span>
        </div>
    </x-slot>

    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            @php $badge = $order->status_badge; @endphp
            <span class="inline-flex px-3.5 py-1.5 text-xs font-bold rounded-full border {{ $badge['class'] }}">
                {{ $badge['label'] }}
            </span>
            <span class="text-xs text-slate-400">Created: {{ $order->created_at->format('M d, Y H:i:s') }}</span>
        </div>

        <div class="flex items-center gap-3">
            @if($order->isPaid() && $order->status !== \App\Models\Order::STATUS_COMPLETED)
                <form method="POST" action="{{ route('admin.orders.retry', $order->id) }}">
                    @csrf
                    <x-button type="submit" variant="primary" size="sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Retry Automatic Top-Up</span>
                    </x-button>
                </form>
            @endif

            @if(!$order->isCompleted() && $order->status !== \App\Models\Order::STATUS_CANCELLED)
                <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}">
                    @csrf
                    <x-button type="submit" variant="danger" size="sm">
                        Cancel Order
                    </x-button>
                </form>
            @endif
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Game & Package Info (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Game Item Card -->
            <x-card :hover="false">
                <div class="flex items-start gap-5">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-800 shrink-0 shadow-md">
                        <img src="{{ $order->game->logo }}" alt="{{ $order->game->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">{{ $order->game->name }}</h3>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $order->game->publisher }}</span>
                        </div>
                        <p class="text-sm font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $order->package_name }}</p>

                        <!-- Player Target Information -->
                        <div class="mt-4 p-4 rounded-2xl bg-black/5 dark:bg-white/5 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                            <div>
                                <span class="text-slate-400 block">Player UID</span>
                                <span class="font-mono font-bold text-sm text-slate-900 dark:text-white">{{ $order->player_id }}</span>
                            </div>
                            @if($order->zone_id)
                                <div>
                                    <span class="text-slate-400 block">Zone / Server ID</span>
                                    <span class="font-mono font-bold text-sm text-slate-900 dark:text-white">{{ $order->zone_id }}</span>
                                </div>
                            @endif
                            @if($order->player_nickname)
                                <div>
                                    <span class="text-slate-400 block">Nickname</span>
                                    <span class="font-bold text-sm text-emerald-500">{{ $order->player_nickname }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Top-Up Provider Transactions -->
            <x-card :hover="false">
                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Authorized Top-Up Provider Execution Logs</span>
                </h4>

                @if($order->topupTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($order->topupTransactions as $txn)
                            <div class="p-4 rounded-2xl border border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-slate-900/40 text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200">Ref: {{ $txn->reference_id }}</span>
                                    @php $tb = $txn->status_badge; @endphp
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-full border {{ $tb['class'] }}">
                                        {{ $tb['label'] }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-slate-500 dark:text-slate-400 pt-1">
                                    <div>Provider: <span class="font-semibold text-slate-800 dark:text-slate-200 capitalize">{{ $txn->provider }}</span></div>
                                    <div>Provider Order ID: <span class="font-mono font-semibold text-slate-800 dark:text-slate-200">{{ $txn->provider_order_id ?? 'None' }}</span></div>
                                    <div>Executed: <span class="text-slate-800 dark:text-slate-200">{{ $txn->executed_at?->format('H:i:s') ?? '-' }}</span></div>
                                    <div>Completed: <span class="text-slate-800 dark:text-slate-200">{{ $txn->completed_at?->format('H:i:s') ?? '-' }}</span></div>
                                </div>

                                @if($txn->error_message)
                                    <div class="p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 font-medium">
                                        Error: {{ $txn->error_message }} (Code: {{ $txn->error_code ?? 'UNKNOWN' }})
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No top-up dispatch recorded yet. Top-up will execute immediately once payment is confirmed.</p>
                @endif
            </x-card>
        </div>

        <!-- Payment & Financial Info (Right Col) -->
        <div class="space-y-6">
            <!-- Price Summary -->
            <x-card :hover="false">
                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white mb-4">Financial Summary</h4>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Unit Price</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($order->unit_price) }}៛</span>
                    </div>

                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-500 font-semibold">
                            <span>Discount Voucher</span>
                            <span>-{{ number_format($order->discount_amount) }}៛</span>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-gray-100 dark:border-white/10 flex justify-between items-baseline">
                        <span class="text-base font-extrabold text-slate-900 dark:text-white">Total Amount</span>
                        <span class="text-xl font-black text-blue-600 dark:text-blue-400">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </x-card>

            <!-- ABA KHQR Payment Info -->
            <x-card :hover="false">
                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                    <span>Payment Gateway</span>
                    <span class="text-[11px] font-bold text-blue-500 uppercase">ABA KHQR</span>
                </h4>

                @if($order->payments->count() > 0)
                    @php $payment = $order->payments->first(); @endphp
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-white/5">
                            <span class="text-slate-400">Transaction ID</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $payment->transaction_id }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-white/5">
                            <span class="text-slate-400">Payment Status</span>
                            @php $pb = $payment->status_badge; @endphp
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-full border {{ $pb['class'] }}">
                                {{ $pb['label'] }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-white/5">
                            <span class="text-slate-400">Gateway Ref</span>
                            <span class="font-mono text-slate-800 dark:text-slate-200">{{ $payment->gateway_transaction_id ?? 'Pending ABA' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-white/5">
                            <span class="text-slate-400">Verified At</span>
                            <span class="text-slate-800 dark:text-slate-200">{{ $payment->verified_at?->format('M d, H:i') ?? 'Not verified' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">Expires At</span>
                            <span class="text-slate-800 dark:text-slate-200">{{ $payment->expires_at?->format('M d, H:i') ?? '-' }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No payment record created for this order.</p>
                @endif
            </x-card>
        </div>
    </div>
</x-admin-layout>
