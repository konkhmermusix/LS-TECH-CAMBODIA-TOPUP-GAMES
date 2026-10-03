<x-admin-layout title="Promotions & Discounts">
    <x-slot name="header">
        Promotions & Vouchers
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xs text-slate-500 dark:text-slate-400">Manage promotional discount codes and banners.</p>
        <a href="{{ route('admin.promotions.create') }}">
            <x-button variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>New Promotion</span>
            </x-button>
        </a>
    </div>

    @if($promotions->count() > 0)
        <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-5 py-3.5">Title</th>
                            <th class="px-5 py-3.5">Voucher Code</th>
                            <th class="px-5 py-3.5">Discount</th>
                            <th class="px-5 py-3.5">Min Spend</th>
                            <th class="px-5 py-3.5">Usage</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($promotions as $promo)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ $promo->title }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($promo->code)
                                        <span class="px-2.5 py-1 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 font-mono font-bold text-xs border border-blue-500/20">
                                            {{ $promo->code }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">Banner Promo (No code)</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-emerald-500">
                                    @if($promo->discount_type === 'percentage')
                                        {{ $promo->discount_value }}% Off
                                    @else
                                        {{ number_format($promo->discount_value) }}៛ Off
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    {{ number_format($promo->min_spend) }}៛
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    {{ $promo->usage_count }} / {{ $promo->usage_limit ?? 'Unlimited' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($promo->is_active)
                                        <x-badge color="emerald" :dot="true">Active</x-badge>
                                    @else
                                        <x-badge color="gray" :dot="true">Inactive</x-badge>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.promotions.edit', $promo->id) }}" class="px-3 py-1 rounded-xl glass-card text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-blue-500 transition-colors inline-block">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $promotions->links() }}
        </div>
    @else
        <x-empty-state title="No promotions yet" description="Create promo discount codes or special banners for your store." />
    @endif
</x-admin-layout>
