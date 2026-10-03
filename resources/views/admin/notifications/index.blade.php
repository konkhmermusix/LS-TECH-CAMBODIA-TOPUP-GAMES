<x-admin-layout title="System Notifications">
    <x-slot name="header">
        System Notifications
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-xs text-slate-500 dark:text-slate-400">Important system alerts and automated event logs.</p>
        @if($notifications->where('is_read', false)->count() > 0)
            <form method="POST" action="{{ route('admin.notifications.read_all') }}">
                @csrf
                <x-button type="submit" variant="glass" size="sm">
                    Mark All as Read
                </x-button>
            </form>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $n)
                <div class="glass-card rounded-2xl p-5 border border-white/40 dark:border-white/10 flex items-start justify-between gap-4 {{ !$n->is_read ? 'border-l-4 border-l-blue-500' : 'opacity-75' }}">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $n->title }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $n->message }}</p>
                            <span class="text-[10px] text-slate-400 mt-2 block">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    @if(!$n->is_read)
                        <form method="POST" action="{{ route('admin.notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="px-2.5 py-1 text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                Mark read
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <x-empty-state title="No notifications" description="System notifications and automated provider notices will appear here." />
    @endif
</x-admin-layout>
