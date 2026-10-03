<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'LS TECH TOPUP GAME' }}</title>
    <meta name="description" content="{{ $description ?? 'Official instant Free Fire and Mobile Legends diamond top-up in Cambodia with ABA KHQR payment. Fast, automated, 100% secure.' }}">

    <!-- Open Graph / SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'LS TECH TOPUP GAME' }}">
    <meta property="og:description" content="Instant Free Fire & Mobile Legends Diamonds with ABA KHQR. Fast delivery.">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts: Google Sans & Kantumruy Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600;700&family=Google+Sans+Text:wght@400;500;600;700&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bulletproof Theme Initializer & Controller -->
    <script>
        (function() {
            function getSystemDark() {
                return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            function applyTheme(mode) {
                const isDark = mode === 'dark' || (mode === 'system' && getSystemDark());
                const root = document.documentElement;
                if (isDark) {
                    root.classList.add('dark');
                    root.setAttribute('data-theme', 'dark');
                    root.style.colorScheme = 'dark';
                } else {
                    root.classList.remove('dark');
                    root.setAttribute('data-theme', 'light');
                    root.style.colorScheme = 'light';
                }
                updateThemeButtons(mode);
            }
            function updateThemeButtons(mode) {
                document.querySelectorAll('[data-theme-btn]').forEach(function(btn) {
                    const btnMode = btn.getAttribute('data-theme-btn');
                    if (btnMode === mode) {
                        btn.classList.add('bg-white', 'text-blue-500', 'shadow-sm', 'dark:bg-slate-700', 'dark:text-blue-400');
                        btn.classList.remove('text-gray-400', 'text-slate-400');
                    } else {
                        btn.classList.remove('bg-white', 'text-blue-500', 'shadow-sm', 'dark:bg-slate-700', 'dark:text-blue-400');
                        btn.classList.add('text-gray-400');
                    }
                });
            }

            const currentMode = localStorage.getItem('theme_mode') || 'system';
            applyTheme(currentMode);

            window.setTheme = function(mode) {
                localStorage.setItem('theme_mode', mode);
                applyTheme(mode);
                if (window.Alpine && window.Alpine.store && window.Alpine.store('theme')) {
                    window.Alpine.store('theme').mode = mode;
                }
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { mode: mode } }));
            };

            window.addEventListener('DOMContentLoaded', function() {
                updateThemeButtons(localStorage.getItem('theme_mode') || 'system');
            });

            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
                    const active = localStorage.getItem('theme_mode') || 'system';
                    if (active === 'system') {
                        applyTheme('system');
                    }
                });
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- QRCode JS CDN for ultra-fast, offline-friendly client rendering of ABA KHQR payload -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    @stack('styles')
</head>
<body class="h-full bg-slate-50 dark:bg-[#0b0f19] text-slate-800 dark:text-slate-100 font-sans antialiased flex flex-col selection:bg-blue-500 selection:text-white pb-20 md:pb-0">
    <!-- Ambient iOS Liquid Glass Background Accents -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-blue-400/10 dark:bg-blue-600/10 blur-[120px]"></div>
        <div class="absolute top-[40%] -right-[10%] w-[45vw] h-[45vw] rounded-full bg-emerald-400/10 dark:bg-emerald-600/10 blur-[130px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[40vw] h-[40vw] rounded-full bg-purple-400/10 dark:bg-indigo-600/10 blur-[140px]"></div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 w-full glass-panel border-b border-white/40 dark:border-white/10 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group focus:outline-none">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl  from-blue-600 to-cyan-400 flex items-center justify-center text-white shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/logo.jpg') }}" alt="LS Tech" class="w-10 h-10">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg sm:text-xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 dark:from-blue-400 dark:via-indigo-300 dark:to-cyan-300 bg-clip-text text-transparent">
                        LS TECH CAMBODIA
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 -mt-1">GAME TOP-UP</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-1 bg-black/5 dark:bg-white/5 p-1 rounded-2xl border border-white/20 dark:border-white/5">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Home
                </a>
                <a href="{{ route('games.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('games.*') ? 'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Games
                </a>
                <a href="{{ route('orders.track') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('orders.track') ? 'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Track Order
                </a>
                <a href="{{ route('faq') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('faq') ? 'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Help & FAQ
                </a>
            </nav>

            <!-- Right Controls: Theme Toggle & Support -->
            <div class="flex items-center gap-2 sm:gap-3">
                <x-theme-toggle />

                <a href="https://t.me/lstechcambodiagroup" target="_blank" rel="noopener noreferrer" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl glass-card text-xs font-bold text-blue-600 dark:text-blue-400 hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.52 2.77-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                    <span>Telegram</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-12 glass-panel border-t border-white/40 dark:border-white/10 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
               <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group focus:outline-none">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl  from-blue-600 to-cyan-400 flex items-center justify-center text-white shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('images/logo.jpg') }}" alt="LS Tech" class="w-10 h-10">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg sm:text-xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 dark:from-blue-400 dark:via-indigo-300 dark:to-cyan-300 bg-clip-text text-transparent">
                            LS TECH CAMBODIA
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 -mt-1">GAME TOP-UP</span>
                    </div>
                </a>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-md">
                    Fast and verified game top-up for Free Fire & Mobile Legends with automatic ABA KHQR payment verification.
                </p>
            </div>

            <div class="flex items-center gap-6 text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('faq') }}" class="hover:text-blue-500 transition-colors">FAQ & Support</a>
                <a href="{{ route('orders.track') }}" class="hover:text-blue-500 transition-colors">Track Order</a>
            </div>

            <div class="text-xs text-slate-400 text-center md:text-right">
                &copy; {{ date('Y') }} LS Tech Cambodia. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Bar (iOS Inspired) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 glass-panel border-t border-white/40 dark:border-white/10 px-3 py-2 safe-pb">
        <div class="flex items-center justify-around">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px]">Home</span>
            </a>
            <a href="{{ route('games.index') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('games.*') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/></svg>
                <span class="text-[10px]">Games</span>
            </a>
            <a href="{{ route('orders.track') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('orders.track') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span class="text-[10px]">Track Order</span>
            </a>
            <a href="{{ route('faq') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('faq') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[10px]">Help</span>
            </a>
        </div>
    </nav>

    <!-- Global Components -->
    <x-toast />
    <x-scroll-to-top />

    @stack('scripts')
</body>
</html>
