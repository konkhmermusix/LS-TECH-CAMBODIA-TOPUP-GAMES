<x-admin-layout title="Orders Management">
    <x-slot name="header">
        Orders Management
    </x-slot>

    <!-- Filters & Search Bar -->
    <div class="glass-card rounded-3xl p-5 mb-6 border border-white/40 dark:border-white/10">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <x-input
                    name="search"
                    placeholder="Search by Order # or Player ID..."
                    value="{{ request('search') }}"
                    icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                />
            </div>

            <!-- Game Filter -->
            <div>
                <x-select name="game_id">
                    <option value="">All Games</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            <!-- Status Filter -->
            <div>
                <x-select name="status">
                    <option value="">All Statuses</option>
                    <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </x-select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <x-button type="submit" variant="primary" class="w-full">
                    Filter
                </x-button>
                @if(request()->hasAny(['search', 'game_id', 'status', 'date']))
                    <a href="{{ route('admin.orders.index') }}" class="p-2.5 rounded-2xl glass-card text-gray-500 hover:text-gray-700 dark:hover:text-white" title="Reset Filters">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    @if($orders->count() > 0)
        <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-5 py-3.5">Order Number</th>
                            <th class="px-5 py-3.5">Game</th>
                            <th class="px-5 py-3.5">Package</th>
                            <th class="px-5 py-3.5">Player Details</th>
                            <th class="px-5 py-3.5">Amount</th>
                            <th class="px-5 py-3.5">Order Status</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($orders as $order)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">
                                    {{ $order->game->name }}
                                </td>
                                <td class="px-5 py-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    {{ $order->package_name }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-mono font-bold text-xs text-slate-800 dark:text-slate-200 block">{{ $order->player_id }}</span>
                                    @if($order->zone_id)
                                        <span class="text-[10px] text-slate-400">Zone: {{ $order->zone_id }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $order->formatted_total }}
                                </td>
                                <td class="px-5 py-4">
                                    @php $badge = $order->status_badge; @endphp
                                    <span class="inline-flex px-2.5 py-1 text-[11px] font-bold rounded-full border {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-400">
                                    {{ $order->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-xl glass-card text-xs font-bold text-blue-600 dark:text-blue-400 hover:scale-105 transition-transform inline-block">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div>
            {{ $orders->links() }}
        </div>
    @else
        <x-empty-state
            title="No orders found"
            description="No orders match your filter criteria. Try adjusting your search query."
        />
    @endif
</x-admin-layout>
