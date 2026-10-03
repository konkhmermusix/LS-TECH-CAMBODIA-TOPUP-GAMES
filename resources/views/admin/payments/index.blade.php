<x-admin-layout title="ABA Payments">
    <x-slot name="header">
        ABA KHQR Payments
    </x-slot>

    <!-- Search & Filter -->
    <div class="glass-card rounded-3xl p-5 mb-6 border border-white/40 dark:border-white/10">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <x-input
                    name="search"
                    placeholder="Search by Transaction ID, ABA Ref, or Order #..."
                    value="{{ request('search') }}"
                    icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                />
            </div>

            <div class="flex items-center gap-2">
                <x-select name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </x-select>

                <x-button type="submit" variant="primary">
                    Filter
                </x-button>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    @if($payments->count() > 0)
        <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-5 py-3.5">Transaction ID</th>
                            <th class="px-5 py-3.5">Order</th>
                            <th class="px-5 py-3.5">Gateway Ref</th>
                            <th class="px-5 py-3.5">Amount</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Expires</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-900 dark:text-white">
                                    {{ $payment->transaction_id }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="font-mono text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $payment->order->order_number ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs text-slate-500">
                                    {{ $payment->gateway_transaction_id ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ number_format($payment->amount) }}៛
                                </td>
                                <td class="px-5 py-3.5">
                                    @php $pb = $payment->status_badge; @endphp
                                    <span class="inline-flex px-2.5 py-1 text-[11px] font-bold rounded-full border {{ $pb['class'] }}">
                                        {{ $pb['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-400">
                                    {{ $payment->expires_at?->diffForHumans() ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    @if($payment->isPending())
                                        <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded-xl glass-card text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:scale-105 transition-transform">
                                                Verify with ABA
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">Verified</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $payments->links() }}
        </div>
    @else
        <x-empty-state title="No payments found" description="Transactions created by the ABA PayWay engine will appear here." />
    @endif
</x-admin-layout>
