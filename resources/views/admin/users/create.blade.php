<x-admin-layout title="Create Admin User">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl glass-card text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <span>Create Admin User</span>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <x-input
                    label="Full Name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="e.g. John Doe"
                    :error="$errors->first('name')"
                />

                <x-input
                    label="Email Address"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="john@example.com"
                    :error="$errors->first('email')"
                />

                <x-input
                    label="Password"
                    name="password"
                    type="password"
                    required
                    placeholder="Minimum 8 characters"
                    :error="$errors->first('password')"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-select label="Role" name="role" required>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="support" {{ old('role') === 'support' ? 'selected' : '' }}>Support</option>
                    </x-select>

                    <x-select label="Status" name="status" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </x-select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-white/5">
                    <a href="{{ route('admin.users.index') }}">
                        <x-button variant="ghost">Cancel</x-button>
                    </a>
                    <x-button type="submit" variant="primary">Create User</x-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
