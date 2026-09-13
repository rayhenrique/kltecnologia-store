<x-admin-layout title="Categorias do Blog">
    <div class="space-y-6">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 flex items-center justify-between text-sm text-emerald-800 shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Header da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Conteúdo & Blog
                    </span>
                    <span class="font-mono text-xs text-slate-500 font-semibold">
                        {{ $categories->total() }} {{ $categories->total() === 1 ? 'categoria cadastrada' : 'categorias cadastradas' }}
                    </span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Categorias do Blog
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Gerencie os tópicos editoriais para classificar artigos, tutoriais e comunicados do blog.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a 
                    href="{{ route('blog.index') }}" 
                    target="_blank"
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver Blog Público</span>
                </a>

                <a 
                    href="{{ route('admin.blog-categories.create') }}" 
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
            <form action="{{ route('admin.blog-categories.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}"
                        placeholder="Buscar categoria do blog por nome, descrição ou slug..." 
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
                            href="{{ route('admin.blog-categories.index') }}" 
                            class="rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-700 transition"
                            title="Limpar busca"
                        >
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Categorias do Blog --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50/80 font-mono uppercase tracking-wider text-[11px] font-bold text-slate-600">
                        <tr>
                            <th class="px-6 py-3.5">Categoria</th>
                            <th class="px-6 py-3.5">Slug</th>
                            <th class="px-6 py-3.5 text-center">Artigos</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5">Criada em</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans text-slate-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/80 transition group">
                                {{-- Nome + Descrição + Ícone --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-teal-50 border border-teal-200/60 font-mono text-xs font-bold text-teal-700 shadow-2xs">
                                            @if($category->icon === 'sparkles')
                                                ✨
                                            @elseif($category->icon === 'code')
                                                💻
                                            @elseif($category->icon === 'book-open')
                                                📖
                                            @elseif($category->icon === 'chart')
                                                📈
                                            @elseif($category->icon === 'shield-check')
                                                🛡️
                                            @elseif($category->icon === 'newspaper')
                                                📰
                                            @else
                                                🏷️
                                            @endif
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 group-hover:text-teal-700 transition">
                                                {{ $category->name }}
                                            </p>
                                            @if($category->description)
                                                <p class="mt-0.5 text-[11px] text-slate-500 line-clamp-1 max-w-sm">
                                                    {{ $category->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Slug --}}
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-slate-500">
                                    /blog?categoria={{ $category->name }}
                                </td>

                                {{-- Contagem de Artigos --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 font-mono text-xs font-semibold text-slate-700">
                                        {{ $category->posts_count }} {{ $category->posts_count === 1 ? 'artigo' : 'artigos' }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($category->is_active)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 font-mono text-[10px] font-bold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Ativa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 border border-slate-200 px-2 py-0.5 font-mono text-[10px] font-bold text-slate-500">
                                            Inativa
                                        </span>
                                    @endif
                                </td>

                                {{-- Data --}}
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-[11px] text-slate-400">
                                    {{ $category->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Ações --}}
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a 
                                            href="{{ route('admin.blog-categories.edit', $category) }}" 
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition"
                                            title="Editar Categoria"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form 
                                            method="POST" 
                                            action="{{ route('admin.blog-categories.destroy', $category) }}" 
                                            onsubmit="return confirm('Deseja realmente excluir a categoria {{ $category->name }}? Os artigos vinculados permanecerão intactos.')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer"
                                                title="Excluir Categoria"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-800 text-sm">Nenhuma categoria do blog encontrada.</p>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                        @if($search !== '')
                                            Nenhum resultado para a busca "{{ $search }}". Tente buscar por outros termos.
                                        @else
                                            Comece cadastrando a primeira categoria para os artigos do seu blog.
                                        @endif
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.blog-categories.create') }}" class="btn-primary !bg-teal-600 hover:!bg-teal-500 text-xs !min-h-9 !px-3.5">
                                            + Nova Categoria do Blog
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</x-admin-layout>
