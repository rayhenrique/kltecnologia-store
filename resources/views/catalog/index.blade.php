<x-storefront-layout>
    <x-slot:title>Catálogo de Produtos Digitais</x-slot:title>

    {{-- 1. PAGE HEADER (Green/Teal Banner like in the reference image) --}}
    <section class="bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-600 py-6 text-white shadow-inner">
        <div class="page-container flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-white">
                Produtos Digitais
            </h1>
            <nav class="flex items-center gap-2 text-xs font-semibold text-teal-100" aria-label="Breadcrumb">
                <a href="{{ route('storefront.index') }}" class="hover:text-white transition">Início</a>
                <span>/</span>
                <span class="text-white">Catálogo</span>
            </nav>
        </div>
    </section>

    {{-- 2. MAIN CONTENT (Sidebar Filter + Product Listing) --}}
    <div class="bg-slate-50 min-h-screen py-8">
        <div class="page-container">
            <div class="catalog-layout flex flex-col md:flex-row items-start gap-8 w-full">
                
                {{-- SIDEBAR: FILTRAR PRODUTOS --}}
                <aside class="catalog-sidebar w-full md:w-[270px] md:min-w-[270px] md:max-w-[270px] shrink-0 rounded-xl border border-slate-200 bg-white p-5 shadow-sm md:sticky md:top-24">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <h2 class="font-display text-sm font-bold uppercase tracking-wider text-slate-800">
                            Filtrar produtos
                        </h2>
                        @if(!empty(array_filter($filters)))
                            <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 underline">
                                Limpar
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4">
                        {{-- Buscar --}}
                        <div>
                            <label for="filter-q" class="block text-xs font-bold text-slate-700 mb-1">
                                Buscar
                            </label>
                            <input 
                                type="text" 
                                id="filter-q" 
                                name="q" 
                                value="{{ request('q') }}" 
                                placeholder="Nome do item..." 
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 shadow-sm"
                            />
                        </div>

                        {{-- Categoria --}}
                        <div>
                            <label for="filter-category" class="block text-xs font-bold text-slate-700 mb-1">
                                Categoria
                            </label>
                            <select 
                                id="filter-category" 
                                name="category" 
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 shadow-sm"
                            >
                                <option value="all" {{ request('category', 'all') === 'all' ? 'selected' : '' }}>Todas</option>
                                <option value="scripts" {{ request('category') === 'scripts' ? 'selected' : '' }}>Scripts</option>
                                <option value="templates" {{ request('category') === 'templates' ? 'selected' : '' }}>Templates</option>
                                <option value="sistemas" {{ request('category') === 'sistemas' ? 'selected' : '' }}>Sistemas & SaaS</option>
                                <option value="softwares" {{ request('category') === 'softwares' ? 'selected' : '' }}>Softwares & Extensões</option>
                                <option value="outros" {{ request('category') === 'outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                        </div>

                        {{-- Ordenar --}}
                        <div>
                            <label for="filter-sort" class="block text-xs font-bold text-slate-700 mb-1">
                                Ordenar
                            </label>
                            <select 
                                id="filter-sort" 
                                name="sort" 
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 shadow-sm"
                            >
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Mais recentes</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Menor preço</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Maior preço</option>
                                <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Ordem alfabética (A-Z)</option>
                            </select>
                        </div>

                        {{-- Faixa de Preço (Opcional) --}}
                        <div class="pt-1">
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Preço (R$)
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    name="min_price" 
                                    value="{{ request('min_price') }}" 
                                    placeholder="Mín" 
                                    class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 shadow-sm"
                                />
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    name="max_price" 
                                    value="{{ request('max_price') }}" 
                                    placeholder="Máx" 
                                    class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 shadow-sm"
                                />
                            </div>
                        </div>

                        {{-- Botão Filtrar (Estilo Dark como na referência) --}}
                        <div class="pt-2">
                            <button 
                                type="submit" 
                                class="w-full rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-4 text-xs uppercase tracking-wider transition shadow-sm"
                            >
                                Filtrar
                            </button>
                        </div>
                    </form>
                </aside>

                {{-- PRODUCT LISTING --}}
                <main class="catalog-content flex-1 w-full min-w-0">
                    {{-- Header info box (com barra teal à esquerda igual a imagem) --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-6 border-l-4 border-l-teal-500">
                        <h2 class="font-display text-lg font-bold text-slate-900">
                            {{ $products->total() }} {{ $products->total() === 1 ? 'item ativo' : 'itens ativos' }}
                        </h2>
                        <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                            Catálogo de produtos digitais da KL Tecnologia: scripts, sistemas, templates e automações com entrega imediata e download protegido.
                        </p>
                    </div>

                    {{-- Products Grid (3 colunas como na imagem) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($products as $product)
                            <article class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                                {{-- Imagem com badge amarelo "DESTAQUE" e coração no canto superior direito --}}
                                <div class="relative aspect-[16/10] bg-slate-950 overflow-hidden group">
                                    <a href="{{ route('storefront.show', $product) }}" class="block w-full h-full">
                                        @if($product->cover_path)
                                            <img src="{{ asset($product->cover_path) }}" alt="Capa de {{ $product->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 font-display text-2xl font-bold text-teal-300">
                                                KL
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Badge Amarelo "DESTAQUE" no canto inferior esquerdo da capa --}}
                                    <span class="absolute bottom-2 left-2 rounded bg-amber-400 px-2 py-0.5 text-[9px] font-black uppercase tracking-wider text-slate-950 shadow-sm">
                                        DESTAQUE
                                    </span>

                                    {{-- Ícone de Coração / Favorito no canto superior direito da capa --}}
                                    <button 
                                        type="button" 
                                        aria-label="Favoritar {{ $product->title }}"
                                        onclick="this.classList.toggle('text-red-500');"
                                        class="absolute top-2 right-2 grid h-7 w-7 place-items-center rounded-full bg-white/90 text-slate-400 hover:text-red-500 shadow-sm transition backdrop-blur-sm"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Corpo do Card --}}
                                <div class="flex flex-1 flex-col p-4">
                                    <h3 class="font-display text-sm font-bold text-slate-900 line-clamp-2 hover:text-teal-600 transition leading-snug min-h-[2.5rem]">
                                        <a href="{{ route('storefront.show', $product) }}">
                                            {{ $product->title }}
                                        </a>
                                    </h3>
                                    
                                    <p class="mt-1.5 text-xs text-slate-500 line-clamp-2 leading-relaxed min-h-[2rem]">
                                        {{ $product->description }}
                                    </p>

                                    {{-- Linha de Downloads / Imediato --}}
                                    <div class="mt-3 flex items-center justify-end text-[11px] font-semibold text-slate-500 gap-1">
                                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span>Download Imediato</span>
                                    </div>

                                    {{-- Rodapé do Card --}}
                                    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
                                        <div>
                                            <span class="text-xs font-bold text-teal-700 block">Digital</span>
                                            <span class="text-[10px] font-mono text-slate-400">KL TEC</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-tight block">Entrega Segura</span>
                                            <p class="font-display font-black text-sm text-slate-900">
                                                R$ {{ number_format((float) $product->price, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Botão de ação --}}
                                    <div class="mt-3">
                                        <a 
                                            href="{{ route('storefront.show', $product) }}" 
                                            class="inline-flex w-full items-center justify-center rounded-lg bg-teal-600 hover:bg-teal-500 py-2 text-xs font-bold text-white transition shadow-sm"
                                        >
                                            Ver Detalhes &rarr;
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                                <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-display text-base font-bold text-slate-900">Nenhum produto encontrado</h3>
                                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                                    Tente ajustar os filtros ao lado para encontrar o produto desejado.
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center rounded-lg bg-slate-800 text-white px-4 py-2 text-xs font-bold hover:bg-slate-700 transition">
                                        Limpar todos os filtros
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Paginação numerada --}}
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-storefront-layout>
