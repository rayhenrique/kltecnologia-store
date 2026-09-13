<x-admin-layout title="Categorias de Produtos">
    <div class="space-y-6">
        {{-- Header da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl font-bold tracking-tight text-white">
                    Categorias de Produtos
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Organize os sistemas, scripts e infoprodutos da loja por categorias de mercado.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('catalog.index') }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition"
                >
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver no Catálogo</span>
                </a>

                <a 
                    href="{{ route('admin.categories.create') }}" 
                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white shadow-md shadow-teal-600/30 transition"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nova Categoria</span>
                </a>
            </div>
        </div>

        {{-- Barra de Busca --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4 backdrop-blur-xs">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}"
                        placeholder="Buscar categoria por nome, descrição ou slug..." 
                        class="w-full rounded-xl border-slate-700 bg-slate-950/80 pl-9 pr-4 py-2 text-xs sm:text-sm text-slate-200 placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500/30"
                    />
                    <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 px-4 py-2 text-xs font-semibold text-white transition"
                    >
                        Buscar
                    </button>

                    @if($search !== '')
                        <a 
                            href="{{ route('admin.categories.index') }}" 
                            class="rounded-xl bg-red-950/50 border border-red-800/40 hover:bg-red-900/40 px-3 py-2 text-xs text-red-300 transition"
                            title="Limpar busca"
                        >
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Categorias --}}
        <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40 shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="border-b border-slate-800 bg-slate-950/70 font-mono uppercase tracking-wider text-[11px] text-slate-400">
                        <tr>
                            <th class="px-5 py-3.5">Categoria</th>
                            <th class="px-5 py-3.5">Slug</th>
                            <th class="px-5 py-3.5 text-center">Produtos</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5">Criada em</th>
                            <th class="px-5 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-sans">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-800/30 transition group">
                                {{-- Nome + Descrição --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 shrink-0 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center font-mono text-xs font-bold text-teal-400 group-hover:border-teal-500/50 group-hover:bg-teal-950/30 transition">
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
                                                class="font-semibold text-white group-hover:text-teal-400 transition block text-sm"
                                            >
                                                {{ $category->name }}
                                            </a>
                                            @if($category->description)
                                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                                    {{ $category->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Slug --}}
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono text-[11px] text-slate-400">
                                    <code class="rounded bg-slate-950/60 px-2 py-0.5 border border-slate-800 text-teal-300">
                                        {{ $category->slug }}
                                    </code>
                                </td>

                                {{-- Produtos Vinculados --}}
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                    <a 
                                        href="{{ route('admin.products.index', ['q' => $category->name]) }}" 
                                        class="inline-flex items-center gap-1.5 rounded-md bg-slate-800 hover:bg-slate-700 border border-slate-700 px-2.5 py-1 font-mono text-xs text-slate-200 transition"
                                        title="Ver produtos desta categoria"
                                    >
                                        <svg class="h-3.5 w-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span>{{ $category->products_count }}</span>
                                    </a>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                    @if($category->is_active)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-950/60 border border-emerald-800/60 px-2.5 py-0.5 text-[10px] font-semibold text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                            Ativa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-800 border border-slate-700 px-2.5 py-0.5 text-[10px] font-semibold text-slate-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                            Inativa
                                        </span>
                                    @endif
                                </td>

                                {{-- Data --}}
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono text-[11px] text-slate-400">
                                    {{ $category->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Ações --}}
                                <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a 
                                            href="{{ route('admin.categories.edit', $category) }}" 
                                            class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-white transition"
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
                                                class="rounded-lg p-1.5 text-slate-400 hover:bg-red-950/60 hover:text-red-400 transition"
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
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">
                                    <svg class="h-10 w-10 mx-auto mb-3 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p class="font-medium text-slate-400">Nenhuma categoria encontrada</p>
                                    <p class="text-xs text-slate-500 mt-1">Crie uma nova categoria para começar a organizar seus produtos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="border-t border-slate-800 bg-slate-950/60 px-5 py-4">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
