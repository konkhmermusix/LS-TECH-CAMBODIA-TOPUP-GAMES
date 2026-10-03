<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Admin Login | {{ config('app.name', 'LS Tech TopUp') }}</title>

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
</head>
<body class="h-full bg-slate-100 dark:bg-[#070b14] text-slate-800 dark:text-slate-100 flex items-center justify-center p-4 selection:bg-blue-500 selection:text-white relative overflow-hidden">

    <!-- Background Orbs -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="absolute -top-[10%] -left-[10%] w-[45vw] h-[45vw] rounded-full bg-blue-500/15 dark:bg-blue-600/20 blur-[130px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[45vw] h-[45vw] rounded-full bg-indigo-500/15 dark:bg-indigo-600/20 blur-[130px]"></div>
    </div>

    <!-- Theme Switcher Top-Right -->
    <div class="absolute top-6 right-6">
        <x-theme-toggle />
    </div>

    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto mb-4 rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-xl shadow-blue-500/30">
                <img src="../images/logo.jpg" class="w-full h-full object-cover rounded-full" alt="">
                <!-- <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> -->
            </div>
            <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">LS Tech Administration</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sign in with authorized administrator credentials</p>
        </div>

        <!-- Glass Card Form -->
        <div class="glass-panel rounded-3xl border border-white/60 dark:border-white/10 shadow-2xl p-8 backdrop-blur-2xl">
            @if(session('success'))
                <x-alert type="success" class="mb-5">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if(session('error'))
                <x-alert type="error" class="mb-5">
                    {{ session('error') }}
                </x-alert>
            @endif

            @if($errors->any())
                <x-alert type="error" class="mb-5">
                    {{ $errors->first() }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf

                <x-input
                    label="Email"
                    name="email"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="admin@example.com"
                    :error="$errors->first('email')"
                    icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>'
                />

                <div x-data="{ show: false }">
                    <x-input
                        label="Password"
                        name="password"
                        x-bind:type="show ? 'text' : 'password'"
                        required
                        placeholder="••••••••••••"
                        :error="$errors->first('password')"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>'
                    />
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600 dark:text-slate-300 font-medium">
                        <input type="checkbox" name="remember" class="rounded-lg border-gray-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500">
                        <span>Remember session</span>
                    </label>

                    <a href="{{ route('home') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                        &larr; Back to Store
                    </a>
                </div>

                <div class="pt-2">
                    <x-button type="submit" variant="primary" size="lg" class="w-full shadow-lg shadow-blue-500/25">
                        Sign In
                    </x-button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
