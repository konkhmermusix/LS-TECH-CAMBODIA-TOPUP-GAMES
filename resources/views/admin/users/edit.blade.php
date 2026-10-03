<x-admin-layout title="Edit {{ $user->name }}">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl glass-card text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <span>Edit Admin: {{ $user->name }}</span>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-input
                    label="Full Name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    :error="$errors->first('name')"
                />

                <x-input
                    label="Email Address"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    :error="$errors->first('email')"
                />

                <x-input
                    label="Password (Optional)"
                    name="password"
                    type="password"
                    placeholder="Leave blank to keep unchanged"
                    :error="$errors->first('password')"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-select label="Role" name="role" required>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="support" {{ old('role', $user->role) === 'support' ? 'selected' : '' }}>Support</option>
                    </x-select>

                    <x-select label="Status" name="status" required>
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </x-select>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-white/5">
                    @if($user->id !== auth()->id())
                        <button type="button" onclick="if(confirm('Delete user {{ $user->name }}?')) { document.getElementById('delete-user-form').submit(); }" class="text-xs font-bold text-rose-500 hover:underline">
                            Delete User
                        </button>
                    @else
                        <div></div>
                    @endif

                    <div class="flex gap-3">
                        <a href="{{ route('admin.users.index') }}">
                            <x-button variant="ghost">Cancel</x-button>
                        </a>
                        <x-button type="submit" variant="primary">Save Changes</x-button>
                    </div>
                </div>
            </form>

            @if($user->id !== auth()->id())
                <form id="delete-user-form" method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
</x-admin-layout>
