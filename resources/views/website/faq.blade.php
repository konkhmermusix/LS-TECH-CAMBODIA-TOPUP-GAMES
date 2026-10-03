<x-app-layout title="Help Center & Frequently Asked Questions — LS Tech TopUp">

    <div class="max-w-3xl mx-auto py-8 sm:py-12">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Help Center & FAQ</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2">
                Everything you need to know about Free Fire & Mobile Legends instant top-ups in Cambodia.
            </p>
        </div>

        <!-- How to Find UID Visual Guide Card -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/50 dark:border-white/10 shadow-xl mb-10">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>How to Find Your Player ID</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-slate-600 dark:text-slate-300">
                <div class="p-4 rounded-2xl bg-black/5 dark:bg-white/5 space-y-2">
                    <span class="font-bold text-sm text-slate-900 dark:text-white block">Free Fire</span>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-500 dark:text-slate-400">
                        <li>Launch Free Fire and stay on the main lobby screen.</li>
                        <li>Tap your profile avatar icon in the <strong>top-left corner</strong>.</li>
                        <li>Your <strong>Player UID (8-10 digits)</strong> is displayed under your nickname.</li>
                        <li>Tap the copy button next to your UID.</li>
                    </ol>
                </div>

                <div class="p-4 rounded-2xl bg-black/5 dark:bg-white/5 space-y-2">
                    <span class="font-bold text-sm text-slate-900 dark:text-white block">Mobile Legends: Bang Bang</span>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-500 dark:text-slate-400">
                        <li>Open MLBB and tap your profile avatar in the <strong>top-left corner</strong>.</li>
                        <li>Go to the <strong>"Basic Info"</strong> tab.</li>
                        <li>You will see: <strong>User ID: 12345678 (2024)</strong>.</li>
                        <li><strong>12345678</strong> is your User ID, and <strong>2024</strong> is your Zone ID.</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion with Alpine.js -->
        <div class="space-y-4 mb-12" x-data="{ active: null }">
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-4">Frequently Asked Questions</h2>

            @foreach($faqs as $index => $faq)
                <div class="glass-card rounded-2xl border border-white/40 dark:border-white/10 overflow-hidden">
                    <button
                        type="button"
                        @click="active === {{ $index }} ? active = null : active = {{ $index }}"
                        class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-bold text-sm text-slate-900 dark:text-white hover:text-blue-500 transition-colors focus:outline-none"
                    >
                        <span>{{ $faq['q'] }}</span>
                        <svg
                            class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0"
                            :class="{ 'rotate-180': active === {{ $index }} }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="active === {{ $index }}"
                        x-transition
                        class="px-6 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-gray-100 dark:border-white/5 pt-3"
                        style="display: none;"
                    >
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Support Callout Box -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 border border-white/50 dark:border-white/10 text-center shadow-xl">
            <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">Still need assistance?</h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">
                Our support team is ready 24/7 on Telegram to assist you with order inquiries, payment verification, and questions.
            </p>
            <a
                href="https://t.me/lstechcambodiagroup"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-lg shadow-blue-500/25 transition-transform hover:scale-105"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.52 2.77-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                <span>Contact @lstechcambodiagroup</span>
            </a>
        </div>
    </div>

</x-app-layout>
