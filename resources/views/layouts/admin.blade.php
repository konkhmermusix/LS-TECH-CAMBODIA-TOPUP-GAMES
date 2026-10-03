<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'Administration' }}</title>

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

    @stack('styles')
</head>
<body class="h-full bg-slate-100/70 dark:bg-[#080d1a] text-slate-800 dark:text-slate-100 font-sans antialiased flex selection:bg-blue-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
        @click="sidebarOpen = false"
        style="display: none;"
    ></div>

    <!-- Liquid Glass Admin Sidebar -->
    <aside
        :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen }"
        class="fixed inset-y-0 left-0 z-50 w-72 glass-panel border-r border-white/40 dark:border-white/10 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
    >
        <!-- Brand Header -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-gray-200/50 dark:border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <span class="font-extrabold text-base tracking-tight bg-gradient-to-r from-blue-600 to-indigo-500 dark:from-blue-400 dark:to-indigo-300 bg-clip-text text-transparent">
                        LS TECH ADMIN
                    </span>
                    <p class="text-[10px] uppercase font-bold text-gray-400 -mt-0.5">Top-Up Management</p>
                </div>
            </a>

            <button type="button" @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
            <!-- Main Section -->
            <div>
                <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Orders</span>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>ABA Payments</span>
                    </a>
                    <a href="{{ route('admin.topups.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.topups.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Top-Up Logs</span>
                    </a>
                </div>
            </div>

            <!-- Game Management -->
            <div>
                <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Inventory</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.games.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.games.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/></svg>
                        <span>Games</span>
                    </a>
                    <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.packages.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Packages</span>
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.promotions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Promotions</span>
                    </a>
                </div>
            </div>

            <!-- Analytics & Users -->
            <div>
                <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">System</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Buyers / Customers</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>Notifications</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Admin Users</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile Pill in Sidebar Footer -->
        <div class="p-4 border-t border-gray-200/50 dark:border-white/10">
            <div class="flex items-center justify-between p-2 rounded-2xl bg-black/5 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role ?? 'Admin' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-500/10 transition-colors" title="Sign Out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top App Bar -->
        <header class="h-20 glass-panel border-b border-white/40 dark:border-white/10 px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button type="button" @click="sidebarOpen = true" class="lg:hidden p-2 rounded-2xl text-gray-500 hover:bg-black/5 dark:hover:bg-white/5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $header ?? $title ?? 'Dashboard' }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl glass-card text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-blue-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Store</span>
                </a>

                <x-theme-toggle />

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="flex items-center gap-2 p-1 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors focus:outline-none">
                            <div class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-white/5">
                            <p class="text-xs font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-blue-500/10 hover:text-blue-500">
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-500/10">
                                Sign Out
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <!-- Page Alerts -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 pt-6 w-full">
            @if(session('success'))
                <x-alert type="success" :dismissible="true" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if(session('error'))
                <x-alert type="error" :dismissible="true" class="mb-4">
                    {{ session('error') }}
                </x-alert>
            @endif
        </div>

        <!-- Main Admin Content Area -->
        <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-8 py-6 w-full">
            {{ $slot }}
        </main>
    </div>

    <!-- Global Toast & Modals -->
    <x-toast />
    @stack('scripts')
</body>
</html>
