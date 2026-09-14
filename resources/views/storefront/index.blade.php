<x-storefront-layout>
    <x-slot:title>Recursos & Produtos Digitais de Alta Performance</x-slot:title>

    {{-- 1. HERO SECTION (Dark Tech Theme with Search) --}}
    <section class="hero-tech-bg relative overflow-hidden border-b border-slate-800 py-16 sm:py-24 text-white">
        <div class="page-container relative z-10">
            {{-- Header Text & Eyebrow --}}
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-teal-500/30 bg-teal-500/10 px-4 py-1.5 text-xs font-mono font-semibold uppercase tracking-widest text-teal-300 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-teal-400 animate-pulse"></span>
                    KL TECNOLOGIA &bull; RECURSOS DIGITAIS
                </span>
                <h1 class="mt-6 font-display text-4xl font-extrabold tracking-tight text-white sm:text-6xl sm:leading-[1.15]">
                    Projetos e Templates para <span class="bg-gradient-to-r from-teal-400 via-cyan-300 to-blue-400 bg-clip-text text-transparent">acelerar sua execução.</span>
                </h1>
                <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-300">
                    Acesso imediato a códigos autorais, templates e automações de alta qualidade. Compra unitária e segura, sem taxas ocultas ou mensalidades.
                </p>
            </div>

            {{-- Big Glowing Hero Search Bar --}}
            <div class="mx-auto mt-10 max-w-2xl">
                <form action="{{ route('storefront.index') }}#produtos" method="GET" class="relative flex items-center rounded-2xl border-2 border-teal-500/40 bg-slate-900/90 p-2 shadow-2xl shadow-teal-500/15 backdrop-blur transition-all duration-300 focus-within:border-teal-400 focus-within:ring-4 focus-within:ring-teal-500/20">
                    <div class="pl-3 text-teal-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('q') }}"
                        placeholder="Buscar scripts, templates, sistemas..." 
                        class="w-full border-0 bg-transparent px-3 sm:px-4 py-2 text-xs sm:text-base text-white placeholder-slate-400 focus:outline-none focus:ring-0"
                    />
                    @if(request('q'))
                        <a href="{{ route('storefront.index') }}" class="mr-2 text-xs font-semibold text-slate-400 hover:text-white">Limpar</a>
                    @endif
                    <button type="submit" class="btn-teal shrink-0 rounded-xl px-4 sm:px-7 py-2.5 sm:py-3 font-bold uppercase tracking-wider text-xs sm:text-sm">
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- 2. FEATURE ICONS (4 Badges / Cards) --}}
    <section class="border-b border-slate-200 bg-white py-12">
        <div class="page-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Feature 1 --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition hover:border-teal-200 hover:bg-white hover:shadow-md">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-teal-100 text-teal-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-sm font-bold text-slate-900">Download Imediato</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Liberação automática em "Meus Downloads" após aprovação.</p>
                </div>
            </div>

            {{-- Feature 2 --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition hover:border-teal-200 hover:bg-white hover:shadow-md">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-blue-100 text-blue-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-sm font-bold text-slate-900">Pagamento Seguro</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Pix e Cartão via Mercado Pago com proteção total.</p>
                </div>
            </div>

            {{-- Feature 3 --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition hover:border-teal-200 hover:bg-white hover:shadow-md">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-sm font-bold text-slate-900">Arquivos Autênticos</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Projetos testados e livres de vírus ou scripts nocivos.</p>
                </div>
            </div>

            {{-- Feature 4 --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition hover:border-teal-200 hover:bg-white hover:shadow-md">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-amber-100 text-amber-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-sm font-bold text-slate-900">Acesso Permanente</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Baixe novamente seus arquivos sempre que precisar.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. DUAL CALL-TO-ACTION BANNERS (Inspired by the 2 prominent cards in the reference image) --}}
    <section class="page-container py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Banner 1: Lançamentos Exclusivos --}}
            <div class="relative overflow-hidden rounded-3xl border border-teal-500/40 bg-gradient-to-br from-slate-900 via-slate-950 to-teal-950 p-8 sm:p-10 text-white shadow-xl">
                <div class="absolute right-0 top-0 -mt-8 -mr-8 h-48 w-48 rounded-full bg-teal-500/10 blur-3xl pointer-events-none"></div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-500/20 px-3 py-1 text-xs font-mono font-bold uppercase tracking-wider text-teal-300">
                    ✦ DESTAQUES DO MÊS
                </span>
                <h2 class="mt-4 font-display text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Lançamentos Exclusivos
                </h2>
                <p class="mt-3 text-sm text-slate-300 leading-relaxed max-w-md">
                    Templates, automações e projetos prontos recentemente publicados com arquitetura de alta performance.
                </p>
                <div class="mt-6">
                    <a href="#destaques" class="btn-teal">
                        Conferir Novidades &darr;
                    </a>
                </div>
            </div>

            {{-- Banner 2: Catálogo Completo --}}
            <div class="relative overflow-hidden rounded-3xl border border-blue-500/40 bg-gradient-to-br from-slate-900 via-slate-950 to-blue-950 p-8 sm:p-10 text-white shadow-xl">
                <div class="absolute right-0 top-0 -mt-8 -mr-8 h-48 w-48 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/20 px-3 py-1 text-xs font-mono font-bold uppercase tracking-wider text-blue-300">
                    📦 BIBLIOTECA COMPLETA
                </span>
                <h2 class="mt-4 font-display text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Todos os Produtos
                </h2>
                <p class="mt-3 text-sm text-slate-300 leading-relaxed max-w-md">
                    Navegue por todo o nosso inventário de soluções digitais e encontre o componente ideal para o seu projeto.
                </p>
                <div class="mt-6">
                    <a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-500 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition">
                        Explorar Catálogo &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. PRODUTOS EM DESTAQUE (Shelf Section 1) --}}
    <section id="destaques" class="page-container scroll-mt-24 py-10">
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div class="flex items-center gap-3">
                <div class="h-6 w-1.5 rounded-full bg-teal-500"></div>
                <h2 class="font-display text-2xl font-bold text-slate-900">Produtos em Destaque</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs sm:text-sm font-bold text-teal-700 hover:text-teal-800">
                Ver todos os itens &rarr;
            </a>
        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featuredProducts as $item)
                <article class="product-cut panel flex flex-col overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    {{-- Cover & Badge --}}
                    <a href="{{ route('storefront.show', $item) }}" aria-label="{{ $item->title }}" class="relative block overflow-hidden bg-slate-950 aspect-[4/3] group">
                        @if($item->cover_path)
                            <img 
                                src="{{ asset($item->cover_path) }}" 
                                alt="{{ $item->title }}" 
                                width="400" 
                                height="300" 
                                {!! $loop->index < 2 ? 'fetchpriority="high" decoding="async"' : 'loading="lazy" decoding="async"' !!}
                                class="h-full w-full object-cover group-hover:scale-105 transition duration-300"
                            >
                        @else
                            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 text-white font-display text-3xl font-bold">
                                KL
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 badge-hot shadow">
                            HOT
                        </span>

                        {{-- Botão de Favorito --}}
                        <button 
                            type="button" 
                            x-data="{ isFav: false }"
                            x-init="
                                isFav = window.isFavorite ? window.isFavorite({{ $item->id }}) : false;
                                window.addEventListener('favorites-updated', () => { 
                                    if (window.isFavorite) isFav = window.isFavorite({{ $item->id }});
                                });
                            "
                            aria-label="Favoritar {{ $item->title }}"
                            @click.prevent="
                                if (window.toggleFavorite) {
                                    isFav = window.toggleFavorite({
                                        id: {{ $item->id }},
                                        title: {{ json_encode($item->title) }},
                                        slug: {{ json_encode($item->slug) }},
                                        price: {{ (float) $item->price }},
                                        cover_image: {{ json_encode($item->cover_path ? asset($item->cover_path) : null) }},
                                        category: {{ json_encode($item->categoryRelation?->name ?? 'Sistema Web') }}
                                    });
                                }
                            "
                            :class="isFav ? 'text-pink-500' : 'text-slate-400 hover:text-pink-500'"
                            class="absolute top-3 right-3 grid h-8 w-8 place-items-center rounded-full bg-slate-900/80 hover:bg-slate-900 shadow-sm transition-transform duration-200 active:scale-90 backdrop-blur-sm z-10 cursor-pointer"
                        >
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    </a>

                    {{-- Content --}}
                    <div class="flex flex-1 flex-col p-5">
                        <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-teal-700">Disponível</p>
                        <h3 class="mt-1 font-display text-base font-bold text-slate-900 line-clamp-1">
                            <a href="{{ route('storefront.show', $item) }}" class="hover:text-teal-700 transition">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="mt-2 text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $item->description }}
                        </p>

                        <div class="mt-auto pt-5 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[10px] uppercase font-mono text-slate-500 font-semibold">Preço</span>
                                <p class="font-display text-lg font-extrabold text-slate-900">
                                    R$ {{ number_format((float) $item->price, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button 
                                    type="button" 
                                    @click.prevent="window.addToCart({
                                        id: {{ $item->id }},
                                        title: {{ json_encode($item->title) }},
                                        slug: {{ json_encode($item->slug) }},
                                        price: {{ (float) $item->price }},
                                        cover_image: {{ json_encode($item->cover_image) }},
                                        category: {{ json_encode($item->categoryRelation?->name ?? 'Sistema Web') }}
                                    })"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-teal-200 bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white transition cursor-pointer"
                                    title="Adicionar ao Carrinho"
                                    aria-label="Adicionar ao carrinho"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </button>
                                <a href="{{ route('storefront.show', $item) }}" class="inline-flex items-center justify-center rounded-lg bg-teal-600 hover:bg-teal-500 px-3.5 py-2 text-xs font-bold text-white transition shadow-sm">
                                    Comprar
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full panel p-8 text-center text-slate-500">
                    Nenhum produto em destaque no momento.
                </div>
            @endforelse
        </div>
    </section>

    {{-- 5. CATÁLOGO COMPLETO & BUSCA (Shelf Section 2) --}}
    <section id="produtos" class="page-container scroll-mt-24 py-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-4">
            <div class="flex items-center gap-3">
                <div class="h-6 w-1.5 rounded-full bg-blue-600"></div>
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">Catálogo Completo</h2>
                    @if($search)
                        <p class="text-xs text-slate-500 mt-0.5">
                            Filtrando por: <strong class="text-slate-800">"{{ $search }}"</strong> 
                            <a href="{{ route('storefront.index') }}#produtos" class="ml-2 text-teal-600 underline font-semibold">Limpar filtro</a>
                        </p>
                    @endif
                </div>
            </div>
            <span class="text-xs font-mono text-slate-500">
                {{ $products->total() }} {{ $products->total() === 1 ? 'item encontrado' : 'itens encontrados' }}
            </span>
        </div>

        {{-- Products Grid --}}
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($products as $product)
                <article class="product-cut panel flex flex-col overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('storefront.show', $product) }}" aria-label="{{ $product->title }}" class="relative block overflow-hidden bg-slate-950 aspect-[4/3] group">
                        @if($product->cover_path)
                            <img 
                                src="{{ asset($product->cover_path) }}" 
                                alt="{{ $product->title }}" 
                                width="400" 
                                height="300" 
                                loading="lazy" 
                                decoding="async" 
                                class="h-full w-full object-cover group-hover:scale-105 transition duration-300"
                            >
                        @else
                            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-100 to-blue-100 font-display text-3xl font-bold text-blue-700">
                                KL
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 badge-pro shadow">
                            PRO
                        </span>

                        {{-- Botão de Favorito --}}
                        <button 
                            type="button" 
                            x-data="{ isFav: false }"
                            x-init="
                                isFav = window.isFavorite ? window.isFavorite({{ $product->id }}) : false;
                                window.addEventListener('favorites-updated', () => { 
                                    if (window.isFavorite) isFav = window.isFavorite({{ $product->id }});
                                });
                            "
                            aria-label="Favoritar {{ $product->title }}"
                            @click.prevent="
                                if (window.toggleFavorite) {
                                    isFav = window.toggleFavorite({
                                        id: {{ $product->id }},
                                        title: {{ json_encode($product->title) }},
                                        slug: {{ json_encode($product->slug) }},
                                        price: {{ (float) $product->price }},
                                        cover_image: {{ json_encode($product->cover_image) }},
                                        category: {{ json_encode($product->categoryRelation?->name ?? 'Sistema Web') }}
                                    });
                                }
                            "
                            :class="isFav ? 'text-pink-500' : 'text-slate-400 hover:text-pink-500'"
                            class="absolute top-3 right-3 grid h-8 w-8 place-items-center rounded-full bg-slate-900/80 hover:bg-slate-900 shadow-sm transition-transform duration-200 active:scale-90 backdrop-blur-sm z-10 cursor-pointer"
                        >
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    </a>

                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="font-display text-lg font-bold text-slate-900">
                            <a href="{{ route('storefront.show', $product) }}" class="hover:text-blue-700 transition">
                                {{ $product->title }}
                            </a>
                        </h3>
                        <p class="mt-2.5 line-clamp-3 text-sm leading-relaxed text-slate-600">
                            {{ $product->description }}
                        </p>

                        <div class="mt-auto flex items-center justify-between gap-4 pt-6 border-t border-slate-100">
                            <div>
                                <span class="text-[10px] uppercase font-mono text-slate-500 font-semibold">Valor Unitário</span>
                                @if((float) $product->price <= 0)
                                    <p class="font-display text-xl font-extrabold text-emerald-600">
                                        GRÁTIS
                                    </p>
                                @else
                                    <p class="font-display text-xl font-extrabold text-slate-900">
                                        R$ {{ number_format((float) $product->price, 2, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button" 
                                    @click.prevent="window.addToCart({
                                        id: {{ $product->id }},
                                        title: {{ json_encode($product->title) }},
                                        slug: {{ json_encode($product->slug) }},
                                        price: {{ (float) $product->price }},
                                        cover_image: {{ json_encode($product->cover_image) }},
                                        category: {{ json_encode($product->categoryRelation?->name ?? 'Sistema Web') }}
                                    })"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-teal-200 bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white transition cursor-pointer"
                                    title="Adicionar ao Carrinho"
                                    aria-label="Adicionar ao carrinho"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </button>
                                <a href="{{ route('storefront.show', $product) }}" class="btn-primary">
                                    Ver Detalhes &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel col-span-full p-12 text-center">
                    <p class="font-display text-xl font-bold text-slate-800">Nenhum produto encontrado.</p>
                    <p class="mt-2 text-sm text-slate-600">Tente buscar por outros termos ou explore todos os itens disponíveis.</p>
                    <a href="{{ route('storefront.index') }}" class="btn-teal mt-6">
                        Ver todos os produtos
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </section>

    {{-- 6. LIST SECTION: FEED DE ÚLTIMAS ATUALIZAÇÕES (Matches list_section in reference image) --}}
    <section id="atualizacoes" class="page-container scroll-mt-24 py-12">
        <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
            <div class="h-6 w-1.5 rounded-full bg-emerald-500"></div>
            <div>
                <h2 class="font-display text-2xl font-bold text-slate-900">Últimas Atualizações & Adições</h2>
                <p class="text-xs text-slate-500">Feed cronológico de materiais e novidades inseridos no catálogo.</p>
            </div>
        </div>

        <div class="mt-6 panel divide-y divide-slate-100 overflow-hidden">
            @forelse($recentUpdates as $update)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 sm:px-6 hover:bg-slate-50/80 transition">
                    <div class="flex items-center gap-4">
                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-slate-900 text-teal-400 font-mono text-xs font-bold">
                            KL
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="badge-new">NOVO</span>
                                <h3 class="font-display text-sm font-bold text-slate-900">
                                    <a href="{{ route('storefront.show', $update) }}" class="hover:text-teal-700 transition">
                                        {{ $update->title }}
                                    </a>
                                </h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                                {{ $update->description }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-4 shrink-0">
                        <span class="text-xs font-mono font-bold text-slate-700">
                            R$ {{ number_format((float) $update->price, 2, ',', '.') }}
                        </span>
                        <a href="{{ route('storefront.show', $update) }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 underline">
                            Acessar &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-slate-500">
                    Nenhuma atualização registrada recentemente.
                </div>
            @endforelse
        </div>
    </section>

    {{-- 7. INSTITUTIONAL TRUST & COMPARISON SECTION (Adapted from pricing section in reference image) --}}
    <section id="vantagens" class="scroll-mt-24 border-t border-slate-200 bg-gradient-to-b from-slate-900 to-slate-950 py-16 text-white">
        <div class="page-container">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-teal-500/30 bg-teal-500/10 px-4 py-1.5 text-xs font-mono font-semibold uppercase tracking-widest text-teal-300">
                    GARANTIA & AGILIDADE
                </span>
                <h2 class="mt-4 font-display text-3xl sm:text-4xl font-extrabold text-white">
                    Por que adquirir na KL Tecnologia?
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-300">
                    Sem assinaturas recorrentes ou burocracia. Você compra uma única vez e garante entrega direta e segura.
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                {{-- Comparison Card 1: Mercado Geral --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 text-slate-300">
                    <span class="text-xs font-mono uppercase tracking-wider text-slate-400 font-bold">O modo tradicional</span>
                    <h3 class="mt-2 font-display text-xl font-bold text-slate-300">Links manuais ou marketplaces</h3>
                    <ul class="mt-6 space-y-3.5 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="text-red-400 font-bold">&times;</span>
                            <span>Links do Google Drive/Mega que expiram ou são derrubados.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-400 font-bold">&times;</span>
                            <span>Liberação manual e lenta após o pagamento.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-400 font-bold">&times;</span>
                            <span>Arquivos sem procedência comprovada ou cheios de anúncios.</span>
                        </li>
                    </ul>
                </div>

                {{-- Comparison Card 2: KL Tecnologia --}}
                <div class="relative rounded-3xl border-2 border-teal-500 bg-gradient-to-b from-slate-900 via-teal-950/40 to-slate-900 p-8 shadow-2xl shadow-teal-500/10">
                    <div class="absolute -top-3 right-6 rounded-full bg-teal-500 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-slate-950 shadow">
                        DIFERENCIAL KL
                    </div>
                    <span class="text-xs font-mono uppercase tracking-wider text-teal-400 font-bold">Padrão Profissional</span>
                    <h3 class="mt-2 font-display text-xl font-bold text-white">E-commerce Digital KL Tecnologia</h3>
                    <ul class="mt-6 space-y-3.5 text-sm text-slate-200">
                        <li class="flex items-start gap-3">
                            <span class="text-teal-400 font-bold">&check;</span>
                            <span><strong>Download Imediato:</strong> Webhook automático que libera o arquivo assim que o Pix é pago.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-teal-400 font-bold">&check;</span>
                            <span><strong>Entrega Protegida:</strong> Arquivos armazenados em disco privado, entregues por URLs temporárias e assinadas.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-teal-400 font-bold">&check;</span>
                            <span><strong>Histórico Seguro:</strong> Seus downloads ficam sempre salvos na sua conta de cliente.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</x-storefront-layout>

