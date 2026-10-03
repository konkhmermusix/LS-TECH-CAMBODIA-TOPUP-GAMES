<x-admin-layout title="Profile Settings">
    <x-slot name="header">
        Profile Settings
    </x-slot>

    <div class="max-w-xl">
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
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

                <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Change Password (Optional)</h4>

                    <x-input
                        label="Current Password"
                        name="current_password"
                        type="password"
                        placeholder="••••••••••••"
                        :error="$errors->first('current_password')"
                    />

                    <x-input
                        label="New Password"
                        name="new_password"
                        type="password"
                        placeholder="Minimum 8 characters"
                        :error="$errors->first('new_password')"
                    />

                    <x-input
                        label="Confirm New Password"
                        name="new_password_confirmation"
                        type="password"
                        placeholder="Re-enter new password"
                    />
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-white/5">
                    <x-button type="submit" variant="primary">
                        Save Profile Changes
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
