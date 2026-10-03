<x-admin-layout title="Edit Promotion">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.promotions.index') }}" class="p-2 rounded-xl glass-card text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <span>Edit Promotion: {{ $promotion->title }}</span>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
            <form method="POST" action="{{ route('admin.promotions.update', $promotion->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-input
                    label="Promotion Title"
                    name="title"
                    value="{{ old('title', $promotion->title) }}"
                    required
                    :error="$errors->first('title')"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Voucher Code (Optional)"
                        name="code"
                        value="{{ old('code', $promotion->code) }}"
                        :error="$errors->first('code')"
                    />

                    <x-select label="Discount Type" name="discount_type" required>
                        <option value="fixed" {{ old('discount_type', $promotion->discount_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (៛)</option>
                        <option value="percentage" {{ old('discount_type', $promotion->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    </x-select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Discount Value"
                        name="discount_value"
                        type="number"
                        step="0.01"
                        value="{{ old('discount_value', $promotion->discount_value) }}"
                        required
                        :error="$errors->first('discount_value')"
                    />

                    <x-input
                        label="Minimum Spend (៛)"
                        name="min_spend"
                        type="number"
                        step="100"
                        value="{{ old('min_spend', $promotion->min_spend) }}"
                        :error="$errors->first('min_spend')"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Maximum Discount Cap (៛)"
                        name="max_discount"
                        type="number"
                        step="100"
                        value="{{ old('max_discount', $promotion->max_discount) }}"
                    />

                    <x-input
                        label="Usage Limit"
                        name="usage_limit"
                        type="number"
                        value="{{ old('usage_limit', $promotion->usage_limit) }}"
                    />
                </div>

                <x-input
                    label="Banner Image URL"
                    name="banner"
                    value="{{ old('banner', $promotion->banner) }}"
                />

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }} class="rounded-lg text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">Active</label>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-white/5">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this promotion?')) { document.getElementById('delete-promo-form').submit(); }" class="text-xs font-bold text-rose-500 hover:underline">
                        Delete Promotion
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.promotions.index') }}">
                            <x-button variant="ghost">Cancel</x-button>
                        </a>
                        <x-button type="submit" variant="primary">Save Changes</x-button>
                    </div>
                </div>
            </form>

            <form id="delete-promo-form" method="POST" action="{{ route('admin.promotions.destroy', $promotion->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-admin-layout>
