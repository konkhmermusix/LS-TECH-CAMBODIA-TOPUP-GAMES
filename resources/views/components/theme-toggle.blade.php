<div class="relative inline-flex items-center p-1 rounded-2xl glass-panel border border-white/40 dark:border-white/10 shadow-inner">
    {{-- Light Mode --}}
    <button
        type="button"
        onclick="window.setTheme('light')"
        data-theme-btn="light"
        class="p-1.5 rounded-xl transition-all duration-200 focus:outline-none text-gray-400 hover:text-amber-500 cursor-pointer"
        title="Light Mode"
        aria-label="Light Mode"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
        </svg>
    </button>

    {{-- System Mode --}}
    <button
        type="button"
        onclick="window.setTheme('system')"
        data-theme-btn="system"
        class="p-1.5 rounded-xl transition-all duration-200 focus:outline-none text-gray-400 hover:text-blue-500 cursor-pointer"
        title="System Auto"
        aria-label="System Mode"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
    </button>

    {{-- Dark Mode --}}
    <button
        type="button"
        onclick="window.setTheme('dark')"
        data-theme-btn="dark"
        class="p-1.5 rounded-xl transition-all duration-200 focus:outline-none text-gray-400 hover:text-indigo-400 cursor-pointer"
        title="Dark Mode"
        aria-label="Dark Mode"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>
