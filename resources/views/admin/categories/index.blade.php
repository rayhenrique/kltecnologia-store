<x-admin-layout title="Categorias de Produtos">
    <div class="space-y-6">
        {{-- Header da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        E-commerce & Catálogo
                    </span>
                    <span class="font-mono text-xs text-slate-500 font-semibold">
                        {{ $categories->total() }} {{ $categories->total() === 1 ? 'categoria' : 'categorias' }}
                    </span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Categorias de Produtos
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Organize os sistemas, scripts e infoprodutos da loja por categorias de mercado.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('catalog.index') }}" 
                    target="_blank"
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver no Catálogo</span>
                </a>

                <a 
                    href="{{ route('admin.categories.create') }}" 
                    class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-teal-600/20 transition transform active:scale-95"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nova Categoria</span>
                </a>
            </div>
        </div>

        {{-- Barra de Busca --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}"
                        placeholder="Buscar categoria por nome, descrição ou slug..." 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-4 py-2.5 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
                    />
                    <svg class="pointer-events-none absolute left-3 top-3 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="rounded-xl bg-slate-900 hover:bg-slate-800 px-4 py-2.5 text-xs font-bold text-white transition shadow-xs"
                    >
                        Buscar
                    </button>

                    @if($search !== '')
                        <a 
                            href="{{ route('admin.categories.index') }}" 
                            class="rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-700 transition"
                            title="Limpar busca"
                        >
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Categorias --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50/80 font-mono uppercase tracking-wider text-[11px] font-bold text-slate-600">
                        <tr>
                            <th class="px-6 py-3.5">Categoria</th>
                            <th class="px-6 py-3.5">Slug</th>
                            <th class="px-6 py-3.5 text-center">Produtos</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5">Criada em</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans text-slate-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/80 transition group">
                                {{-- Nome + Descrição --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="h-10 w-10 shrink-0 rounded-xl bg-teal-50 border border-teal-200/80 flex items-center justify-center font-mono text-sm font-bold text-teal-700 group-hover:bg-teal-100 transition shadow-2xs">
                                            @if($category->icon === 'code')
                                                &lt;/&gt;
                                            @elseif($category->icon === 'cloud')
                                                ☁
                                            @elseif($category->icon === 'bot')
                                                🤖
                                            @elseif($category->icon === 'chart')
                                                📈
                                            @elseif($category->icon === 'template')
                                                📄
                                            @elseif($category->icon === 'mobile')
                                                📱
                                            @else
                                                🏷
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a 
                                                href="{{ route('admin.categories.edit', $category) }}" 
                                                class="font-bold text-slate-900 group-hover:text-teal-600 transition block text-sm"
                                            >
                                                {{ $category->name }}
                                            </a>
                                            @if($category->description)
                                                <p class="text-xs text-slate-500 line-clamp-1 mt-0.5 font-normal">
                                                    {{ $category->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Slug --}}
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs">
                                    <code class="rounded-md bg-slate-100 px-2 py-0.5 border border-slate-200/80 text-slate-700 font-semibold">
                                        {{ $category->slug }}
                                    </code>
                                </td>

                                {{-- Produtos Vinculados --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a 
                                        href="{{ route('admin.products.index', ['q' => $category->name]) }}" 
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 border border-teal-200 px-2.5 py-1 font-mono text-xs font-bold text-teal-800 transition shadow-2xs"
                                        title="Ver produtos desta categoria"
                                    >
                                        <svg class="h-3.5 w-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span>{{ $category->products_count }}</span>
                                    </a>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($category->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Ativa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            Inativa
                                        </span>
                                    @endif
                                </td>

                                {{-- Data --}}
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-600">
                                    {{ $category->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Ações --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a 
                                            href="{{ route('admin.categories.edit', $category) }}" 
                                            class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 hover:text-teal-600 transition"
                                            title="Editar categoria"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form 
                                            action="{{ route('admin.categories.destroy', $category) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta categoria? Os produtos vinculados terão a categoria desvinculada.')"
                                            class="inline-block"
                                        >
                                            @csrf 
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-red-50 hover:text-red-600 transition"
                                                title="Excluir categoria"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <svg class="h-10 w-10 mx-auto mb-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p class="font-bold text-slate-700">Nenhuma categoria encontrada</p>
                                    <p class="text-xs text-slate-500 mt-1">Crie uma nova categoria para começar a organizar seus produtos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-4">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
