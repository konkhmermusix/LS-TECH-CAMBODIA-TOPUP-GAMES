<x-admin-layout title="Analytics & Reports">
    <x-slot name="header">
        Reports & Analytics
    </x-slot>

    <!-- Top Grid: Performance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Sales by Game -->
        <div class="md:col-span-2 glass-card rounded-3xl p-6 border border-white/40 dark:border-white/10">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4">Revenue Breakdown by Game</h3>

            <div class="space-y-4">
                @foreach($salesByGame as $item)
                    <div class="p-4 rounded-2xl bg-black/5 dark:bg-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 shrink-0 shadow-sm">
                                <img src="{{ $item->logo }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $item->name }}</h4>
                                <p class="text-xs text-slate-400">{{ $item->total_orders }} orders placed</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-base font-black text-blue-600 dark:text-blue-400">{{ number_format($item->total_revenue ?? 0) }}៛</span>
                            <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wider">Settled</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- TopUp Status Distribution -->
        <div class="glass-card rounded-3xl p-6 border border-white/40 dark:border-white/10">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4">Top-Up Engine Health</h3>

            <div class="space-y-3">
                <div class="p-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex justify-between items-center text-xs">
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">Successful Top-Ups</span>
                    <span class="font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400">{{ $topupStats['success'] ?? 0 }}</span>
                </div>
                <div class="p-3 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex justify-between items-center text-xs">
                    <span class="font-bold text-amber-600 dark:text-amber-400">Processing in Queue</span>
                    <span class="font-mono font-bold text-sm text-amber-600 dark:text-amber-400">{{ $topupStats['processing'] ?? 0 }}</span>
                </div>
                <div class="p-3 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex justify-between items-center text-xs">
                    <span class="font-bold text-rose-600 dark:text-rose-400">Provider Errors / Failed</span>
                    <span class="font-mono font-bold text-sm text-rose-600 dark:text-rose-400">{{ $topupStats['failed'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Table -->
    <div class="glass-card rounded-3xl p-6 border border-white/40 dark:border-white/10">
        <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4">Last 7 Days Revenue Trend</h3>

        @if($sevenDaysRevenue->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Orders Processed</th>
                            <th class="px-5 py-3.5 text-right">Settled Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($sevenDaysRevenue as $day)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($day->order_date)->format('M d, Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-300">
                                    {{ $day->orders_count }} orders
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white text-right">
                                    {{ number_format($day->revenue ?? 0) }}៛
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <x-empty-state title="No data for the past 7 days" description="Completed transactions will generate daily analytics." />
        @endif
    </div>
</x-admin-layout>
