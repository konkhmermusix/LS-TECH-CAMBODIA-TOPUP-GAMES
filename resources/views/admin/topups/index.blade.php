<x-admin-layout title="Top-Up Provider Logs">
    <x-slot name="header">
        Authorized Top-Up Execution Logs
    </x-slot>

    <!-- Top Provider Status Banner -->
    <div class="glass-card rounded-3xl p-6 mb-6 border border-white/40 dark:border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Configured Top-Up Provider</span>
            <h3 class="text-lg font-black text-slate-900 dark:text-white capitalize">
                {{ str_replace('_', ' ', config('topup.active_provider')) }}
            </h3>
        </div>

        <div class="flex items-center gap-4 text-xs">
            <div class="px-4 py-2 rounded-2xl bg-black/5 dark:bg-white/5">
                <span class="text-slate-400 block">Available Quota / Balance</span>
                <span class="font-bold text-base text-emerald-500">${{ number_format($providerBalance['balance'] ?? 0, 2) }}</span>
            </div>
            <div class="px-4 py-2 rounded-2xl bg-black/5 dark:bg-white/5">
                <span class="text-slate-400 block">Provider Status</span>
                <span class="font-bold text-base text-blue-500">Operational</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="glass-card rounded-3xl p-5 mb-6 border border-white/40 dark:border-white/10">
        <form method="GET" action="{{ route('admin.topups.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <x-input
                    name="search"
                    placeholder="Search by Reference ID, Provider Order ID, or Player UID..."
                    value="{{ request('search') }}"
                    icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                />
            </div>

            <div class="flex items-center gap-2">
                <x-select name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </x-select>

                <x-button type="submit" variant="primary">
                    Filter
                </x-button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    @if($transactions->count() > 0)
        <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-5 py-3.5">Reference ID</th>
                            <th class="px-5 py-3.5">Order</th>
                            <th class="px-5 py-3.5">Game</th>
                            <th class="px-5 py-3.5">Player UID</th>
                            <th class="px-5 py-3.5">Provider ID</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Execution Details</th>
                            <th class="px-5 py-3.5">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($transactions as $txn)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-900 dark:text-white">
                                    {{ $txn->reference_id }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.orders.show', $txn->order_id) }}" class="font-mono text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $txn->order->order_number ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-xs font-semibold">
                                    {{ $txn->order->game->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs">
                                    {{ $txn->player_id }}
                                    @if($txn->zone_id)
                                        <span class="text-[10px] text-slate-400">({{ $txn->zone_id }})</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs text-slate-500">
                                    {{ $txn->provider_order_id ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @php $b = $txn->status_badge; @endphp
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-full border {{ $b['class'] }}">
                                        {{ $b['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    @if($txn->error_message)
                                        <span class="text-rose-500 font-medium truncate block max-w-xs" title="{{ $txn->error_message }}">{{ $txn->error_message }}</span>
                                    @else
                                        <span class="text-emerald-500">Completed in {{ $txn->executed_at && $txn->completed_at ? $txn->executed_at->diffInSeconds($txn->completed_at) . 's' : 'Instant' }}</span>
                                    @endif
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

        <div>
            {{ $transactions->links() }}
        </div>
    @else
        <x-empty-state title="No top-up transactions" description="Top-up provider execution records will appear here." />
    @endif
</x-admin-layout>
