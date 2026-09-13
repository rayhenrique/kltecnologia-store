<x-storefront-layout>
    <x-slot:title>Meus Favoritos</x-slot:title>

    <section 
        class="py-8 sm:py-12 bg-slate-950 min-h-[75vh]"
        x-data="{
            items: [],
            loading: true,
            csrfToken: '{{ csrf_token() }}',
            init() {
                this.loadFavorites();
                window.addEventListener('favorites-updated', () => {
                    this.loadFavorites();
                });
            },
            async loadFavorites() {
                try {
                    const stored = JSON.parse(localStorage.getItem('kl_favorites') || '[]');
                    if (!Array.isArray(stored) || stored.length === 0) {
                        this.items = [];
                        this.loading = false;
                        return;
                    }

                    // Se já forem objetos completos, renderiza preliminarmente
                    if (typeof stored[0] === 'object' && stored[0] !== null && stored[0].title) {
                        this.items = stored;
                    }

                    // Extrai IDs numéricos
                    const ids = stored.map(item => typeof item === 'object' ? item.id : item).filter(Boolean);

                    if (ids.length > 0) {
                        const response = await fetch('{{ route('favorites.items') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ ids: ids })
                        });

                        if (response.ok) {
                            const freshData = await response.json();
                            if (Array.isArray(freshData)) {
                                this.items = freshData;
                                // Atualiza localStorage com os dados normalizados
                                localStorage.setItem('kl_favorites', JSON.stringify(freshData));
                            }
                        }
                    }
                } catch (e) {
                    console.error('Erro ao carregar favoritos:', e);
                } finally {
                    this.loading = false;
                }
            },
            removeItem(id) {
                this.items = this.items.filter(item => item.id !== id);
                localStorage.setItem('kl_favorites', JSON.stringify(this.items));
                window.dispatchEvent(new CustomEvent('favorites-updated', { 
                    detail: { count: this.items.length, favorites: this.items } 
                }));
                window.dispatchEvent(new CustomEvent('toast-message', {
                    detail: {
                        message: 'Item removido dos Favoritos.',
                        type: 'info',
                        cartUrl: ''
                    }
                }));
            },
            clearAll() {
                if (confirm('Tem certeza que deseja remover todos os itens dos seus favoritos?')) {
                    this.items = [];
                    localStorage.removeItem('kl_favorites');
                    window.dispatchEvent(new CustomEvent('favorites-updated', { 
                        detail: { count: 0, favorites: [] } 
                    }));
                    window.dispatchEvent(new CustomEvent('toast-message', {
                        detail: {
                            message: 'Lista de favoritos limpa com sucesso!',
                            type: 'info',
                            cartUrl: ''
                        }
                    }));
                }
            },
            addToCart(item) {
                window.addToCart({
                    id: item.id,
                    title: item.title,
                    slug: item.slug,
                    price: parseFloat(item.price) || 0,
                    formatted_price: item.formatted_price,
                    cover_image: item.cover_image,
                    category: item.category || 'Sistema Web'
                });
            }
        }"
    >
        <div class="page-container">
            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-slate-400 mb-6">
                <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Início
                </a>
                <span>/</span>
                <span class="text-pink-400 font-semibold">Meus Favoritos</span>
            </nav>

            {{-- Header da Página --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800/80 pb-6 mb-8">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-400 shadow-sm">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Meus Favoritos
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-400 mt-0.5">
                                Gerencie os scripts e sistemas que você guardou para conferir ou adquirir depois.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Ações no Topo --}}
                <div class="flex items-center gap-3" x-show="items.length > 0" x-cloak>
                    <span class="rounded-full bg-slate-900 border border-slate-800 px-3 py-1 text-xs font-mono font-semibold text-slate-300">
                        <span x-text="items.length" class="text-pink-400 font-bold"></span> 
                        <span x-text="items.length === 1 ? 'produto salvo' : 'produtos salvos'"></span>
                    </span>
                    <button 
                        type="button" 
                        @click="clearAll()" 
                        class="inline-flex items-center gap-1.5 rounded-xl border border-rose-500/30 bg-rose-500/10 hover:bg-rose-500/20 px-3.5 py-1.5 text-xs font-semibold text-rose-300 hover:text-rose-200 transition cursor-pointer"
                        title="Limpar todos os favoritos"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Limpar Lista</span>
                    </button>
                </div>
            </div>

            {{-- Loading State --}}
            <div x-show="loading" class="text-center py-20">
                <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-slate-800 border-t-pink-500"></div>
                <p class="mt-4 text-sm font-semibold text-slate-400">Carregando seus favoritos...</p>
            </div>

            {{-- Empty State (Nenhum produto favoritado) --}}
            <div x-show="!loading && items.length === 0" x-cloak class="text-center py-16 px-4">
                <div class="mx-auto grid h-24 w-24 place-items-center rounded-3xl bg-gradient-to-b from-pink-500/15 to-transparent border border-pink-500/20 text-pink-400 shadow-xl shadow-pink-500/5 mb-6">
                    <svg class="h-12 w-12 text-pink-400/80 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="font-display text-xl sm:text-2xl font-bold text-white tracking-tight">
                    Sua lista de favoritos está vazia
                </h2>
                <p class="mt-2 text-sm text-slate-400 max-w-md mx-auto leading-relaxed">
                    Você ainda não guardou nenhum produto. Explore nosso catálogo com scripts, sistemas e infoprodutos com entrega imediata e clique no coração para salvá-los aqui!
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a 
                        href="{{ route('catalog.index') }}" 
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-500 px-6 py-3.5 text-sm font-bold text-slate-950 hover:bg-teal-400 transition shadow-lg shadow-teal-500/25"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Explorar Catálogo de Produtos
                    </a>
                    <a 
                        href="{{ route('storefront.index') }}" 
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-6 py-3.5 text-sm font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition"
                    >
                        Voltar ao Início
                    </a>
                </div>
            </div>

            {{-- Filled State (Grid com produtos favoritados) --}}
            <div x-show="!loading && items.length > 0" x-cloak class="space-y-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="item in items" :key="item.id">
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 overflow-hidden shadow-lg hover:border-slate-700 hover:shadow-xl transition-all duration-300 flex flex-col group">
                            {{-- Capa com Botão de Desfavoritar --}}
                            <div class="relative aspect-[16/10] bg-slate-950 overflow-hidden">
                                <a :href="'/produtos/' + item.slug" class="block w-full h-full">
                                    <template x-if="item.cover_image">
                                        <img :src="item.cover_image" :alt="item.title" class="h-full w-full object-cover group-hover:scale-105 transition duration-500" />
                                    </template>
                                    <template x-if="!item.cover_image">
                                        <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 font-display text-2xl font-bold text-teal-400">
                                            KL
                                        </div>
                                    </template>
                                </a>

                                {{-- Badge Categoria --}}
                                <span class="absolute bottom-2 left-2 rounded-md bg-slate-950/85 backdrop-blur-sm border border-slate-700 px-2 py-0.5 text-[10px] font-bold text-teal-300 shadow-xs" x-text="item.category || 'Sistema Web'">
                                </span>

                                {{-- Botão Remover Favorito (Coração ativo) --}}
                                <button 
                                    type="button" 
                                    @click="removeItem(item.id)" 
                                    class="absolute top-2 right-2 grid h-8 w-8 place-items-center rounded-full bg-slate-950/90 text-pink-500 hover:text-rose-400 hover:bg-rose-500/20 border border-slate-700 shadow-sm transition backdrop-blur-sm z-10 cursor-pointer"
                                    title="Remover dos favoritos"
                                    :aria-label="'Remover ' + item.title + ' dos favoritos'"
                                >
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Detalhes do Produto --}}
                            <div class="flex flex-1 flex-col p-4 sm:p-5">
                                <h3 class="font-display text-sm sm:text-base font-bold text-white line-clamp-2 hover:text-teal-400 transition leading-snug min-h-[2.75rem]">
                                    <a :href="'/produtos/' + item.slug" x-text="item.title"></a>
                                </h3>

                                <p class="mt-1.5 text-xs text-slate-400 line-clamp-2 leading-relaxed min-h-[2rem]" x-text="item.description">
                                </p>

                                {{-- Preço e Entrega --}}
                                <div class="mt-auto pt-4 border-t border-slate-800/80 flex items-baseline justify-between gap-2">
                                    <div>
                                        <span class="text-[10px] uppercase font-mono text-slate-500 block">Preço</span>
                                        <template x-if="item.is_free || item.price <= 0">
                                            <span class="inline-flex items-center rounded-md bg-emerald-500/15 border border-emerald-500/30 px-2 py-0.5 text-xs font-black text-emerald-400">
                                                GRÁTIS
                                            </span>
                                        </template>
                                        <template x-if="!item.is_free && item.price > 0">
                                            <span class="font-mono text-base sm:text-lg font-black text-teal-400" x-text="item.formatted_price"></span>
                                        </template>
                                    </div>
                                    <span class="text-[10px] text-emerald-400 font-mono flex items-center gap-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                        Instantâneo
                                    </span>
                                </div>

                                {{-- Ações: Adicionar ao Carrinho & Comprar/Checkout --}}
                                <div class="mt-4 flex items-center gap-2">
                                    {{-- Botão [+] Carrinho --}}
                                    <button 
                                        type="button" 
                                        @click="addToCart(item)"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-teal-500/30 bg-teal-500/10 text-teal-400 hover:bg-teal-500/20 transition cursor-pointer shrink-0"
                                        title="Adicionar ao Carrinho"
                                        aria-label="Adicionar ao carrinho"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </button>

                                    {{-- Botão Comprar / Checkout Direto --}}
                                    <a 
                                        :href="'/checkout?product=' + item.slug" 
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-teal-500 hover:bg-teal-400 py-2 px-3 text-xs font-bold text-slate-950 shadow-md shadow-teal-500/20 transition group"
                                    >
                                        <span x-text="item.is_free || item.price <= 0 ? 'Baixar Grátis' : 'Comprar Agora'"></span>
                                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Vitrine de Recomendados (Você também pode gostar) --}}
            @if(isset($recommendedProducts) && $recommendedProducts->isNotEmpty())
                <div class="mt-16 pt-12 border-t border-slate-800/80">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <span class="rounded bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-400">
                                Recomendações
                            </span>
                            <h2 class="font-display text-lg sm:text-xl font-bold text-white mt-1.5 tracking-tight">
                                Mais Produtos em Destaque
                            </h2>
                        </div>
                        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-teal-400 hover:text-teal-300 transition flex items-center gap-1">
                            <span>Ver todo o catálogo</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach($recommendedProducts as $rec)
                            <div class="rounded-2xl border border-slate-800/80 bg-slate-900/40 p-4 hover:border-slate-700 transition flex flex-col group">
                                <div class="relative aspect-video rounded-xl bg-slate-950 overflow-hidden mb-3">
                                    <a href="{{ route('storefront.show', $rec) }}" class="block w-full h-full">
                                        @if($rec->cover_path)
                                            <img src="{{ asset($rec->cover_path) }}" alt="{{ $rec->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300" />
                                        @else
                                            <div class="grid h-full w-full place-items-center bg-slate-900 text-teal-400 font-bold font-display text-sm">
                                                KL
                                            </div>
                                        @endif
                                    </a>

                                    <button 
                                        type="button" 
                                        onclick="window.toggleFavorite({
                                            id: {{ $rec->id }},
                                            title: {{ json_encode($rec->title) }},
                                            slug: {{ json_encode($rec->slug) }},
                                            price: {{ (float) $rec->price }},
                                            cover_image: {{ json_encode($rec->cover_path ? asset($rec->cover_path) : null) }},
                                            category: {{ json_encode($rec->categoryGroup?->name ?? $rec->category ?? 'Sistema Web') }}
                                        }, this)"
                                        class="absolute top-2 right-2 grid h-7 w-7 place-items-center rounded-full bg-slate-950/80 text-slate-400 hover:text-pink-500 border border-slate-700/60 transition cursor-pointer"
                                        title="Favoritar"
                                        aria-label="Favoritar {{ $rec->title }}"
                                    >
                                        <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </div>

                                <h4 class="font-display text-xs font-bold text-white hover:text-teal-400 transition line-clamp-2 min-h-[2rem]">
                                    <a href="{{ route('storefront.show', $rec) }}">{{ $rec->title }}</a>
                                </h4>

                                <div class="mt-auto pt-3 border-t border-slate-800 flex items-center justify-between">
                                    <div>
                                        @if((float) $rec->price <= 0)
                                            <span class="text-xs font-black text-emerald-400">GRÁTIS</span>
                                        @else
                                            <span class="font-mono text-sm font-bold text-teal-400">R$ {{ number_format((float) $rec->price, 2, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('checkout.index', ['product' => $rec->slug]) }}" class="rounded-lg bg-slate-800 hover:bg-teal-500 hover:text-slate-950 px-2.5 py-1 text-[11px] font-bold text-slate-300 transition">
                                        Ver &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-storefront-layout>
