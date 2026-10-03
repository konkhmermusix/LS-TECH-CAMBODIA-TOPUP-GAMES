<x-admin-layout title="Admin Dashboard">
    <x-slot name="header">
        Executive Dashboard
    </x-slot>

    <!-- Top Summary Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Orders Card -->
        <x-card :hover="false" class="relative overflow-hidden border-blue-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Orders</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1.5">{{ number_format($totalOrders) }}</h3>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-500 mt-2">
                        <span>+{{ $todayOrders }} today</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
        </x-card>

        <!-- Revenue Card -->
        <x-card :hover="false" class="relative overflow-hidden border-emerald-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Revenue</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1.5">{{ number_format($totalRevenueKhr) }}៛</h3>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-500 mt-2">
                        <span>+{{ number_format($todayRevenueKhr) }}៛ today</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </x-card>

        <!-- Top-Up Success Rate -->
        <x-card :hover="false" class="relative overflow-hidden border-indigo-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Top-Up Success Rate</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1.5">{{ $topupSuccessRate }}%</h3>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 mt-2">
                        <span>{{ $successfulOrders }} Delivered</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </x-card>

        <!-- Provider Status / Pending -->
        <x-card :hover="false" class="relative overflow-hidden border-amber-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Provider Balance</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1.5">
                        ${{ number_format($providerBalance['balance'] ?? 0, 2) }}
                    </h3>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-500 mt-2">
                        <span>{{ $pendingOrders }} Orders In Queue</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Active Games & Recent Orders Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Recent Orders (2 Columns) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Recent Top-Up Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    View All Orders &rarr;
                </a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                                <tr>
                                    <th class="px-5 py-3.5">Order</th>
                                    <th class="px-5 py-3.5">Game & Package</th>
                                    <th class="px-5 py-3.5">Player Info</th>
                                    <th class="px-5 py-3.5">Amount</th>
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-5 py-3.5">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $order->order_number }}
                                            </a>
                                            <p class="text-[10px] text-slate-400">{{ $order->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="font-semibold text-slate-900 dark:text-white block">{{ $order->game->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $order->package_name }}</span>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="font-mono font-medium text-xs block text-slate-700 dark:text-slate-300">{{ $order->player_id }}</span>
                                            @if($order->zone_id)
                                                <span class="text-[10px] text-slate-400">Zone: {{ $order->zone_id }}</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                            {{ $order->formatted_total }}
                                        </td>
                                        <td class="px-5 py-3.5">
                                            @php $badge = $order->status_badge; @endphp
                                            <span class="inline-flex px-2.5 py-1 text-[11px] font-bold rounded-full border {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1.5 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 text-slate-400 hover:text-slate-700 dark:hover:text-white inline-block">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <x-empty-state title="No orders yet" description="Orders placed by customers will appear here." />
            @endif
        </div>

        <!-- Games Quick Status (1 Column) -->
        <div class="space-y-4">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Game Catalog</h3>

            <div class="space-y-3">
                @foreach($games as $game)
                    <div class="glass-card rounded-2xl p-4 flex items-center justify-between border border-white/40 dark:border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 shrink-0">
                                <img src="{{ $game->logo }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $game->name }}</h4>
                                <p class="text-xs text-slate-400">{{ $game->orders_count }} total orders</p>
                            </div>
                        </div>

                        <div>
                            @if($game->is_active)
                                <x-badge color="emerald" :dot="true">Active</x-badge>
                            @else
                                <x-badge color="gray" :dot="true">Inactive</x-badge>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Provider Health Box -->
            <div class="glass-card rounded-2xl p-5 border border-white/40 dark:border-white/10">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Payment & Top-Up Engine</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-white/5">
                        <span class="text-slate-500">ABA PayWay Gateway</span>
                        <span class="font-semibold text-emerald-500">Connected (KHQR Ready)</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-white/5">
                        <span class="text-slate-500">Active Provider</span>
                        <span class="font-semibold text-blue-500">{{ config('topup.active_provider') }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Queue Worker</span>
                        <span class="font-semibold text-emerald-500">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Top-Up Log Table -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Recent Automated Top-Up Transactions</h3>
            <a href="{{ route('admin.topups.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                View All Logs &rarr;
            </a>
        </div>

        @if($recentTransactions->count() > 0)
            <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                            <tr>
                                <th class="px-5 py-3.5">Reference</th>
                                <th class="px-5 py-3.5">Order Ref</th>
                                <th class="px-5 py-3.5">Game</th>
                                <th class="px-5 py-3.5">Player ID</th>
                                <th class="px-5 py-3.5">Provider</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @foreach($recentTransactions as $txn)
                                <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-700 dark:text-slate-300">
                                        {{ $txn->reference_id }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <a href="{{ route('admin.orders.show', $txn->order_id) }}" class="font-mono text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $txn->order->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5 text-xs font-medium">
                                        {{ $txn->order->game->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-5 py-3.5 font-mono text-xs">
                                        {{ $txn->player_id }}
                                    </td>
                                    <td class="px-5 py-3.5 text-xs font-semibold capitalize">
                                        {{ str_replace('_', ' ', $txn->provider) }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @php $b = $txn->status_badge; @endphp
                                        <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-full border {{ $b['class'] }}">
                                            {{ $b['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-slate-400">
                                        {{ $txn->created_at->format('M d, H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <x-empty-state title="No transactions yet" description="Top-up execution logs will appear once customers place orders." />
        @endif
    </div>
</x-admin-layout>
