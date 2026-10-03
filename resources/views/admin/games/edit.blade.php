<x-admin-layout title="Edit {{ $game->name }}">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.games.index') }}" class="p-2 rounded-xl glass-card text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <span>Edit Game: {{ $game->name }}</span>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
            <form method="POST" action="{{ route('admin.games.update', $game->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Game Title"
                        name="name"
                        value="{{ old('name', $game->name) }}"
                        required
                        :error="$errors->first('name')"
                    />

                    <x-input
                        label="URL Slug"
                        name="slug"
                        value="{{ old('slug', $game->slug) }}"
                        required
                        :error="$errors->first('slug')"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input
                        label="Game Publisher"
                        name="publisher"
                        value="{{ old('publisher', $game->publisher) }}"
                        required
                        :error="$errors->first('publisher')"
                    />

                    <x-input
                        label="Sort Order"
                        name="sort_order"
                        type="number"
                        value="{{ old('sort_order', $game->sort_order) }}"
                    />
                </div>

                <x-input
                    label="Logo Image URL"
                    name="logo"
                    value="{{ old('logo', $game->logo) }}"
                    :error="$errors->first('logo')"
                />

                <x-input
                    label="Banner Image URL"
                    name="banner"
                    value="{{ old('banner', $game->banner) }}"
                    :error="$errors->first('banner')"
                />

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Game Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-2xl text-sm px-4 py-2.5 glass-input border border-gray-200 dark:border-white/10">{{ old('description', $game->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Player ID Instruction</label>
                    <textarea name="instruction" rows="3" class="w-full rounded-2xl text-sm px-4 py-2.5 glass-input border border-gray-200 dark:border-white/10">{{ old('instruction', $game->instruction) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        Dynamic Input Fields (JSON Schema) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="input_fields" rows="5" class="w-full font-mono text-xs rounded-2xl px-4 py-2.5 glass-input border border-gray-200 dark:border-white/10" required>{{ old('input_fields', json_encode($game->input_fields, JSON_PRETTY_PRINT)) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $game->is_active) ? 'checked' : '' }} class="rounded-lg text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">Active and visible to customers</label>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-white/5">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this game?')) { document.getElementById('delete-game-form').submit(); }" class="text-xs font-bold text-rose-500 hover:underline">
                        Delete Game
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.games.index') }}">
                            <x-button variant="ghost">Cancel</x-button>
                        </a>
                        <x-button type="submit" variant="primary">Save Changes</x-button>
                    </div>
                </div>
            </form>

            <form id="delete-game-form" method="POST" action="{{ route('admin.games.destroy', $game->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-admin-layout>
