<x-admin-layout title="System Settings">
    <x-slot name="header">
        System Settings & Integrations
    </x-slot>

    <div class="max-w-4xl space-y-8">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <!-- General Settings -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10 mb-8">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>General Website Configuration</span>
                </h3>
                <p class="text-xs text-slate-400 mb-6">Store brand, Telegram support hotline, and currency exchange.</p>

                <div class="space-y-4">
                    @foreach($settings['general'] ?? [] as $s)
                        <div>
                            <x-input
                                label="{{ ucwords(str_replace('_', ' ', $s->key)) }}"
                                name="{{ $s->key }}"
                                value="{{ $s->value }}"
                                help="{{ $s->description }}"
                            />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ABA KHQR Gateway Settings -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10 mb-8">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>ABA KHQR (PayWay) Integration</span>
                </h3>
                <p class="text-xs text-slate-400 mb-6">Payment parameters. Note: Secret API keys are safely managed in your server <code class="bg-black/10 dark:bg-white/10 px-1 py-0.5 rounded">.env</code> file.</p>

                <div class="space-y-4">
                    @foreach($settings['aba'] ?? [] as $s)
                        <div>
                            <x-input
                                label="{{ ucwords(str_replace('_', ' ', $s->key)) }}"
                                name="{{ $s->key }}"
                                value="{{ $s->value }}"
                                help="{{ $s->description }}"
                            />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top-Up Provider Settings -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/40 dark:border-white/10 mb-8">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Authorized Top-Up Engine</span>
                </h3>
                <p class="text-xs text-slate-400 mb-6">Switch between Sandbox Simulated Provider and Live Authorized Partner APIs.</p>

                <div class="space-y-4">
                    @foreach($settings['topup'] ?? [] as $s)
                        <div>
                            <x-input
                                label="{{ ucwords(str_replace('_', ' ', $s->key)) }}"
                                name="{{ $s->key }}"
                                value="{{ $s->value }}"
                                help="{{ $s->description }}"
                            />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <x-button type="submit" variant="primary" size="lg">
                    Save System Settings
                </x-button>
            </div>
        </form>
    </div>
</x-admin-layout>
