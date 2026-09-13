<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name', 'KL Tecnologia') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&family=ibm-plex-mono:500,600&family=sora:600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50">
    <a href="#conteudo" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2">Ir para o conteúdo</a>
    
    {{-- Header --}}
    <header class="sticky top-0 z-40 border-b border-slate-800 bg-slate-950/95 backdrop-blur-md">
        <div class="page-container flex min-h-20 items-center justify-between gap-4 py-3">
            {{-- Logo --}}
            <a href="{{ route('storefront.index') }}" class="flex items-center gap-3 group shrink-0">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-teal-400 to-blue-600 font-display font-bold text-white shadow-lg shadow-teal-500/20 group-hover:scale-105 transition">
                    KL
                </span>
                <span class="font-display text-lg font-bold tracking-tight text-white group-hover:text-teal-400 transition">
                    KL<span class="text-teal-400">Tecnologia</span>
                </span>
            </a>

            {{-- Header Search Bar (Desktop) --}}
            <form action="{{ route('catalog.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-4">
                <div class="relative w-full">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ request('q') }}"
                        placeholder="Buscar templates, scripts, sistemas..." 
                        class="w-full rounded-xl border-slate-800 bg-slate-900/90 pl-10 pr-4 py-2 text-sm text-slate-200 placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 transition shadow-inner"
                    />
                    <svg class="pointer-events-none absolute left-3.5 top-2.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>

            {{-- Navigation & Auth --}}
            <nav aria-label="Navegação principal" class="flex items-center gap-3 sm:gap-6">
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

                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 border border-slate-700 px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-200 hover:bg-slate-700 transition">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Painel Admin
                            </a>
                        @endif
                        <a href="{{ route('customer.downloads') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-sm shadow-teal-500/20 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Meus Downloads
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-300 hover:text-white px-2 py-1 transition">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Entrar
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-blue-600 hover:from-teal-400 hover:to-blue-500 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-md shadow-teal-500/25 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Cadastre-se
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <x-flash />

    {{-- Main Content --}}
    <main id="conteudo">
        {{ $slot }}
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
            <form onsubmit="event.preventDefault(); alert('Inscrição confirmada com sucesso!'); this.reset();" class="flex w-full max-w-md flex-col sm:flex-row gap-2">
                <input 
                    type="email" 
                    required 
                    placeholder="Seu melhor e-mail..." 
                    class="flex-1 rounded-xl border-slate-700 bg-slate-900/90 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-400 focus:ring-2 focus:ring-teal-400/30"
                />
                <button type="submit" class="btn-teal whitespace-nowrap shadow-lg shadow-teal-600/30">
                    Receber Novidades
                </button>
            </form>
        </div>
    </section>

    {{-- Comprehensive 4-Column Footer --}}
    <footer class="border-t border-slate-900 bg-slate-950 text-slate-400 py-16">
        <div class="page-container grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Col 1: About --}}
            <div>
                <a href="{{ route('storefront.index') }}" class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-teal-400 to-blue-600 font-display font-bold text-white">
                        KL
                    </span>
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
                    <li><a href="{{ route('storefront.index') }}#atualizacoes" class="hover:text-teal-400 transition">Últimas Atualizações</a></li>
                    <li><a href="{{ route('storefront.index') }}#vantagens" class="hover:text-teal-400 transition">Vantagens & Garantias</a></li>
                </ul>
            </div>

            {{-- Col 3: Support & Policy --}}
            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-wider text-slate-200">Suporte & Termos</h4>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('customer.downloads') }}" class="hover:text-teal-400 transition">Área de Downloads</a></li>
                    <li><span class="text-slate-500">Entrega Automática e Imediata</span></li>
                    <li><span class="text-slate-500">Termos de Uso e Licenciamento</span></li>
                    <li><span class="text-slate-500">Política de Privacidade</span></li>
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

        <div class="page-container mt-12 border-t border-slate-900 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <p>© {{ now()->year }} KL Tecnologia. Todos os direitos reservados.</p>
            <p>Vendas unitárias de infoprodutos com entrega segura e imediata.</p>
        </div>
    </footer>
</body>
</html>

