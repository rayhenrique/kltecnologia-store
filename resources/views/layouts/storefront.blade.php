@props([
    'title' => null,
    'metaDescription' => 'Plataforma oficial de produtos digitais, scripts, sistemas SaaS, automações e templates prontos para acelerar seu negócio na KL Tecnologia.',
    'ogImage' => null,
    'ogType' => 'website',
    'canonical' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($title) ? $title.' | ' : '' }}{{ config('app.name', 'KL Tecnologia') }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    {{-- Open Graph & Twitter Card --}}
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ !empty($title) ? $title.' | ' : '' }}{{ config('app.name', 'KL Tecnologia') }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/logo-kltecnologia.png') }}">
    <meta property="og:site_name" content="KL Tecnologia">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ !empty($title) ? $title.' | ' : '' }}{{ config('app.name', 'KL Tecnologia') }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/logo-kltecnologia.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/logo-kltecnologia.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-kltecnologia.png') }}">

    {{-- Preload High-Priority Self-Hosted Fonts --}}
    <link rel="preload" href="{{ asset('fonts/dm-sans-400.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/sora-700.woff2') }}" as="font" type="font/woff2" crossorigin>

    @production
        <style>{!! Vite::content('resources/css/app.css') !!}</style>
        @vite(['resources/js/app.js'])
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endproduction

    {{-- Global Structured Data (Organization & WebSite with SearchAction) --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '#organization',
                'name' => 'KL Tecnologia',
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    '@id' => url('/') . '#logo',
                    'url' => asset('images/logo-kltecnologia.png'),
                    'caption' => 'KL Tecnologia',
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#website',
                'url' => url('/'),
                'name' => 'KL Tecnologia',
                'publisher' => [
                    '@id' => url('/') . '#organization',
                ],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => route('catalog.index') . '?q={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @stack('schema')
</head>
<body 
    class="font-sans antialiased text-slate-900 bg-slate-50"
    x-data="{
        searchOpen: false,
        mobileMenuOpen: false,
        favoritesCount: 0,
        cartCount: 0,
        toast: {
            show: false,
            message: '',
            cartUrl: '',
            timeout: null
        },
        init() {
            try {
                const favs = JSON.parse(localStorage.getItem('kl_favorites') || '[]');
                this.favoritesCount = Array.isArray(favs) ? favs.length : 0;
                const cart = JSON.parse(localStorage.getItem('kl_cart') || '[]');
                this.cartCount = Array.isArray(cart) ? cart.length : 0;
            } catch(e) {}
            window.addEventListener('favorites-updated', (e) => {
                this.favoritesCount = e.detail?.count ?? 0;
            });
            window.addEventListener('cart-updated', (e) => {
                this.cartCount = e.detail?.count ?? 0;
            });
            window.addEventListener('toast-message', (e) => {
                clearTimeout(this.toast.timeout);
                this.toast.show = true;
                this.toast.message = e.detail?.message || '';
                this.toast.cartUrl = e.detail?.cartUrl || '';
                this.toast.timeout = setTimeout(() => {
                    this.toast.show = false;
                }, 4000);
            });
        }
    }"
    x-on:keydown.window.prevent.cmd.k="searchOpen = true; $nextTick(() => $refs.modalSearchInput?.focus())"
    x-on:keydown.window.prevent.ctrl.k="searchOpen = true; $nextTick(() => $refs.modalSearchInput?.focus())"
    x-on:keydown.window.escape="searchOpen = false; mobileMenuOpen = false"
>
    <a href="#conteudo" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2">Ir para o conteúdo</a>
    
    {{-- Header --}}
    <header class="sticky top-0 z-40 border-b border-slate-800 bg-slate-950/95 backdrop-blur-md">
        <div class="page-container flex min-h-20 items-center justify-between gap-2 sm:gap-4 py-3">
            {{-- Logo --}}
            <a href="{{ route('storefront.index') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0">
                <img 
                    src="{{ asset('images/logo-kltecnologia-80.webp') }}" 
                    srcset="{{ asset('images/logo-kltecnologia-80.webp') }} 1x, {{ asset('images/logo-kltecnologia.webp') }} 2x" 
                    alt="KL Tecnologia" 
                    width="40" 
                    height="40" 
                    fetchpriority="high" 
                    class="h-9 w-9 sm:h-10 sm:w-10 rounded-xl object-cover shadow-lg shadow-teal-500/20 group-hover:scale-105 transition duration-200" 
                />
                <span class="font-display text-base sm:text-lg font-bold tracking-tight text-white group-hover:text-teal-400 transition">
                    KL<span class="text-teal-400">Tecnologia</span>
                </span>
            </a>

            {{-- Navigation & Auth --}}
            <nav aria-label="Navegação principal" class="flex items-center gap-2 sm:gap-6">
                <div class="hidden lg:flex items-center gap-5 text-sm font-medium text-slate-300">
                    <a href="{{ route('storefront.index') }}#destaques" class="inline-flex items-center gap-1.5 hover:text-teal-400 transition group">
                        <svg class="h-4 w-4 text-amber-400 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                        </svg>
                        Destaques
                    </a>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 hover:text-teal-400 transition group {{ request()->routeIs('catalog.index') ? 'text-teal-400 font-semibold' : '' }}">
                        <svg class="h-4 w-4 text-teal-400/90 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Catálogo
                    </a>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 hover:text-teal-400 transition group {{ request()->routeIs('blog.*') ? 'text-teal-400 font-semibold' : '' }}">
                        <svg class="h-4 w-4 text-purple-400 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Blog
                    </a>
                    <a href="{{ route('storefront.index') }}#atualizacoes" class="inline-flex items-center gap-1.5 hover:text-teal-400 transition group">
                        <svg class="h-4 w-4 text-emerald-400 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Atualizações
                    </a>
                    <a href="{{ route('storefront.index') }}#vantagens" class="inline-flex items-center gap-1.5 hover:text-teal-400 transition group">
                        <svg class="h-4 w-4 text-blue-400 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Vantagens
                    </a>
                </div>

                <div class="flex items-center gap-1.5 sm:gap-3">
                    {{-- Botão de Busca (Lupa) --}}
                    <button 
                        type="button" 
                        @click="searchOpen = true; $nextTick(() => $refs.modalSearchInput?.focus())"
                        id="topbar-search-btn"
                        class="relative inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl border border-slate-800 bg-slate-900/80 text-slate-300 hover:border-teal-500/50 hover:bg-teal-500/10 hover:text-teal-400 transition-all duration-200 group shadow-xs cursor-pointer"
                        title="Buscar produtos (Ctrl+K)"
                        aria-label="Abrir pesquisa"
                    >
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    {{-- Ícone Favoritos --}}
                    <a 
                        href="{{ route('favorites.index') }}" 
                        id="topbar-favorites-link"
                        class="relative inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl border {{ request()->routeIs('favorites.index') ? 'border-pink-500 bg-pink-500/10 text-pink-400' : 'border-slate-800 bg-slate-900/80 text-slate-300' }} hover:border-pink-500/50 hover:bg-pink-500/10 hover:text-pink-400 transition-all duration-200 group shadow-xs"
                        title="Meus Favoritos"
                        aria-label="Ver produtos favoritos"
                    >
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span 
                            x-text="favoritesCount" 
                            class="absolute -top-1 -right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-pink-500 px-1 text-[10px] font-bold text-white shadow-xs"
                        >
                            0
                        </span>
                    </a>

                    {{-- Ícone Carrinho --}}
                    <a 
                        href="{{ route('cart.index') }}" 
                        id="topbar-cart-link"
                        class="relative inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl border {{ request()->routeIs('cart.index') ? 'border-teal-500 bg-teal-500/10 text-teal-400' : 'border-slate-800 bg-slate-900/80 text-slate-300' }} hover:border-teal-500/50 hover:bg-teal-500/10 hover:text-teal-400 transition-all duration-200 group shadow-xs"
                        title="Meu Carrinho"
                        aria-label="Ver carrinho de compras"
                    >
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span 
                            x-text="cartCount" 
                            class="absolute -top-1 -right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-teal-500 px-1 text-[10px] font-bold text-white shadow-xs"
                        >
                            0
                        </span>
                    </a>

                    <div class="h-6 w-px bg-slate-800 hidden sm:block mx-0.5"></div>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden md:inline-flex items-center gap-1.5 rounded-xl bg-slate-800 border border-slate-700 px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-200 hover:bg-slate-700 transition">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Painel</span>
                            </a>
                        @endif
                        <a href="{{ route('customer.downloads') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl bg-teal-700 hover:bg-teal-800 px-3.5 sm:px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-sm shadow-teal-700/20 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Downloads</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-300 hover:text-white px-2 py-1 transition">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Entrar</span>
                        </a>
                        <a href="{{ route('register') }}" class="hidden md:inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-blue-600 hover:from-teal-400 hover:to-blue-500 px-3.5 py-2 text-xs sm:text-sm font-bold text-white shadow-md shadow-teal-500/25 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Cadastre-se</span>
                        </a>
                    @endauth

                    {{-- Mobile Hamburger Trigger --}}
                    <button 
                        type="button" 
                        @click="mobileMenuOpen = true"
                        id="mobile-menu-toggle-btn"
                        class="lg:hidden relative inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl border border-slate-800 bg-slate-900/80 text-slate-300 hover:border-teal-500/50 hover:bg-teal-500/10 hover:text-teal-400 transition-all duration-200 group shadow-xs cursor-pointer"
                        aria-label="Abrir menu de navegação"
                    >
                        <svg class="h-5 w-5 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    {{-- Modal de Busca Global (Command Palette) --}}
    <div 
        x-show="searchOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-start justify-center p-4 pt-16 sm:pt-24"
        role="dialog" 
        aria-modal="true" 
        aria-labelledby="modal-search-title"
    >
        {{-- Backdrop click to close --}}
        <div class="fixed inset-0" @click="searchOpen = false"></div>

        {{-- Modal Content Card --}}
        <div 
            x-show="searchOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
            class="relative w-full max-w-2xl rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-teal-500/10 z-10"
            @click.stop
        >
            <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="rounded bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-400">
                            Buscar na Loja
                        </span>
                        <span class="text-xs text-slate-400 font-medium">
                            Encontre templates, scripts, sistemas e infoprodutos
                        </span>
                    </div>
                    <button 
                        type="button" 
                        @click="searchOpen = false"
                        class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-white transition cursor-pointer"
                        title="Fechar (ESC)"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                    <div class="relative flex items-center">
                        <input 
                            x-ref="modalSearchInput"
                            type="search" 
                            name="q" 
                            value="{{ request('q') }}"
                            placeholder="Digite o que procura... (Ex: Delivery, WhatsApp, PHP, SaaS)" 
                            class="w-full rounded-xl border border-slate-700 bg-slate-950 pl-11 pr-24 py-3.5 text-sm sm:text-base text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 shadow-inner"
                            autocomplete="off"
                        />
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 h-5 w-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <button 
                            type="submit" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg bg-teal-700 hover:bg-teal-800 px-4 py-2 text-xs font-bold text-white transition shadow-sm cursor-pointer"
                        >
                            Buscar
                        </button>
                    </div>

                {{-- Sugestões de busca rápida --}}
                <div class="pt-2">
                    <p class="text-xs font-semibold text-slate-400 mb-2">🔥 Mais buscados:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['WhatsApp', 'Delivery', 'Streaming', 'Laravel', 'SaaS', 'Automação', 'Mercado Pago'] as $term)
                            <a 
                                href="{{ route('catalog.index', ['q' => $term]) }}" 
                                class="rounded-lg bg-slate-800/90 hover:bg-teal-500/20 border border-slate-700 hover:border-teal-500/40 px-2.5 py-1 text-xs text-slate-300 hover:text-teal-300 transition"
                            >
                                {{ $term }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-800/80 text-[11px] text-slate-500">
                    <span>Pressione <kbd class="rounded bg-slate-800 px-1.5 py-0.5 text-slate-300 font-mono">Enter</kbd> para pesquisar</span>
                    <span><kbd class="rounded bg-slate-800 px-1.5 py-0.5 text-slate-300 font-mono">ESC</kbd> para fechar</span>
                </div>
            </form>
        </div>
    </div>

    {{-- Off-canvas Mobile Navigation Drawer --}}
    <div 
        x-show="mobileMenuOpen" 
        x-cloak
        class="relative z-50 lg:hidden" 
        role="dialog" 
        aria-modal="true"
        aria-labelledby="mobile-nav-title"
    >
        {{-- Backdrop --}}
        <div 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-950/80 backdrop-blur-md" 
            @click="mobileMenuOpen = false"
        ></div>

        <div class="fixed inset-0 flex justify-end">
            <div 
                x-show="mobileMenuOpen" 
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="relative flex w-full max-w-xs flex-1 flex-col bg-slate-950 border-l border-slate-800 text-slate-200 shadow-2xl overflow-y-auto"
                @click.stop
            >
                {{-- Drawer Header --}}
                <div class="flex h-20 shrink-0 items-center justify-between px-6 border-b border-slate-800/80">
                    <a href="{{ route('storefront.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-kltecnologia-80.webp') }}" alt="KL Tecnologia" width="32" height="32" class="h-8 w-8 rounded-lg object-cover shadow-sm shadow-teal-500/20" />
                        <span id="mobile-nav-title" class="font-display text-base font-bold text-white">
                            KL<span class="text-teal-400">Tecnologia</span>
                        </span>
                    </a>
                    <button 
                        type="button" 
                        @click="mobileMenuOpen = false"
                        class="rounded-xl p-2 text-slate-400 hover:bg-slate-900 hover:text-white transition cursor-pointer"
                        aria-label="Fechar menu"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Drawer Search Quick Trigger --}}
                <div class="px-5 pt-4">
                    <button 
                        type="button" 
                        @click="mobileMenuOpen = false; searchOpen = true; $nextTick(() => $refs.modalSearchInput?.focus())"
                        class="w-full flex items-center justify-between rounded-xl border border-slate-800 bg-slate-900/90 px-3.5 py-2.5 text-xs text-slate-400 hover:border-teal-500/40 hover:text-slate-200 transition cursor-pointer"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Buscar na loja...</span>
                        </span>
                        <kbd class="rounded bg-slate-800 px-1.5 py-0.5 text-[10px] font-mono text-slate-400">Ctrl+K</kbd>
                    </button>
                </div>

                {{-- Navigation Links --}}
                <div class="flex flex-1 flex-col px-5 py-6 space-y-6">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
                            Navegação
                        </span>
                        <nav class="mt-3 space-y-1.5">
                            <a 
                                href="{{ route('storefront.index') }}#destaques" 
                                @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-900 hover:text-teal-400 transition"
                            >
                                <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                                </svg>
                                <span>Destaques</span>
                            </a>

                            <a 
                                href="{{ route('catalog.index') }}" 
                                @click="mobileMenuOpen = false"
                                class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('catalog.index') ? 'bg-teal-600/20 text-teal-300 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-900 hover:text-teal-400' }} transition"
                            >
                                <span class="flex items-center gap-3">
                                    <svg class="h-4 w-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    <span>Catálogo Completo</span>
                                </span>
                                <span class="rounded bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 font-mono text-[10px] text-teal-400 font-bold">VIP</span>
                            </a>

                            <a 
                                href="{{ route('cart.index') }}" 
                                @click="mobileMenuOpen = false"
                                class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('cart.index') ? 'bg-teal-600/20 text-teal-300 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-900 hover:text-teal-400' }} transition"
                            >
                                <span class="flex items-center gap-3">
                                    <svg class="h-4 w-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span>Meu Carrinho</span>
                                </span>
                                <span x-show="cartCount > 0" x-text="cartCount" class="rounded-full bg-teal-500 px-2 py-0.5 text-[10px] text-white font-bold"></span>
                            </a>

                            <a 
                                href="{{ route('favorites.index') }}" 
                                @click="mobileMenuOpen = false"
                                class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('favorites.index') ? 'bg-pink-600/20 text-pink-300 border border-pink-500/30' : 'text-slate-300 hover:bg-slate-900 hover:text-pink-400' }} transition"
                            >
                                <span class="flex items-center gap-3">
                                    <svg class="h-4 w-4 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span>Meus Favoritos</span>
                                </span>
                                <span x-show="favoritesCount > 0" x-text="favoritesCount" class="rounded-full bg-pink-500 px-2 py-0.5 text-[10px] text-white font-bold"></span>
                            </a>

                            <a 
                                href="{{ route('blog.index') }}" 
                                @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('blog.*') ? 'bg-teal-600/20 text-teal-300 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-900 hover:text-teal-400' }} transition"
                            >
                                <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <span>Blog & Artigos</span>
                            </a>

                            <a 
                                href="{{ route('storefront.index') }}#atualizacoes" 
                                @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-900 hover:text-teal-400 transition"
                            >
                                <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>Últimas Atualizações</span>
                            </a>

                            <a 
                                href="{{ route('storefront.index') }}#vantagens" 
                                @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-900 hover:text-teal-400 transition"
                            >
                                <svg class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Vantagens & Garantias</span>
                            </a>
                        </nav>
                    </div>

                    {{-- Account & Auth --}}
                    <div class="pt-4 border-t border-slate-800">
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
                            Minha Conta
                        </span>
                        <div class="mt-3 space-y-2">
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <a 
                                        href="{{ route('admin.dashboard') }}" 
                                        class="flex items-center gap-2.5 rounded-xl bg-slate-900 border border-slate-700 px-3.5 py-2.5 text-xs font-semibold text-teal-400 hover:bg-slate-800 transition"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Acessar Painel Admin</span>
                                    </a>
                                @endif
                                <a 
                                    href="{{ route('customer.downloads') }}" 
                                    class="flex items-center gap-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 px-3.5 py-2.5 text-xs font-bold text-white shadow-sm shadow-teal-700/20 transition"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Meus Downloads & Pedidos</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 text-xs text-rose-400 hover:text-rose-300 py-2 px-3.5 rounded-lg hover:bg-slate-900 transition cursor-pointer">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Sair da Conta</span>
                                    </button>
                                </form>
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="flex items-center justify-center gap-2 rounded-xl border border-slate-700 bg-slate-900/80 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-slate-800 transition"
                                >
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Já tenho conta (Entrar)</span>
                                </a>
                                <a 
                                    href="{{ route('register') }}" 
                                    class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-blue-600 hover:from-teal-400 hover:to-blue-500 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-teal-500/25 transition"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    <span>Criar Conta Gratuita</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Drawer Footer --}}
                <div class="p-5 border-t border-slate-800/80 bg-slate-950 text-center">
                    <div class="flex items-center justify-center gap-2 text-[11px] text-emerald-400 font-mono">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span>Ambiente 100% Seguro</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-flash />

    {{-- Main Content --}}
    <main id="conteudo">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Newsletter Pre-Footer Banner --}}
    <section class="border-t border-b border-teal-900/50 bg-gradient-to-r from-teal-950 via-slate-950 to-blue-950 py-12 text-white">
        <div class="page-container flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-teal-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                    Fique Atualizado
                </span>
                <h3 class="mt-3 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    Novos produtos e códigos toda semana.
                </h3>
                <p class="mt-2 text-sm text-slate-300">
                    Cadastre-se para receber avisos de lançamentos, promoções relâmpago e materiais exclusivos.
                </p>
            </div>
            <div 
                x-data="{
                    email: '',
                    loading: false,
                    successMessage: '',
                    errorMessage: '',
                    async submitForm() {
                        if (!this.email || this.loading) return;
                        this.loading = true;
                        this.errorMessage = '';
                        this.successMessage = '';

                        try {
                            const res = await fetch('{{ route('newsletter.subscribe') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')?.content || '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ email: this.email })
                            });

                            const data = await res.json();

                            if (res.ok && data.success) {
                                this.successMessage = data.message || 'Inscrição realizada com sucesso!';
                                this.email = '';
                                window.dispatchEvent(new CustomEvent('toast-message', { 
                                    detail: { message: this.successMessage } 
                                }));
                            } else {
                                this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Erro ao processar inscrição. Tente novamente.');
                            }
                        } catch (err) {
                            this.errorMessage = 'Ocorreu um erro de conexão. Tente novamente.';
                        } finally {
                            this.loading = false;
                        }
                    }
                }"
                class="w-full max-w-md"
            >
                <form @submit.prevent="submitForm" class="flex flex-col sm:flex-row gap-2">
                    <input 
                        type="email" 
                        x-model="email"
                        required 
                        :disabled="loading"
                        placeholder="Seu melhor e-mail..." 
                        class="flex-1 rounded-xl border-slate-700 bg-slate-900/90 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-400 focus:ring-2 focus:ring-teal-400/30 disabled:opacity-50"
                    />
                    <button 
                        type="submit" 
                        :disabled="loading"
                        class="btn-teal whitespace-nowrap shadow-lg shadow-teal-700/30 flex items-center justify-center gap-2 disabled:opacity-60"
                    >
                        <template x-if="loading">
                            <svg class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Enviando...' : 'Receber Novidades'"></span>
                    </button>
                </form>

                {{-- Feedback messages --}}
                <div x-show="successMessage" x-cloak class="mt-2.5 flex items-center gap-2 text-xs font-medium text-emerald-400 bg-emerald-950/40 border border-emerald-500/20 px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span x-text="successMessage"></span>
                </div>

                <div x-show="errorMessage" x-cloak class="mt-2.5 flex items-center gap-2 text-xs font-medium text-rose-400 bg-rose-950/40 border border-rose-500/20 px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="errorMessage"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Comprehensive 4-Column Footer --}}
    <footer class="border-t border-slate-900 bg-slate-950 text-slate-400 py-16">
        <div class="page-container grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Col 1: About --}}
            <div>
                <a href="{{ route('storefront.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-kltecnologia-80.webp') }}" alt="KL Tecnologia" width="36" height="36" loading="lazy" decoding="async" class="h-9 w-9 rounded-xl object-cover shadow-md shadow-teal-500/20" />
                    <span class="font-display text-lg font-bold text-white">
                        KL<span class="text-teal-400">Tecnologia</span>
                    </span>
                </a>
                <p class="mt-4 text-xs sm:text-sm leading-relaxed text-slate-400">
                    Plataforma especializada em produtos digitais, templates, scripts autorais e PLRs prontos para execução e escala de negócios digitais.
                </p>
                <div class="mt-6 flex items-center gap-2 text-xs font-mono text-emerald-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Ambiente 100% Seguro & Criptografado
                </div>
            </div>

            {{-- Col 2: Links --}}
            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-wider text-slate-200">Navegação</h4>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('storefront.index') }}#destaques" class="hover:text-teal-400 transition">Produtos em Destaque</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-teal-400 transition">Catálogo Completo</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-teal-400 transition">Blog & Artigos</a></li>
                    <li><a href="{{ route('storefront.index') }}#atualizacoes" class="hover:text-teal-400 transition">Últimas Atualizações</a></li>
                    <li><a href="{{ route('storefront.index') }}#vantagens" class="hover:text-teal-400 transition">Vantagens & Garantias</a></li>
                </ul>
            </div>

            {{-- Col 3: Support & Policy --}}
            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-wider text-slate-200">Suporte & Termos</h4>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('customer.downloads') }}" class="hover:text-teal-400 transition">Área de Downloads</a></li>
                    <li><a href="{{ route('terms.index') }}" class="hover:text-teal-400 transition">Termos de Uso & Licença</a></li>
                    <li><a href="{{ route('privacy.index') }}" class="hover:text-teal-400 transition">Política de Privacidade (LGPD)</a></li>
                    <li>
                        <button 
                            type="button" 
                            @click="$dispatch('open-cookie-settings')" 
                            class="hover:text-teal-400 transition text-left cursor-pointer inline-flex items-center gap-1.5 text-slate-400"
                        >
                            <svg class="h-3.5 w-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Preferências de Cookies
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Col 4: Payments & Guarantee --}}
            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-wider text-slate-200">Pagamento & Segurança</h4>
                <p class="mt-4 text-xs sm:text-sm text-slate-400">
                    Transações processadas via gateway Mercado Pago com suporte a Pix imediato e Cartão de Crédito.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-lg bg-slate-900 border border-slate-800 px-2.5 py-1 text-xs font-semibold text-slate-300">
                        ⚡ Pix Instantâneo
                    </span>
                    <span class="inline-flex items-center rounded-lg bg-slate-900 border border-slate-800 px-2.5 py-1 text-xs font-semibold text-slate-300">
                        💳 Cartão de Crédito
                    </span>
                    <span class="inline-flex items-center rounded-lg bg-slate-900 border border-slate-800 px-2.5 py-1 text-xs font-semibold text-emerald-400">
                        🔒 SSL 256-Bit
                    </span>
                </div>
            </div>
        </div>

        <div class="page-container mt-12 border-t border-slate-900 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <p>© {{ now()->year }} KL Tecnologia. Todos os direitos reservados.</p>
            <p>Vendas unitárias de infoprodutos com entrega segura e imediata.</p>
        </div>
    </footer>

    {{-- Toast Notification --}}
    <div 
        x-show="toast.show" 
        x-cloak
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
        x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
        class="fixed bottom-5 right-5 z-50 max-w-sm w-full mx-auto sm:mx-0 p-4 rounded-2xl bg-slate-900/95 border border-teal-500/40 text-white shadow-2xl shadow-teal-950/50 backdrop-blur-md flex items-center justify-between gap-3"
        role="alert"
    >
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-teal-500/20 border border-teal-500/40 flex items-center justify-center text-teal-400 shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="text-xs sm:text-sm">
                <p class="font-bold text-white" x-text="toast.message"></p>
                <p class="text-[11px] text-slate-400">Item disponível no seu pedido</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <template x-if="toast.cartUrl">
                <a 
                    :href="toast.cartUrl" 
                    class="rounded-lg bg-teal-700 hover:bg-teal-800 px-3 py-1.5 text-xs font-bold text-white transition shadow-xs whitespace-nowrap"
                >
                    Ver Carrinho →
                </a>
            </template>
            <button 
                type="button" 
                @click="toast.show = false" 
                class="rounded-lg p-1 text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer"
                aria-label="Fechar notificação"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        window.addToCart = function(product) {
            try {
                let cart = JSON.parse(localStorage.getItem('kl_cart') || '[]');
                if (!Array.isArray(cart)) cart = [];
                const index = cart.findIndex(item => item.id === product.id);
                if (index === -1) {
                    cart.push({
                        id: product.id,
                        title: product.title,
                        slug: product.slug,
                        price: parseFloat(product.price),
                        formatted_price: product.formatted_price || ('R$ ' + parseFloat(product.price).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })),
                        cover_image: product.cover_image || null,
                        category: product.category || 'Sistema Web'
                    });
                    localStorage.setItem('kl_cart', JSON.stringify(cart));
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: cart.length } }));
                }
                window.dispatchEvent(new CustomEvent('toast-message', {
                    detail: {
                        message: index !== -1 ? 'Item já está no carrinho!' : 'Adicionado ao carrinho!',
                        type: 'success',
                        cartUrl: '{{ route("cart.index") }}'
                    }
                }));
            } catch (e) {
                console.error('Erro ao manipular o carrinho:', e);
            }
        };

        window.toggleFavorite = function(product, btn) {
            try {
                let favs = JSON.parse(localStorage.getItem('kl_favorites') || '[]');
                if (!Array.isArray(favs)) favs = [];

                const productId = typeof product === 'object' ? product.id : product;
                const existingIndex = favs.findIndex(item => (typeof item === 'object' ? item.id === productId : item === productId));
                let isAdded = false;

                if (existingIndex > -1) {
                    favs.splice(existingIndex, 1);
                    isAdded = false;
                } else {
                    if (typeof product === 'object') {
                        favs.push({
                            id: product.id,
                            title: product.title,
                            slug: product.slug,
                            price: parseFloat(product.price) || 0,
                            formatted_price: product.formatted_price || (parseFloat(product.price) <= 0 ? 'GRÁTIS' : ('R$ ' + parseFloat(product.price).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))),
                            cover_image: product.cover_image || null,
                            category: product.category || 'Sistema Web'
                        });
                    } else {
                        favs.push(product);
                    }
                    isAdded = true;
                }

                localStorage.setItem('kl_favorites', JSON.stringify(favs));
                window.dispatchEvent(new CustomEvent('favorites-updated', { detail: { count: favs.length, favorites: favs } }));

                if (btn) {
                    if (isAdded) {
                        btn.classList.add('text-pink-500');
                        btn.classList.remove('text-slate-400');
                    } else {
                        btn.classList.remove('text-pink-500');
                        btn.classList.add('text-slate-400');
                    }
                }

                window.dispatchEvent(new CustomEvent('toast-message', {
                    detail: {
                        message: isAdded ? 'Item salvo nos seus Favoritos! ❤️' : 'Item removido dos Favoritos.',
                        type: isAdded ? 'success' : 'info',
                        cartUrl: '{{ route("favorites.index") }}'
                    }
                }));

                return isAdded;
            } catch (e) {
                console.error('Erro ao manipular favoritos:', e);
            }
        };

        window.isFavorite = function(productId) {
            try {
                const favs = JSON.parse(localStorage.getItem('kl_favorites') || '[]');
                if (!Array.isArray(favs)) return false;
                return favs.some(item => (typeof item === 'object' ? item.id === productId : item === productId));
            } catch (e) {
                return false;
            }
        };
    </script>

    {{-- Aviso e Gerenciador de Cookies LGPD --}}
    <x-cookie-consent />
</body>
</html>

