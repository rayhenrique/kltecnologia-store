<x-admin-layout title="Artigos do Blog">
    <div class="space-y-6">
        {{-- Header da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl font-bold tracking-tight text-white">
                    Artigos do Blog
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Crie, edite e monitore o alcance dos artigos e tutoriais da KL Tecnologia.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('blog.index') }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition"
                >
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver Blog Público</span>
                </a>

                <a 
                    href="{{ route('admin.posts.create') }}" 
                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white shadow-md shadow-teal-600/30 transition"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Novo Artigo</span>
                </a>
            </div>
        </div>

        {{-- Barra de Filtros & Busca --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4 backdrop-blur-xs">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}"
                        placeholder="Buscar por título, conteúdo ou categoria..." 
                        class="w-full rounded-xl border-slate-700 bg-slate-950/80 pl-9 pr-4 py-2 text-xs sm:text-sm text-slate-200 placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500/30"
                    />
                    <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-2">
                    <select 
                        name="categoria" 
                        onchange="this.form.submit()"
                        class="rounded-xl border-slate-700 bg-slate-950/80 px-3 py-2 text-xs text-slate-200 focus:border-teal-500 focus:ring-1 focus:ring-teal-500/30"
                    >
                        <option value="">Todas as Categorias</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>

                    <button 
                        type="submit" 
                        class="rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 px-4 py-2 text-xs font-semibold text-white transition"
                    >
                        Filtrar
                    </button>

                    @if($search !== '' || $selectedCategory !== '')
                        <a 
                            href="{{ route('admin.posts.index') }}" 
                            class="rounded-xl bg-red-950/50 border border-red-800/40 hover:bg-red-900/40 px-3 py-2 text-xs text-red-300 transition"
                            title="Limpar filtros"
                        >
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Artigos --}}
        <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40 shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="border-b border-slate-800 bg-slate-950/70 font-mono uppercase tracking-wider text-[11px] text-slate-400">
                        <tr>
                            <th class="px-5 py-3.5">Artigo</th>
                            <th class="px-5 py-3.5">Categoria</th>
                            <th class="px-5 py-3.5 text-center">Visualizações</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5">Publicado Em</th>
                            <th class="px-5 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-sans">
                        @forelse($posts as $post)
                            <tr class="hover:bg-slate-800/30 transition group">
                                {{-- Capa + Título --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-16 shrink-0 rounded-lg overflow-hidden bg-slate-800 border border-slate-700 flex items-center justify-center">
                                            @if($post->cover_path)
                                                <img src="{{ asset($post->cover_path) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                                            @else
                                                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <a 
                                                href="{{ route('admin.posts.edit', $post) }}" 
                                                class="font-semibold text-white group-hover:text-teal-400 transition line-clamp-1 block text-sm"
                                            >
                                                {{ $post->title }}
                                            </a>
                                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 80) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Categoria --}}
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md bg-teal-950/60 border border-teal-800/50 px-2 py-0.5 font-mono text-[10px] font-bold text-teal-300">
                                        {{ $post->category ?? 'Geral' }}
                                    </span>
                                </td>

                                {{-- Views --}}
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1 font-mono text-xs text-slate-400">
                                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ number_format($post->views_count) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                    @if($post->is_published)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-950/60 border border-emerald-800/60 px-2.5 py-0.5 text-[10px] font-semibold text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                            Publicado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-950/60 border border-amber-800/60 px-2.5 py-0.5 text-[10px] font-semibold text-amber-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                            Rascunho
                                        </span>
                                    @endif
                                </td>

                                {{-- Data --}}
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono text-[11px] text-slate-400">
                                    {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : $post->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Ações --}}
                                <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a 
                                            href="{{ route('blog.show', $post) }}" 
                                            target="_blank"
                                            class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-teal-400 transition"
                                            title="Ver post público"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a 
                                            href="{{ route('admin.posts.edit', $post) }}" 
                                            class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-white transition"
                                            title="Editar artigo"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form 
                                            action="{{ route('admin.posts.destroy', $post) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Tem certeza que deseja excluir este artigo permanentemente?')"
                                            class="inline-block"
                                        >
                                            @csrf 
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="rounded-lg p-1.5 text-slate-400 hover:bg-red-950/60 hover:text-red-400 transition"
                                                title="Excluir artigo"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                    <p class="font-medium text-slate-400">Nenhum artigo encontrado</p>
                                    <p class="text-xs text-slate-500 mt-1">Tente ajustar seus termos de busca ou crie um novo artigo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($posts->hasPages())
                <div class="border-t border-slate-800 bg-slate-950/60 px-5 py-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
