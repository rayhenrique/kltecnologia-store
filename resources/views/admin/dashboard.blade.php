<x-admin-layout>
    <x-slot:title>Painel administrativo</x-slot:title>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Visão Geral
                    </span>
                    <span class="inline-flex items-center gap-1 font-mono text-[11px] text-emerald-600 font-semibold">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Tempo Real
                    </span>
                </div>
                <h1 class="mt-1.5 font-display text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-slate-900">
                    Painel administrativo
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Acompanhe o faturamento, status de pedidos e o catálogo de produtos digitais da KL Tecnologia.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
                <a 
                    href="{{ route('catalog.index') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center justify-center gap-1.5 shadow-2xs w-full sm:w-auto text-center"
                >
                    <svg class="h-4 w-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span class="truncate">Ver Catálogo</span>
                </a>
                <a 
                    href="{{ route('admin.products.create') }}" 
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 px-3.5 sm:px-4 py-2 text-xs font-bold text-white shadow-sm shadow-teal-500/20 transition transform active:scale-95 w-full sm:w-auto text-center !min-h-10"
                >
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="truncate">Novo Produto</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full space-y-6 sm:space-y-8">
        {{-- 1. CARDS DE INDICADORES (MÉTRICAS SAAS) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5">
            {{-- Card 1: Receita Confirmada --}}
            <article class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Receita Confirmada
                    </span>
                    <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 sm:mt-4 font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight truncate">
                    R$ {{ number_format((float) $revenue, 2, ',', '.') }}
                </p>
                <div class="mt-2 sm:mt-3 flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="truncate">Mercado Pago • Liquidado</span>
                </div>
            </article>

            {{-- Card 2: Pedidos Pagos --}}
            <article class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Pedidos Pagos
                    </span>
                    <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 sm:mt-4 font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $paidOrders }}
                </p>
                <div class="mt-2 sm:mt-3 flex items-center gap-1.5 text-xs font-semibold text-blue-600">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="truncate">Downloads Ativos</span>
                </div>
            </article>

            {{-- Card 3: Produtos Ativos --}}
            <article class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-xs hover:shadow-md transition sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Produtos Ativos
                    </span>
                    <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-xl bg-teal-50 text-teal-600 border border-teal-100 shadow-2xs shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 sm:mt-4 font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $activeProducts }}
                </p>
                <div class="mt-2 sm:mt-3 flex items-center gap-1.5 text-xs font-semibold text-teal-600">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="truncate">Disponíveis para Compra</span>
                </div>
            </article>
        </div>

        {{-- 2. MÉTRICAS NATIVAS DE TRÁFEGO & VISITAS --}}
        <section class="space-y-4 sm:space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded bg-indigo-50 border border-indigo-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-indigo-700">
                            Analytics Nativo
                        </span>
                        <span class="inline-flex items-center gap-1 font-mono text-[10px] sm:text-[11px] text-emerald-600 font-semibold">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Zero Latência • Imune a AdBlockers
                        </span>
                    </div>
                    <h2 class="mt-1 font-display text-base sm:text-lg lg:text-xl font-bold text-slate-900">
                        Métricas de tráfego & audiência
                    </h2>
                </div>
                <div class="text-xs text-slate-500 font-medium">
                    Últimos 14 dias de movimentação orgânica
                </div>
            </div>

            {{-- 4 Cards de Métricas de Tráfego --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                {{-- Visitas Hoje --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Visitas Hoje</span>
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-teal-50 text-teal-600 border border-teal-100 shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-2.5 sm:mt-3 flex items-baseline gap-2 flex-wrap">
                        <span class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            {{ number_format($traffic['todayViews']) }}
                        </span>
                        <span class="text-xs text-slate-500">
                            ({{ number_format($traffic['todayUniques']) }} únicos)
                        </span>
                    </div>
                    <div class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                        <span>Ontem:</span>
                        <strong class="text-slate-700 truncate">{{ number_format($traffic['yesterdayViews']) }} visualizações</strong>
                    </div>
                </div>

                {{-- Visitantes Únicos no Mês --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Visitantes no Mês</span>
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-2.5 sm:mt-3 flex items-baseline gap-2 flex-wrap">
                        <span class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            {{ number_format($traffic['monthUniques']) }}
                        </span>
                        <span class="text-xs text-slate-500">visitantes</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                        <span>Páginas vistas:</span>
                        <strong class="text-slate-700">{{ number_format($traffic['monthViews']) }}</strong>
                    </div>
                </div>

                {{-- Taxa de Conversão --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Taxa de Conversão</span>
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-2.5 sm:mt-3 flex items-baseline gap-2 flex-wrap">
                        <span class="font-display text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight">
                            {{ $traffic['conversionRate'] }}%
                        </span>
                        <span class="text-xs text-slate-500">visitas &rarr; compras</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                        <span>Vendas:</span>
                        <strong class="text-slate-700">{{ $traffic['paidOrdersMonth'] }} pedidos no mês</strong>
                    </div>
                </div>

                {{-- Dispositivos --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Dispositivos</span>
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-amber-50 text-amber-600 border border-amber-100 shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-2.5 sm:mt-3 flex items-baseline gap-2 flex-wrap">
                        <span class="font-display text-xl font-bold text-slate-900">
                            {{ $traffic['deviceBreakdown']['desktop'] }}% <span class="text-xs font-normal text-slate-500">PC</span>
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="font-display text-xl font-bold text-slate-900">
                            {{ $traffic['deviceBreakdown']['mobile'] }}% <span class="text-xs font-normal text-slate-500">Mobile</span>
                        </span>
                    </div>
                    <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden flex">
                        <div class="bg-teal-500 h-full transition-all duration-500" style="width: {{ $traffic['deviceBreakdown']['desktop'] }}%"></div>
                        <div class="bg-amber-400 h-full transition-all duration-500" style="width: {{ $traffic['deviceBreakdown']['mobile'] }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Gráfico Interativo dos Últimos 14 Dias (Mobile-First com Scroll Horizontal Suave e Toque) --}}
            <div 
                class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-xs" 
                x-data="{ activeBar: null }"
                @click.outside="activeBar = null"
            >
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 sm:mb-6">
                    <div>
                        <h3 class="font-display text-base font-bold text-slate-900">
                            Evolução diária de visualizações
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Passe o mouse ou toque nas barras para ver detalhes de acessos por dia.
                        </p>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-3 text-xs">
                        <span class="sm:hidden text-[10px] text-slate-400 font-mono">
                            ↔ Arraste o gráfico para o lado
                        </span>
                        <span class="flex items-center gap-1.5 font-medium text-slate-600 shrink-0">
                            <span class="h-3 w-3 rounded bg-teal-500"></span>
                            Visualizações
                        </span>
                    </div>
                </div>

                {{-- Scroll container horizontal para não espremer no celular --}}
                <div class="overflow-x-auto pb-2 -mx-2 px-2 sm:mx-0 sm:px-0">
                    <div class="min-w-[500px] sm:min-w-0">
                        {{-- Área do Gráfico de Barras --}}
                        <div class="h-44 flex items-end justify-between gap-1.5 sm:gap-3 pt-6 pb-2 border-b border-slate-100">
                            @foreach($traffic['dailyChart'] as $point)
                                <div 
                                    class="flex-1 h-full flex flex-col justify-end items-center group relative cursor-pointer"
                                    @click="activeBar = activeBar === {{ $loop->index }} ? null : {{ $loop->index }}"
                                >
                                    {{-- Tooltip ao passar o mouse ou ao tocar no mobile --}}
                                    <div 
                                        class="absolute -top-12 z-20 hidden flex-col items-center pointer-events-none transition-all group-hover:flex"
                                        :class="activeBar === {{ $loop->index }} ? '!flex' : ''"
                                    >
                                        <div class="bg-slate-900 text-white text-[11px] rounded-lg py-1 px-2.5 shadow-lg whitespace-nowrap">
                                            <div class="font-bold text-teal-400">{{ $point['views'] }} visualizações</div>
                                            <div class="text-slate-300 text-[10px]">{{ $point['uniques'] }} únicos • {{ $point['fullDate'] }}</div>
                                        </div>
                                        <div class="w-2 h-2 bg-slate-900 rotate-45 -mt-1"></div>
                                    </div>

                                    {{-- Barra Vertical --}}
                                    <div 
                                        class="w-full max-w-[28px] rounded-t-md bg-gradient-to-t from-teal-600 to-teal-400 group-hover:from-teal-500 group-hover:to-teal-300 transition-all duration-300 shadow-2xs"
                                        :class="activeBar === {{ $loop->index }} ? 'from-teal-500 to-teal-300 ring-2 ring-teal-400' : ''"
                                        style="height: {{ $point['heightPercent'] }}%; min-height: 4px;"
                                    ></div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Eixo X de Datas --}}
                        <div class="flex items-center justify-between gap-1.5 sm:gap-3 mt-2 text-[10px] sm:text-[11px] font-mono text-slate-600">
                            @foreach($traffic['dailyChart'] as $point)
                                <div class="flex-1 text-center truncate">
                                    {{ $point['date'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2 Colunas: Top Produtos & Top Artigos --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                {{-- Top 5 Produtos Mais Acessados --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3 sm:mb-4">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-base shrink-0">🔥</span>
                            <h3 class="font-display text-sm font-bold text-slate-900 truncate">
                                Top Produtos Mais Acessados (30 dias)
                            </h3>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700 shrink-0 ml-2">
                            Ver todos &rarr;
                        </a>
                    </div>

                    <div class="space-y-2.5 sm:space-y-3">
                        @forelse($traffic['topProducts'] as $item)
                            <div class="flex items-center justify-between p-2 sm:p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200/60 gap-2.5">
                                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                                    <div class="h-9 w-9 sm:h-10 sm:w-10 shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-slate-200/80">
                                        <img 
                                            src="{{ $item['product']->cover_path ? asset($item['product']->cover_path) : asset('images/logo-kltecnologia.png') }}" 
                                            alt="{{ $item['product']->title }}" 
                                            class="h-full w-full object-cover"
                                            onerror="this.src='{{ asset('images/logo-kltecnologia.png') }}'"
                                        >
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <a 
                                            href="{{ route('storefront.show', $item['product']->slug) }}" 
                                            target="_blank"
                                            class="font-semibold text-xs text-slate-900 hover:text-teal-600 truncate block transition"
                                            title="{{ $item['product']->title }}"
                                        >
                                            {{ $item['product']->title }}
                                        </a>
                                        <div class="text-[11px] font-mono text-slate-600 mt-0.5">
                                            R$ {{ number_format((float) $item['product']->price, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-teal-50 border border-teal-200/60 px-2 py-0.5 text-[10px] sm:text-[11px] font-bold text-teal-700 whitespace-nowrap">
                                        <span>{{ $item['views'] }}</span>
                                        <span class="hidden sm:inline">visualizações</span>
                                        <span class="sm:hidden">views</span>
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 text-center py-6">Nenhum acesso registrado ainda.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Top 5 Artigos do Blog Mais Lidos --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3 sm:mb-4">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-base shrink-0">📚</span>
                            <h3 class="font-display text-sm font-bold text-slate-900 truncate">
                                Top Artigos Mais Lidos no Blog
                            </h3>
                        </div>
                        <a href="{{ route('admin.posts.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700 shrink-0 ml-2">
                            Ver posts &rarr;
                        </a>
                    </div>

                    <div class="space-y-2.5 sm:space-y-3">
                        @forelse($traffic['topPosts'] as $item)
                            <div class="flex items-center justify-between p-2 sm:p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200/60 gap-2.5">
                                <div class="min-w-0 flex-1 pr-1">
                                    @if($item['post']->blogCategory)
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 block mb-0.5 truncate">
                                            {{ $item['post']->blogCategory->name }}
                                        </span>
                                    @endif
                                    <a 
                                        href="{{ route('blog.show', $item['post']->slug) }}" 
                                        target="_blank"
                                        class="font-semibold text-xs text-slate-900 hover:text-teal-600 truncate block transition"
                                        title="{{ $item['post']->title }}"
                                    >
                                        {{ $item['post']->title }}
                                    </a>
                                </div>
                                <div class="shrink-0 text-right">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200/60 px-2 py-0.5 text-[10px] sm:text-[11px] font-bold text-indigo-700 whitespace-nowrap">
                                        <span>{{ $item['views'] }}</span>
                                        <span class="hidden sm:inline">leituras</span>
                                        <span class="sm:hidden">views</span>
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 text-center py-6">Nenhum artigo lido ainda.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. TABELA / CARDS DE PEDIDOS RECENTES (Mobile-First: Cards no celular e Tabela no desktop) --}}
        <section class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 p-4 sm:p-6 gap-2 sm:gap-3">
                <div>
                    <h2 class="font-display text-base sm:text-lg font-bold text-slate-900">
                        Pedidos recentes
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Últimas movimentações e status de pagamento recebidos pela loja.
                    </p>
                </div>
                <a 
                    href="{{ route('admin.orders.index') }}" 
                    class="inline-flex items-center gap-1 text-xs font-bold text-teal-600 hover:text-teal-700 transition self-start sm:self-auto mt-1 sm:mt-0"
                >
                    <span>Ver todos os pedidos</span>
                    <span>&rarr;</span>
                </a>
            </div>

            {{-- Versão Mobile: Cards Inteligentes (telas < 768px) --}}
            <div class="divide-y divide-slate-100 md:hidden">
                @forelse($latestOrders as $order)
                    <div class="p-4 space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">
                                    #{{ $order->id }}
                                </span>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <span class="font-display font-bold text-slate-900 text-sm">
                                R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2.5 pt-0.5">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-600">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-slate-900 text-xs truncate">{{ $order->user->name }}</p>
                                <p class="text-[11px] text-slate-400 font-mono truncate">{{ $order->user->email }}</p>
                            </div>
                        </div>

                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-2.5 text-xs flex items-center justify-between gap-2">
                            <span class="font-medium text-slate-700 truncate" title="{{ $order->product->title }}">
                                {{ $order->product->title }}
                            </span>
                            <span class="text-[10px] font-mono text-teal-600 uppercase font-bold shrink-0">Digital</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <p class="font-bold text-slate-800 text-sm">Nenhum pedido registrado.</p>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Assim que os clientes realizarem compras pelo Mercado Pago, os pedidos aparecerão aqui automaticamente.</p>
                    </div>
                @endforelse
            </div>

            {{-- Versão Desktop / Tablet: Tabela Completa (telas >= 768px) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3.5">Pedido</th>
                            <th class="px-6 py-3.5">Cliente</th>
                            <th class="px-6 py-3.5">Produto</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($latestOrders as $order)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md">
                                        #{{ $order->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="grid h-7 w-7 place-items-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-600">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-xs">{{ $order->user->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono">{{ $order->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800 text-xs max-w-xs truncate">{{ $order->product->title }}</p>
                                    <span class="text-[10px] font-mono text-teal-600 uppercase font-semibold">Digital</span>
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$order->status" />
                                </td>
                                <td class="px-6 py-4 font-display font-bold text-slate-900 text-xs">
                                    R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-800 text-sm">Nenhum pedido registrado.</p>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Assim que os clientes realizarem compras pelo Mercado Pago, os pedidos aparecerão aqui automaticamente.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-layout>
