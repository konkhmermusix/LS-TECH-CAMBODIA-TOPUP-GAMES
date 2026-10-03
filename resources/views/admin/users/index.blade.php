<x-admin-layout title="Admin Users">
    <x-slot name="header">
        Admin Users & Access
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xs text-slate-500 dark:text-slate-400">Manage administrator accounts and security permissions.</p>
        <a href="{{ route('admin.users.create') }}">
            <x-button variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Admin User</span>
            </x-button>
        </a>
    </div>

    <div class="rounded-3xl glass-card overflow-hidden border border-white/40 dark:border-white/10 shadow-sm mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/50 dark:bg-slate-900/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 dark:border-white/5">
                    <tr>
                        <th class="px-5 py-3.5">Name</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Last Login</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach($users as $user)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="ml-1 text-[10px] text-blue-500 font-normal">(You)</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-300 font-mono">
                                {{ $user->email }}
                            </td>
                            <td class="px-5 py-3.5 capitalize text-xs font-semibold">
                                <span class="px-2 py-0.5 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($user->status === 'active')
                                    <x-badge color="emerald" :dot="true">Active</x-badge>
                                @else
                                    <x-badge color="rose" :dot="true">Inactive</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-400">
                                {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 rounded-xl glass-card text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-blue-500 transition-colors inline-block">
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
        {{ $users->links() }}
    </div>
</x-admin-layout>
