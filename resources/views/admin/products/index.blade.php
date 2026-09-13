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
                        {{ $products->total() }} {{ $products->total() === 1 ? 'item cadastrado' : 'itens cadastrados' }}
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

    <div class="page-container py-8">
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
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="font-mono text-[10px] text-slate-400">
                                                    /{{ $product->slug }}
                                                </span>
                                                <span class="text-slate-300">•</span>
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
                                    @if($product->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Ativo</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-1 text-xs font-bold text-slate-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            <span>Inativo</span>
                                        </span>
                                    @endif
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
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-800 text-sm">Seu catálogo ainda está vazio.</p>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Cadastre seu primeiro produto digital para começar a vender na KL Tecnologia.</p>
                                    <a 
                                        href="{{ route('admin.products.create') }}" 
                                        class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white transition shadow-sm"
                                    >
                                        + Cadastrar Primeiro Produto
                                    </a>
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
