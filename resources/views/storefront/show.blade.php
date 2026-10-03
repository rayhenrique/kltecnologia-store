<x-storefront-layout 
    :title="$product->seo_title ?: $product->title . ' — KL Tecnologia'"
    :meta-description="$product->meta_description ?: ($product->short_description ?: Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($product->description))), 155, '...'))"
    :og-image="$product->cover_path ? asset($product->cover_path) : null"
    og-type="product"
    :canonical="route('storefront.show', $product->slug)"
>
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@graph' => array_values(array_filter([
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Início',
                    'item' => route('storefront.index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Catálogo',
                    'item' => route('catalog.index'),
                ],
                $product->categoryGroup ? [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $product->categoryGroup->name,
                    'item' => route('catalog.category', $product->categoryGroup->slug),
                ] : null,
                [
                    '@type' => 'ListItem',
                    'position' => $product->categoryGroup ? 4 : 3,
                    'name' => $product->title,
                    'item' => route('storefront.show', $product->slug),
                ],
            ])),
        ],
        array_filter([
            '@type' => 'Product',
            '@id' => route('storefront.show', $product->slug) . '#product',
            'name' => $product->title,
            'description' => $product->short_description ?: Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($product->description))), 250),
            'image' => $product->cover_path ? asset($product->cover_path) : null,
            'sku' => 'KL-' . $product->id,
            'category' => $product->categoryGroup?->name ?? $product->category ?? null,
            'brand' => $product->brand ? [
                '@type' => 'Brand',
                'name' => $product->brand,
            ] : null,
            'offers' => [
                '@type' => 'Offer',
                'url' => route('storefront.show', $product->slug),
                'priceCurrency' => 'BRL',
                'price' => number_format((float) $product->price, 2, '.', ''),
                'availability' => 'https://schema.org/InStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'KL Tecnologia',
                ],
            ],
        ], fn ($value) => $value !== null && $value !== ''),
    ])),
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

    {{-- Page Header Banner --}}
    <section class="hero-tech-bg border-b border-slate-800 text-white py-8 lg:py-12 relative overflow-hidden">
        <div class="page-container relative z-10">
            {{-- Breadcrumbs --}}
            <nav aria-label="Breadcrumb" class="flex items-center flex-wrap gap-2 text-xs sm:text-sm text-slate-400">
                <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition">Início</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('catalog.index') }}" class="hover:text-teal-400 transition">Catálogo</a>
                @if($product->categoryGroup)
                    <span class="text-slate-600">/</span>
                    <a href="{{ route('catalog.category', $product->categoryGroup->slug) }}" class="hover:text-teal-400 transition">{{ $product->categoryGroup->name }}</a>
                @elseif($product->category)
                    <span class="text-slate-600">/</span>
                    <span class="text-slate-400">{{ $product->category }}</span>
                @endif
                <span class="text-slate-600">/</span>
                <span class="text-teal-400 font-medium truncate max-w-[200px] sm:max-w-xs md:max-w-md">{{ $product->title }}</span>
            </nav>

            {{-- Badges & Title --}}
            <div class="mt-4 flex flex-wrap items-center gap-2">
                @if($product->includes_source_code)
                    <span class="badge-new">
                        ★ Código Fonte Incluso
                    </span>
                @endif
                <span class="inline-flex items-center gap-1 rounded bg-slate-800 border border-slate-700 px-2 py-0.5 text-[11px] font-semibold text-slate-300">
                    ⚡ Entrega Imediata
                </span>
                @if($product->lifetime_access)
                    <span class="inline-flex items-center gap-1 rounded bg-slate-800 border border-slate-700 px-2 py-0.5 text-[11px] font-semibold text-slate-300">
                        ♾️ Uso Vitalício
                    </span>
                @endif
                @if($product->license)
                    <span class="inline-flex items-center gap-1 rounded bg-slate-800 border border-slate-700 px-2 py-0.5 text-[11px] font-semibold text-slate-300">
                        📜 {{ $product->license }}
                    </span>
                @endif
                <span class="text-xs text-slate-400 ml-auto hidden sm:inline-block">
                    Atualizado em {{ $product->updated_at->format('d/m/Y') }}
                </span>
            </div>

            <h1 class="mt-4 font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-snug max-w-4xl">
                {{ $product->title }}
            </h1>
            @if($product->short_description)
                <p class="mt-2 text-sm sm:text-base text-slate-300 max-w-3xl leading-relaxed">
                    {{ $product->short_description }}
                </p>
            @endif
        </div>
    </section>

    {{-- Main Content Grid (2 Columns) --}}
    <section class="page-container py-10 lg:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            
            {{-- Coluna Esquerda (~68%): Capa, Destaque e Abas --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- Imagem de Capa do Produto --}}
                <div class="panel overflow-hidden border border-slate-200/80 shadow-md bg-white rounded-2xl">
                    <div class="relative bg-slate-900 group">
                        @if($product->cover_path)
                            <img 
                                src="{{ asset($product->cover_path) }}" 
                                alt="{{ $product->title }}" 
                                width="800" 
                                height="500" 
                                fetchpriority="high" 
                                decoding="async" 
                                class="w-full aspect-[16/10] object-cover object-center group-hover:scale-[1.01] transition duration-300"
                            />
                        @else
                            <div class="grid aspect-[16/10] w-full place-items-center bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 font-display text-6xl font-bold text-teal-400">
                                <span>KL</span>
                            </div>
                        @endif

                        @if($product->license)
                            <div class="absolute top-4 left-4">
                                <span class="rounded-lg bg-slate-950/80 backdrop-blur-md border border-slate-700/60 px-3 py-1.5 text-xs font-bold text-teal-300 shadow-lg">
                                    ★ {{ $product->license }}
                                </span>
                            </div>
                        @elseif($product->includes_source_code)
                            <div class="absolute top-4 left-4">
                                <span class="rounded-lg bg-slate-950/80 backdrop-blur-md border border-slate-700/60 px-3 py-1.5 text-xs font-bold text-teal-300 shadow-lg">
                                    ★ Código Fonte Aberto
                                </span>
                            </div>
                        @endif

                        <div class="absolute top-4 right-4">
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
                                @click="
                                    if (window.toggleFavorite) {
                                        isFav = window.toggleFavorite({
                                            id: {{ $product->id }},
                                            title: {{ json_encode($product->title) }},
                                            slug: {{ json_encode($product->slug) }},
                                            price: {{ (float) $product->price }},
                                            cover_image: {{ json_encode($product->cover_path ? asset($product->cover_path) : null) }},
                                            category: {{ json_encode($product->categoryGroup?->name ?? $product->category ?? 'Sistema Web') }}
                                        });
                                    }
                                "
                                :class="isFav ? 'text-pink-500 bg-white' : 'text-slate-300 hover:text-pink-500 bg-slate-950/80 hover:bg-slate-900'"
                                class="grid h-10 w-10 place-items-center rounded-xl backdrop-blur-md border border-slate-700/60 shadow-lg transition cursor-pointer group/fav"
                                title="Salvar nos Favoritos"
                            >
                                <svg class="h-5 w-5 fill-current group-hover/fav:scale-110 transition-transform" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Box de Destaque / Garantia de Acesso --}}
                <div class="rounded-2xl border border-teal-200 bg-gradient-to-r from-teal-50 via-white to-emerald-50 p-5 text-teal-950 flex items-start gap-3.5 shadow-sm">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="font-display text-sm font-bold text-teal-950">
                            @if($product->lifetime_access && $product->includes_source_code)
                                Acesso Vitalício & Código-Fonte Incluso
                            @elseif($product->lifetime_access)
                                Acesso Vitalício & Entrega Digital Imediata
                            @elseif($product->includes_source_code)
                                Código-Fonte Incluso & Entrega Imediata
                            @else
                                Entrega Digital Imediata & Compra Segura
                            @endif
                        </h2>
                        <p class="mt-1 text-xs sm:text-sm text-teal-800/90 leading-relaxed">
                            {{ $product->short_description ?: 'Ao adquirir este item, o download é liberado instantaneamente na sua conta após a confirmação. Arquivos verificados e prontos para utilização.' }}
                        </p>
                        @if($product->demo_url || $product->documentation_url)
                            <div class="mt-3 flex flex-wrap items-center gap-4 pt-2 border-t border-teal-200/60">
                                @if($product->demo_url)
                                    <a href="{{ $product->demo_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 hover:text-teal-900 underline">
                                        <span>Ver Demonstração Online</span>
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                @endif
                                @if($product->documentation_url)
                                    <a href="{{ $product->documentation_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 hover:text-teal-900 underline">
                                        <span>Documentação</span>
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Abas Interativas (Alpine.js) --}}
                <div x-data="{ tab: 'description' }" class="panel border border-slate-200/80 shadow-sm rounded-2xl overflow-hidden bg-white">
                    {{-- Tab Headers --}}
                    <div class="flex items-center border-b border-slate-200 bg-slate-50/75 px-4 pt-2 gap-2 overflow-x-auto">
                        <button 
                            type="button" 
                            @click="tab = 'description'" 
                            :class="tab === 'description' ? 'border-teal-600 text-teal-700 bg-white font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-medium'"
                            class="inline-flex items-center gap-2 border-b-2 py-3.5 px-4 text-sm transition whitespace-nowrap rounded-t-lg"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Descrição do Item
                        </button>

                        <button 
                            type="button" 
                            @click="tab = 'features'" 
                            :class="tab === 'features' ? 'border-teal-600 text-teal-700 bg-white font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-medium'"
                            class="inline-flex items-center gap-2 border-b-2 py-3.5 px-4 text-sm transition whitespace-nowrap rounded-t-lg"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Recursos & Requisitos
                        </button>

                        <button 
                            type="button" 
                            @click="tab = 'license'" 
                            :class="tab === 'license' ? 'border-teal-600 text-teal-700 bg-white font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-medium'"
                            class="inline-flex items-center gap-2 border-b-2 py-3.5 px-4 text-sm transition whitespace-nowrap rounded-t-lg"
                        >
                            <svg class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Licença, Suporte & Entrega
                        </button>
                    </div>

                    {{-- Tab 1: Descrição Detalhada --}}
                    <div x-show="tab === 'description'" class="p-6 sm:p-8 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="font-display text-lg font-bold text-slate-900">Sobre este produto</h3>
                            <span class="text-xs font-mono text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">ID: #{{ $product->id }}</span>
                        </div>

                        <div class="space-y-3 pt-2 text-slate-700">
                            @foreach(explode("\n", $product->description) as $line)
                                @php $trimmed = trim($line); @endphp
                                @if(empty($trimmed))
                                    {{-- Linha vazia --}}
                                @elseif(str_starts_with($trimmed, '###') || str_starts_with($trimmed, '##'))
                                    <div class="pt-5 pb-1 border-b border-slate-100">
                                        <h4 class="font-display text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                                            {{ ltrim($trimmed, '# ') }}
                                        </h4>
                                    </div>
                                @elseif(str_starts_with($trimmed, '•') || str_starts_with($trimmed, '-') || str_starts_with($trimmed, '*') || str_starts_with($trimmed, '✅'))
                                    <div class="flex items-start gap-3 pl-1 py-0.5">
                                        <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-teal-100 text-teal-700 text-xs font-bold mt-0.5">
                                            ✓
                                        </span>
                                        <span class="text-sm sm:text-base text-slate-700 leading-relaxed">{{ ltrim($trimmed, '•-* ✅') }}</span>
                                    </div>
                                @else
                                    <p class="text-sm sm:text-base text-slate-700 leading-relaxed">{{ $trimmed }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab 2: Recursos & Requisitos Técnicos --}}
                    <div x-show="tab === 'features'" x-cloak class="p-6 sm:p-8 space-y-6">
                        @if($product->requirements)
                            <div>
                                <h3 class="font-display text-lg font-bold text-slate-900">Requisitos do Produto</h3>
                                <div class="mt-3 p-4 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-mono">
{{ $product->requirements }}
                                </div>
                            </div>
                        @endif

                        @if($product->features)
                            <div>
                                <h3 class="font-display text-lg font-bold text-slate-900">Recursos & Funcionalidades</h3>
                                <div class="mt-3 p-4 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
{{ $product->features }}
                                </div>
                            </div>
                        @endif

                        @if(!$product->requirements && !$product->features)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-center">
                                <p class="text-sm text-slate-600">Consulte a descrição do item acima ou entre em contato com nosso atendimento para tirar dúvidas de compatibilidade e instalação.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tab 3: Licença, Suporte & Entrega (Factual, sem avaliações fictícias) --}}
                    <div x-show="tab === 'license'" x-cloak class="p-6 sm:p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            @if($product->license)
                                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 space-y-2">
                                    <div class="flex items-center gap-2 text-teal-700 font-bold text-sm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>Licença de Uso</span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                        {{ $product->license }}
                                    </p>
                                </div>
                            @endif

                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 space-y-2">
                                <div class="flex items-center gap-2 text-teal-700 font-bold text-sm">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <span>Suporte & Dúvidas</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    {{ $product->support_info ?: 'Atendimento via WhatsApp e e-mail para suporte de acesso aos arquivos e esclarecimento de dúvidas.' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 space-y-2">
                                <div class="flex items-center gap-2 text-teal-700 font-bold text-sm">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    <span>Entrega Imediata</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Download 100% digital liberado imediatamente na sua conta no menu Meus Downloads após confirmação de pagamento.
                                </p>
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Precisa de atendimento pré-venda ou requisitos específicos?</span>
                            <a href="https://wa.me/5582996304742?text={{ urlencode('Olá! Gostaria de falar sobre o produto: ' . $product->title) }}" target="_blank" rel="noopener noreferrer" class="btn-secondary text-xs sm:text-sm">
                                Tirar Dúvidas com Especialista
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Coluna Direita (~32%): Sidebar Sticky com Compra e Ficha Técnica --}}
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                {{-- Card de Compra (Purchase Card) --}}
                <div id="card-compra" class="panel rounded-2xl border-2 border-teal-500/40 bg-white p-6 shadow-xl shadow-teal-950/5 relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-teal-500/10 blur-xl pointer-events-none"></div>

                    {{-- Top Flag --}}
                    <div class="flex items-center justify-between">
                        @if((float) $product->price <= 0)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-800 px-3 py-0.5 text-xs font-bold uppercase tracking-wider">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                100% Gratuito
                            </span>
                            <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                                ⚡ Download Imediato
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-teal-100 text-teal-800 px-3 py-0.5 text-xs font-bold uppercase tracking-wider">
                                <span class="h-1.5 w-1.5 rounded-full bg-teal-600"></span>
                                Pagamento Único
                            </span>
                            <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                                ⚡ Envio Imediato
                            </span>
                        @endif
                    </div>

                    {{-- Price Display --}}
                    <div class="mt-5 border-y border-slate-100 py-4">
                        @if((float) $product->price <= 0)
                            <div class="mt-1 flex items-baseline gap-2">
                                <span class="font-display text-3xl sm:text-4xl font-extrabold text-emerald-600 tracking-tight">
                                    GRÁTIS
                                </span>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500">
                                @if($product->lifetime_access)
                                    Sem cobrança. Acesso instantâneo e vitalício após o cadastro.
                                @else
                                    Sem cobrança. Acesso instantâneo após o cadastro.
                                @endif
                            </p>
                        @else
                            <div class="mt-1 flex items-baseline gap-2">
                                <span class="font-display text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-tight">
                                    R$ {{ number_format((float) $product->price, 2, ',', '.') }}
                                </span>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500">
                                Disponível no Pix à vista ou parcelado no Cartão de Crédito.
                            </p>
                        @endif
                    </div>

                    {{-- Buttons: [+] Adicionar ao Carrinho & Comprar --}}
                    <div class="mt-5 space-y-3">
                        <div class="flex items-center gap-2.5">
                            {{-- Botão [+] Adicionar ao Carrinho --}}
                            <button 
                                type="button"
                                @click="window.addToCart({
                                    id: {{ $product->id }},
                                    title: {{ json_encode($product->title) }},
                                    slug: {{ json_encode($product->slug) }},
                                    price: {{ (float) $product->price }},
                                    cover_image: {{ json_encode($product->cover_path ? asset($product->cover_path) : null) }},
                                    category: {{ json_encode($product->categoryGroup?->name ?? $product->category ?? 'Sistema Web') }}
                                })"
                                class="inline-flex h-[52px] w-[52px] items-center justify-center rounded-2xl border border-teal-500/40 bg-teal-500/10 hover:bg-teal-500/20 text-teal-400 hover:text-teal-300 transition shadow-sm cursor-pointer shrink-0 group"
                                title="Adicionar ao Carrinho (+)"
                                aria-label="Adicionar ao carrinho"
                            >
                                <svg class="h-6 w-6 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>

                            {{-- Botão Favoritar --}}
                            <button 
                                type="button"
                                x-data="{ isFav: false }"
                                x-init="
                                    isFav = window.isFavorite ? window.isFavorite({{ $product->id }}) : false;
                                    window.addEventListener('favorites-updated', () => { 
                                        if (window.isFavorite) isFav = window.isFavorite({{ $product->id }});
                                    });
                                "
                                @click="
                                    if (window.toggleFavorite) {
                                        isFav = window.toggleFavorite({
                                            id: {{ $product->id }},
                                            title: {{ json_encode($product->title) }},
                                            slug: {{ json_encode($product->slug) }},
                                            price: {{ (float) $product->price }},
                                            cover_image: {{ json_encode($product->cover_path ? asset($product->cover_path) : null) }},
                                            category: {{ json_encode($product->categoryGroup?->name ?? $product->category ?? 'Sistema Web') }}
                                        });
                                    }
                                "
                                :class="isFav ? 'text-pink-500 bg-pink-50 border-pink-200' : 'text-slate-500 hover:text-pink-500 bg-slate-50 hover:bg-pink-50/50 border-slate-200'"
                                class="inline-flex h-[52px] w-[52px] items-center justify-center rounded-2xl border transition shadow-sm cursor-pointer shrink-0 group"
                                :title="isFav ? 'Remover dos Favoritos' : 'Salvar nos Favoritos'"
                                aria-label="Favoritar produto"
                            >
                                <svg class="h-6 w-6 fill-current group-hover:scale-110 transition" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>

                            {{-- Botão Comprar / Baixar Grátis (Direto para o Checkout Seguro) --}}
                            <a 
                                href="{{ route('checkout.index', ['product' => $product->slug]) }}" 
                                class="flex-1 {{ (float) $product->price <= 0 ? 'bg-emerald-700 hover:bg-emerald-800 text-white shadow-emerald-700/30' : 'btn-teal shadow-teal-700/30' }} text-base !h-[52px] px-6 font-bold shadow-lg rounded-xl flex items-center justify-center gap-2 group transition"
                            >
                                <svg class="h-5 w-5 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ (float) $product->price <= 0 ? 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4' : 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z' }}" />
                                </svg>
                                <span>{{ (float) $product->price <= 0 ? 'Baixar Grátis' : 'Comprar Agora' }}</span>
                            </a>
                        </div>

                        {{-- Botão de WhatsApp --}}
                        <a 
                            href="https://wa.me/5582996304742?text={{ urlencode('Olá! Gostaria de tirar dúvidas sobre o produto: ' . $product->title) }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl border border-emerald-500/40 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold py-3.5 px-4 text-sm transition shadow-sm"
                        >
                            <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.062-2.18-.553-1.614-.666-2.736-2.3-2.825-2.42-.089-.12-1.042-1.385-1.042-2.641 0-1.256.657-1.874.887-2.13.23-.257.51-.322.68-.322.17 0 .34.003.49.01.157.009.366-.06.574.44.214.512.73 1.776.794 1.905.064.13.107.28.021.451-.085.17-.128.277-.255.426-.128.149-.268.332-.383.447-.128.128-.261.267-.112.523.149.256.662 1.089 1.42 1.764.975.869 1.796 1.139 2.052 1.267.256.128.405.107.554-.064.15-.17.639-.746.81-1.002.17-.256.341-.213.575-.128.234.085 1.491.703 1.747.831.256.128.426.192.49.3.064.106.064.618-.08 1.023z"/>
                            </svg>
                            Tirar Dúvidas no WhatsApp
                        </a>
                    </div>

                    {{-- Security & Delivery Checklist --}}
                    <div class="mt-6 border-t border-slate-100 pt-5 space-y-2.5 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Download liberado automaticamente em <strong>Meus Downloads</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Pagamento seguro via <strong>Mercado Pago</strong> (Pix ou Cartão)</span>
                        </div>
                        @if($product->includes_source_code)
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Código-fonte disponível para customização</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Informações do Produto (Product Info Card) --}}
                <div class="panel rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">
                        Informações do Produto
                    </h3>

                    <dl class="mt-4 divide-y divide-slate-100 text-xs sm:text-sm">
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Categoria</dt>
                            <dd class="font-semibold text-slate-900">
                                @if($product->categoryGroup)
                                    <a href="{{ route('catalog.category', $product->categoryGroup->slug) }}" class="text-teal-700 hover:underline">
                                        {{ $product->categoryGroup->name }}
                                    </a>
                                @else
                                    {{ $product->category ?? 'Produto Digital' }}
                                @endif
                            </dd>
                        </div>
                        @if($product->brand)
                            <div class="flex justify-between py-2.5">
                                <dt class="text-slate-500 font-medium">Marca / Autor</dt>
                                <dd class="font-semibold text-slate-900">{{ $product->brand }}</dd>
                            </div>
                        @endif
                        @if($product->product_type)
                            <div class="flex justify-between py-2.5">
                                <dt class="text-slate-500 font-medium">Tipo</dt>
                                <dd class="font-semibold text-slate-900">{{ $product->product_type }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Atualizado</dt>
                            <dd class="font-semibold text-slate-900">{{ $product->updated_at->format('d/m/Y') }}</dd>
                        </div>
                        @if($product->license)
                            <div class="flex justify-between py-2.5">
                                <dt class="text-slate-500 font-medium">Licença</dt>
                                <dd class="font-semibold text-teal-700">{{ $product->license }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Entrega</dt>
                            <dd class="font-semibold text-emerald-600">Download Imediato</dd>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Arquivos</dt>
                            <dd class="font-semibold text-slate-900">
                                {{ $product->includes_source_code ? 'Código Fonte Incluso' : 'Arquivos Digitais' }}
                            </dd>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Versão</dt>
                            <dd class="font-semibold text-slate-900">{{ $product->version ?? '1.0' }}</dd>
                        </div>
                    </dl>
                </div>

            </div>

        </div>
    </section>

    {{-- Trust Badges Section ("Por que comprar na KL Tecnologia?") --}}
    <section class="border-y border-slate-200 bg-white py-14">
        <div class="page-container">
            <div class="text-center max-w-2xl mx-auto">
                <span class="eyebrow">Segurança & Confiabilidade</span>
                <h2 class="mt-2 font-display text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    Por que comprar na KL Tecnologia?
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Garantimos uma experiência segura, transparente e com entrega digital automatizada.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                {{-- Badge 1 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Compra Segura</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Ambiente 100% criptografado com checkout oficial Mercado Pago.
                    </p>
                </div>

                {{-- Badge 2 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Acesso Imediato</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Arquivos liberados na sua conta assim que o pagamento é aprovado.
                    </p>
                </div>

                {{-- Badge 3 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Suporte Dedicado</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Atendimento direto via WhatsApp e e-mail para tirar dúvidas.
                    </p>
                </div>

                {{-- Badge 4 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Arquivos Verificados</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Downloads diretos, completos e testados para máxima confiabilidade.
                    </p>
                </div>

                {{-- Badge 5 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Pagamento Transparente</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Valores claros sem mensalidades ocultas ou surpresas adicionais.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Products Section ("Você pode gostar") --}}
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="page-container py-14">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="eyebrow">Recomendações</span>
                    <h2 class="mt-1 font-display text-2xl font-bold text-slate-900 tracking-tight">
                        Você pode gostar
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Outros scripts, sistemas e ferramentas selecionados para acelerar seus projetos.
                    </p>
                </div>
                <a href="{{ route('catalog.index') }}" class="btn-secondary text-xs sm:text-sm font-semibold">
                    Ver Catálogo Completo →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <article class="panel group flex flex-col justify-between overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-lg hover:border-teal-500/50 transition duration-200 rounded-2xl bg-white">
                        <div>
                            {{-- Cover --}}
                            <div class="relative aspect-[16/10] overflow-hidden bg-slate-900">
                                @if($related->cover_path)
                                    <img 
                                        src="{{ asset($related->cover_path) }}" 
                                        alt="{{ $related->title }}" 
                                        loading="lazy"
                                        class="h-full w-full object-cover group-hover:scale-105 transition duration-300"
                                    />
                                @else
                                    <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-800 to-teal-950 font-display text-3xl font-bold text-teal-400">
                                        KL
                                    </div>
                                @endif
                                @if($related->includes_source_code)
                                    <div class="absolute top-2.5 left-2.5">
                                        <span class="badge-hot">Código Fonte</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-teal-700">
                                    {{ $related->categoryGroup?->name ?? $related->category ?? 'Produto Digital' }}
                                </span>
                                <h3 class="mt-1 font-display text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-teal-700 transition">
                                    <a href="{{ route('storefront.show', $related) }}">
                                        {{ $related->title }}
                                    </a>
                                </h3>
                                <p class="mt-1.5 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $related->short_description ?: Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($related->description))), 80) }}
                                </p>
                            </div>
                        </div>

                        {{-- Footer / Price --}}
                        <div class="p-4 pt-0 border-t border-slate-100 flex items-center justify-between mt-3">
                            <div>
                                @if((float) $related->price <= 0)
                                    <p class="font-display text-base font-extrabold text-emerald-600">
                                        GRÁTIS
                                    </p>
                                @else
                                    <p class="font-display text-base font-extrabold text-slate-950">
                                        R$ {{ number_format((float) $related->price, 2, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('storefront.show', $related) }}" class="inline-flex items-center justify-center rounded-xl bg-slate-100 hover:bg-teal-50 text-slate-700 hover:text-teal-700 font-bold px-3 py-1.5 text-xs transition">
                                Ver Detalhes
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Dicas do Blog / Artigos Relacionados --}}
    @if(isset($relatedPosts) && $relatedPosts->isNotEmpty())
        <section class="border-t border-slate-200 bg-slate-50 py-14">
            <div class="page-container">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="eyebrow">Conhecimento & Guias</span>
                        <h2 class="mt-1 font-display text-2xl font-bold text-slate-900 tracking-tight">
                            Do Blog & Guias Técnicos
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Conteúdo prático para você implementar e potencializar suas soluções digitais.
                        </p>
                    </div>
                    <a href="{{ route('blog.index') }}" class="btn-secondary text-xs sm:text-sm font-semibold">
                        Ver Todos os Artigos →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $post)
                        <article class="panel overflow-hidden border border-slate-200/80 bg-white rounded-2xl shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                @if($post->cover_path)
                                    <div class="h-44 overflow-hidden bg-slate-900">
                                        <img src="{{ asset($post->cover_path) }}" alt="{{ $post->title }}" loading="lazy" class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="h-44 bg-gradient-to-br from-teal-900 via-slate-900 to-slate-950 p-5 flex flex-col justify-end text-white">
                                        <span class="text-xs font-mono text-teal-400 font-bold uppercase">{{ $post->blogCategory?->name ?? $post->category ?? 'Artigo' }}</span>
                                    </div>
                                @endif
                                <div class="p-5">
                                    <span class="text-[11px] font-mono text-teal-700 font-bold uppercase">{{ $post->blogCategory?->name ?? $post->category ?? 'Artigo' }}</span>
                                    <h3 class="font-display text-base font-bold mt-1 text-slate-900 hover:text-teal-700 transition">
                                        <a href="{{ route('blog.show', $post) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-2 text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                        {{ $post->excerpt }}
                                    </p>
                                </div>
                            </div>
                            <div class="p-5 pt-0">
                                <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 hover:text-teal-800">
                                    Ler artigo completo →
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Dúvidas Frequentes (FAQ Accordion) --}}
    <section class="page-container py-14">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="eyebrow">Dúvidas Frequentes</span>
            <h2 class="mt-2 font-display text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Perguntas Frequentes sobre a Compra
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                Tire suas dúvidas antes de finalizar sua aquisição na KL Tecnologia.
            </p>
        </div>

        <div x-data="{ openFaq: 1 }" class="max-w-3xl mx-auto space-y-3">
            {{-- FAQ 1 --}}
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <button 
                    type="button" 
                    @click="openFaq = openFaq === 1 ? null : 1" 
                    class="w-full flex items-center justify-between p-5 text-left font-display text-sm sm:text-base font-bold text-slate-900 hover:text-teal-700 transition"
                >
                    <span>Como recebo o produto após a confirmação do pagamento?</span>
                    <svg :class="openFaq === 1 ? 'rotate-180 text-teal-600' : 'text-slate-400'" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openFaq === 1" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    A entrega é 100% digital e imediata! Assim que o pagamento for aprovado (Pix ou Cartão), o download fica liberado automaticamente na sua conta no menu <strong>Meus Downloads</strong>.
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <button 
                    type="button" 
                    @click="openFaq = openFaq === 2 ? null : 2" 
                    class="w-full flex items-center justify-between p-5 text-left font-display text-sm sm:text-base font-bold text-slate-900 hover:text-teal-700 transition"
                >
                    <span>Este produto acompanha código-fonte para modificação?</span>
                    <svg :class="openFaq === 2 ? 'rotate-180 text-teal-600' : 'text-slate-400'" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openFaq === 2" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Os produtos que incluem código-fonte têm essa especificação expressa na ficha técnica e nos destaques do anúncio. Para outros itens ou ferramentas fechadas, consulte os requisitos e detalhes de cada produto.
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <button 
                    type="button" 
                    @click="openFaq = openFaq === 3 ? null : 3" 
                    class="w-full flex items-center justify-between p-5 text-left font-display text-sm sm:text-base font-bold text-slate-900 hover:text-teal-700 transition"
                >
                    <span>Como funcionam a licença e o suporte?</span>
                    <svg :class="openFaq === 3 ? 'rotate-180 text-teal-600' : 'text-slate-400'" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openFaq === 3" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    As condições de licença e suporte de cada item estão detalhadas na aba "Licença, Suporte & Entrega" e na ficha do produto. Para dúvidas adicionais, conte com nosso suporte direto pelo WhatsApp oficial <strong>(82) 99630-4742</strong>.
                </div>
            </div>
        </div>
    </section>

    {{-- Mobile Sticky Bottom Bar for Quick Purchase --}}
    <div class="fixed bottom-0 inset-x-0 z-30 bg-slate-950/95 backdrop-blur-md border-t border-slate-800 p-3 lg:hidden flex items-center justify-between gap-3 shadow-2xl">
        <div>
            <div class="flex items-baseline gap-1.5">
                @if((float) $product->price <= 0)
                    <span class="font-display text-lg font-black text-emerald-400">
                        GRÁTIS
                    </span>
                @else
                    <span class="font-display text-lg font-black text-white">
                        R$ {{ number_format($product->price, 2, ',', '.') }}
                    </span>
                @endif
            </div>
            <span class="text-[10px] text-emerald-400 font-mono flex items-center gap-1">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                {{ (float) $product->price <= 0 ? 'Download 100% Gratuito' : 'Pix / Download Imediato' }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button 
                type="button"
                @click="window.addToCart({
                    id: {{ $product->id }},
                    title: {{ json_encode($product->title) }},
                    slug: {{ json_encode($product->slug) }},
                    price: {{ (float) $product->price }},
                    cover_image: {{ json_encode($product->cover_path ? asset($product->cover_path) : null) }},
                    category: {{ json_encode($product->categoryGroup?->name ?? $product->category ?? 'Sistema Web') }}
                })"
                class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 border border-slate-700 text-teal-400 hover:bg-slate-800 transition cursor-pointer"
                title="Adicionar ao Carrinho"
                aria-label="Adicionar ao carrinho"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </button>
            <a 
                href="{{ route('checkout.index', ['product' => $product->slug]) }}" 
                class="{{ (float) $product->price <= 0 ? 'bg-emerald-700 hover:bg-emerald-800 text-white shadow-emerald-700/20' : 'btn-teal shadow-teal-700/20' }} rounded-xl !min-h-10 px-4 py-2 text-xs font-bold shadow-md flex items-center gap-1.5"
            >
                <span>{{ (float) $product->price <= 0 ? 'Baixar Grátis' : 'Comprar' }}</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>
</x-storefront-layout>
