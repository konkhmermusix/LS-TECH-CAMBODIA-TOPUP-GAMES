<x-admin-layout title="Edit {{ $package->name }}">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.packages.index', ['game_id' => $package->game_id]) }}" class="p-2 rounded-xl glass-card text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <span>Edit Package: {{ $package->name }}</span>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
            <form method="POST" action="{{ route('admin.packages.update', $package->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-select label="Game" name="game_id" required :error="$errors->first('game_id')">
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id', $package->game_id) == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </x-select>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Package Name"
                        name="name"
                        value="{{ old('name', $package->name) }}"
                        required
                        :error="$errors->first('name')"
                    />

                    <x-input
                        label="Provider SKU Code"
                        name="provider_code"
                        value="{{ old('provider_code', $package->provider_code) }}"
                        required
                        :error="$errors->first('provider_code')"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Diamonds / Base Value"
                        name="amount_value"
                        type="number"
                        value="{{ old('amount_value', $package->amount_value) }}"
                        required
                        :error="$errors->first('amount_value')"
                    />

                    <x-input
                        label="Bonus Value (Optional)"
                        name="bonus_value"
                        type="number"
                        value="{{ old('bonus_value', $package->bonus_value) }}"
                        :error="$errors->first('bonus_value')"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Price in KHR (៛)"
                        name="price_khr"
                        type="number"
                        step="100"
                        value="{{ old('price_khr', $package->price_khr) }}"
                        required
                        :error="$errors->first('price_khr')"
                    />

                    <x-input
                        label="Price in USD ($)"
                        name="price_usd"
                        type="number"
                        step="0.01"
                        value="{{ old('price_usd', $package->price_usd) }}"
                        required
                        :error="$errors->first('price_usd')"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Badge Tag (Optional)"
                        name="badge"
                        value="{{ old('badge', $package->badge) }}"
                    />

                    <x-input
                        label="Sort Order"
                        name="sort_order"
                        type="number"
                        value="{{ old('sort_order', $package->sort_order) }}"
                    />
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="rounded-lg text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">Active and available for checkout</label>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-white/5">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this package?')) { document.getElementById('delete-package-form').submit(); }" class="text-xs font-bold text-rose-500 hover:underline">
                        Delete Package
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.packages.index', ['game_id' => $package->game_id]) }}">
                            <x-button variant="ghost">Cancel</x-button>
                        </a>
                        <x-button type="submit" variant="primary">Save Changes</x-button>
                    </div>
                </div>
            </form>

            <form id="delete-package-form" method="POST" action="{{ route('admin.packages.destroy', $package->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-admin-layout>
