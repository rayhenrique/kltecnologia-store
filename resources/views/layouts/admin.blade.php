<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' | ' : '' }}Painel Admin - {{ config('app.name', 'KL Tecnologia') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&family=ibm-plex-mono:500,600&family=sora:600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-900 selection:bg-teal-500 selection:text-white" x-data="{ sidebarOpen: false }">
    <div>
        {{-- Off-canvas Mobile Sidebar Overlay --}}
        <div 
            x-show="sidebarOpen" 
            x-cloak 
            class="relative z-50 lg:hidden" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div 
                x-show="sidebarOpen" 
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs" 
                @click="sidebarOpen = false"
            ></div>

            <div class="fixed inset-0 flex">
                <div 
                    x-show="sidebarOpen" 
                    x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    class="relative mr-16 flex w-full max-w-xs flex-1"
                >
                    {{-- Mobile Sidebar Content --}}
                    <div class="flex flex-col flex-1 bg-slate-950 border-r border-slate-800 text-slate-300">
                        @include('layouts.partials.admin-sidebar')
                    </div>
                </div>
            </div>
        </div>

        {{-- Desktop Static Sidebar --}}
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-64 lg:flex-col">
            <div class="flex flex-col flex-1 bg-slate-950 border-r border-slate-800 text-slate-300 shadow-2xl">
                @include('layouts.partials.admin-sidebar')
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="lg:pl-64 flex flex-col min-h-screen">
            {{-- Topbar on Main Area --}}
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/95 px-4 sm:px-6 lg:px-8 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    {{-- Mobile Hamburger --}}
                    <button 
                        type="button" 
                        class="lg:hidden -m-2.5 p-2.5 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition"
                        @click="sidebarOpen = true"
                    >
                        <span class="sr-only">Abrir menu lateral</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 font-mono text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Admin Conectado
                        </span>
                    </div>
                </div>

                {{-- Header Quick Actions --}}
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <a 
                        href="{{ route('admin.products.create') }}" 
                        class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-3.5 py-1.5 text-xs font-bold text-white shadow-sm shadow-teal-600/20 transition"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Novo</span> Produto
                    </a>

                    <a 
                        href="{{ route('admin.posts.create') }}" 
                        class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 px-3.5 py-1.5 text-xs font-bold text-white shadow-sm transition"
                    >
                        <svg class="h-3.5 w-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="hidden sm:inline">Novo</span> Artigo
                    </a>

                    <a 
                        href="{{ route('storefront.index') }}" 
                        target="_blank"
                        class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition"
                        title="Abrir loja em nova aba"
                    >
                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span class="hidden md:inline">Loja</span>
                    </a>
                </div>
            </header>

            {{-- Optional Page Header Banner --}}
            @isset($header)
                <div class="border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8 py-5">
                    {{ $header }}
                </div>
            @endisset

            {{-- Flash Messages --}}
            <div class="px-4 sm:px-6 lg:px-8 mt-4">
                <x-flash />
            </div>

            {{-- Main Body --}}
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>

            {{-- Admin Footer --}}
            <footer class="border-t border-slate-200 bg-white py-4 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    <span class="font-bold text-slate-800">KL Tecnologia</span> — Painel Administrativo de E-commerce
                </div>
                <div class="font-mono text-[11px] text-slate-400">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
