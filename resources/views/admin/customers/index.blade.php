<x-admin-layout title="Buyers / Customers">
    <x-slot name="header">
        Buyers / Guest Customers
    </x-slot>

    <!-- Search -->
    <div class="glass-card rounded-3xl p-5 mb-6 border border-white/40 dark:border-white/10">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-4">
            <div class="flex-1">
                <x-input
                    name="search"
                    placeholder="Search by Player UID or Contact Phone..."
                    value="{{ request('search') }}"
                    icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                />
            </div>
            <x-button type="submit" variant="primary">Search</x-button>
        </form>
    </div>

    @if($customers->count() > 0)
        <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-5 py-3.5">Player UID</th>
                            <th class="px-5 py-3.5">Gamer Nickname</th>
                            <th class="px-5 py-3.5">Phone Number</th>
                            <th class="px-5 py-3.5">Orders Placed</th>
                            <th class="px-5 py-3.5">Total Spend</th>
                            <th class="px-5 py-3.5">Last Purchased</th>
                            <th class="px-5 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($customers as $c)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-900 dark:text-white">
                                    {{ $c->player_id }}
                                </td>
                                <td class="px-5 py-3.5 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    {{ $c->player_nickname ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs">
                                    {{ $c->customer_phone ?? 'Guest (No phone)' }}
                                </td>
                                <td class="px-5 py-3.5 font-bold text-xs text-blue-600 dark:text-blue-400">
                                    {{ $c->total_orders }} orders
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ number_format($c->total_spent) }}៛
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($c->last_order_at)->diffForHumans() }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.orders.index', ['search' => $c->player_id]) }}" class="px-3 py-1 rounded-xl glass-card text-xs font-bold text-blue-600 dark:text-blue-400 hover:scale-105 transition-transform inline-block">
                                        View Orders
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $customers->links() }}
        </div>
    @else
        <x-empty-state title="No customer records" description="Guest buyers will be tracked automatically upon checkout." />
    @endif
</x-admin-layout>
