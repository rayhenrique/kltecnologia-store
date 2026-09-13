<x-admin-layout>
    <x-slot:title>Produtos</x-slot:title>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Catálogo Digital
                    </span>
                    <span class="font-mono text-xs text-slate-400 font-semibold">
                        {{ $metrics['total'] }} {{ $metrics['total'] === 1 ? 'item cadastrado' : 'itens cadastrados' }}
                    </span>
                </div>
                <h1 class="mt-1.5 font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Produtos
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Gerencie scripts, templates, sistemas SaaS e arquivos com entrega imediata.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('catalog.index') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver no Catálogo</span>
                </a>
                <a 
                    href="{{ route('admin.products.create') }}" 
                    class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-teal-500/20 transition transform active:scale-95"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Novo Produto</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Cards de Métricas Rápidas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 block">Total de Produtos</span>
                    <span class="font-display text-xl sm:text-2xl font-bold text-slate-900 mt-0.5 block">{{ $metrics['total'] }}</span>
                </div>
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-emerald-600 block">Produtos Ativos</span>
                    <span class="font-display text-xl sm:text-2xl font-bold text-emerald-600 mt-0.5 block">{{ $metrics['active'] }}</span>
                </div>
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 block">Ocultos / Inativos</span>
                    <span class="font-display text-xl sm:text-2xl font-bold text-slate-600 mt-0.5 block">{{ $metrics['inactive'] }}</span>
                </div>
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-slate-500 border border-slate-200">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-amber-600 block">Destaques na Home</span>
                    <span class="font-display text-xl sm:text-2xl font-bold text-amber-600 mt-0.5 block">{{ $metrics['featured'] }}</span>
                </div>
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Barra de Busca e Filtros --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="search" 
                        value="{{ $search }}"
                        placeholder="Buscar por nome, slug, categoria, versão ou ID..." 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-4 py-2.5 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
                    />
                    <svg class="pointer-events-none absolute left-3 top-3 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-300 bg-slate-50/60 px-3 py-2.5 text-xs font-semibold text-slate-700 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs cursor-pointer"
                    >
                        <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Todos os status</option>
                        <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Apenas Ativos</option>
                        <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Apenas Inativos</option>
                    </select>

                    <select 
                        name="featured" 
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-300 bg-slate-50/60 px-3 py-2.5 text-xs font-semibold text-slate-700 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs cursor-pointer"
                    >
                        <option value="all" {{ $currentFeatured === 'all' ? 'selected' : '' }}>Todos os produtos</option>
                        <option value="featured" {{ $currentFeatured === 'featured' || $currentFeatured === '1' ? 'selected' : '' }}>★ Apenas Destaques</option>
                    </select>

                    <button type="submit" class="btn-secondary text-xs !min-h-10 !px-4">
                        Filtrar
                    </button>

                    @if($search !== '' || $currentStatus !== 'all' || ($currentFeatured !== 'all' && $currentFeatured !== ''))
                        <a href="{{ route('admin.products.index') }}" class="btn-secondary text-xs !min-h-10 text-slate-500 hover:text-rose-600">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Produtos --}}
        <section class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3.5">Produto & Identificador</th>
                            <th class="px-6 py-3.5">Preço</th>
                            <th class="px-6 py-3.5">Disponibilidade</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/60 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        @if($product->cover_path)
                                            <img 
                                                src="{{ asset($product->cover_path) }}" 
                                                alt="Capa de {{ $product->title }}" 
                                                class="h-12 w-16 rounded-lg object-cover border border-slate-200 shadow-2xs group-hover:scale-105 transition"
                                            >
                                        @else
                                            <div class="grid h-12 w-16 place-items-center rounded-lg bg-gradient-to-br from-slate-900 to-teal-950 font-display text-xs font-bold text-teal-300 border border-slate-800">
                                                KL
                                            </div>
                                        @endif
                                        <div>
                                            <a 
                                                href="{{ route('storefront.show', $product) }}" 
                                                class="font-bold text-slate-900 hover:text-teal-600 transition text-xs sm:text-sm block"
                                            >
                                                {{ $product->title }}
                                            </a>
                                            <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                                <span class="font-mono text-[10px] text-slate-400">
                                                    /{{ $product->slug }}
                                                </span>
                                                <span class="text-slate-300">•</span>
                                                @if($product->category)
                                                    <span class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] font-medium text-slate-600">
                                                        {{ $product->category }}
                                                    </span>
                                                    <span class="text-slate-300">•</span>
                                                @endif
                                                @if($product->has_file)
                                                    <span class="text-[10px] font-semibold text-teal-600 flex items-center gap-1" title="Arquivo digital no storage">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        Arquivo OK
                                                    </span>
                                                @else
                                                    <span class="text-[10px] font-semibold text-amber-600 flex items-center gap-1" title="Upload do arquivo pendente">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        Upload pendente
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-display font-bold text-slate-900 text-xs sm:text-sm">
                                        R$ {{ number_format((float) $product->price, 2, ',', '.') }}
                                    </span>
                                    <span class="block text-[10px] font-mono text-slate-400">BRL</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 items-start">
                                        @if($product->is_active)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                <span>Ativo</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-xs font-bold text-slate-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                <span>Inativo</span>
                                            </span>
                                        @endif

                                        @if($product->is_featured)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200/80 px-2 py-0.5 text-[10px] font-bold text-amber-700" title="Exibido na seção Produtos em Destaque da Home">
                                                <span>★ Destaque</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a 
                                            href="{{ route('admin.products.edit', $product) }}" 
                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-2xs hover:bg-slate-50 hover:border-slate-300 transition"
                                        >
                                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <span>Editar</span>
                                        </a>

                                        <button 
                                            type="button" 
                                            class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50 transition" 
                                            x-data 
                                            x-on:click.prevent="$dispatch('open-modal', 'archive-product-{{ $product->id }}')"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Arquivar</span>
                                        </button>
                                    </div>

                                    {{-- Modal de Confirmação de Arquivamento --}}
                                    <x-modal name="archive-product-{{ $product->id }}" focusable maxWidth="md">
                                        <div class="p-6 text-left">
                                            <div class="flex items-center gap-3">
                                                <span class="grid h-10 w-10 place-items-center rounded-xl bg-red-100 text-red-600">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </span>
                                                <div>
                                                    <h2 class="font-display text-lg font-bold text-slate-900">
                                                        Arquivar {{ $product->title }}?
                                                    </h2>
                                                    <p class="font-mono text-xs text-slate-400">ID #{{ $product->id }}</p>
                                                </div>
                                            </div>

                                            <p class="mt-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                                O produto será retirado da vitrine pública. Clientes que já adquiriram o item continuarão com permissão para baixar o arquivo normalmente.
                                            </p>

                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="mt-6 flex justify-end gap-3" novalidate>
                                                @csrf 
                                                @method('DELETE')
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    Cancelar
                                                </x-secondary-button>
                                                <x-danger-button>
                                                    Confirmar Arquivamento
                                                </x-danger-button>
                                            </form>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-14 text-center">
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3 border border-slate-200">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    @if($search !== '' || $currentStatus !== 'all' || ($currentFeatured !== 'all' && $currentFeatured !== ''))
                                        <p class="font-bold text-slate-800 text-sm">Nenhum produto encontrado para estes filtros.</p>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Tente buscar usando termos diferentes ou limpe os filtros para visualizar todo o catálogo.</p>
                                        <a 
                                            href="{{ route('admin.products.index') }}" 
                                            class="mt-4 inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-bold text-slate-700 transition shadow-2xs"
                                        >
                                            Limpar Busca e Filtros
                                        </a>
                                    @else
                                        <p class="font-bold text-slate-800 text-sm">Seu catálogo ainda está vazio.</p>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Cadastre seu primeiro produto digital para começar a vender na KL Tecnologia.</p>
                                        <a 
                                            href="{{ route('admin.products.create') }}" 
                                            class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white transition shadow-sm"
                                        >
                                            + Cadastrar Primeiro Produto
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-admin-layout>
