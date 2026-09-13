<x-admin-layout title="Artigos do Blog">
    <div class="space-y-6">
        {{-- Header da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2.5 py-0.5 font-mono text-[11px] font-bold uppercase tracking-wider text-teal-800">
                        Conteúdo & Blog
                    </span>
                    <span class="font-mono text-xs text-slate-600 font-bold">
                        {{ $posts->total() }} {{ $posts->total() === 1 ? 'artigo cadastrado' : 'artigos cadastrados' }}
                    </span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Artigos do Blog
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                    Crie, edite e monitore o alcance dos artigos e tutoriais da KL Tecnologia.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('blog.index') }}" 
                    target="_blank"
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs text-slate-700 font-semibold"
                >
                    <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver Blog Público</span>
                </a>

                <a 
                    href="{{ route('admin.posts.create') }}" 
                    class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-teal-600/20 transition transform active:scale-95"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Novo Artigo</span>
                </a>
            </div>
        </div>

        {{-- Barra de Filtros & Busca --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}"
                        placeholder="Buscar por título, conteúdo ou categoria..." 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-4 py-2.5 text-xs sm:text-sm text-slate-900 placeholder-slate-500 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs font-medium"
                    />
                    <svg class="pointer-events-none absolute left-3 top-3 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-2">
                    <select 
                        name="categoria" 
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
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
                        class="rounded-xl bg-slate-900 hover:bg-slate-800 px-4 py-2.5 text-xs font-bold text-white transition shadow-xs"
                    >
                        Filtrar
                    </button>

                    @if($search !== '' || $selectedCategory !== '')
                        <a 
                            href="{{ route('admin.posts.index') }}" 
                            class="rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-700 transition"
                            title="Limpar filtros"
                        >
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Artigos --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50/90 uppercase tracking-wider text-[11px] font-bold text-slate-700">
                        <tr>
                            <th class="px-6 py-3.5">Artigo</th>
                            <th class="px-6 py-3.5">Categoria</th>
                            <th class="px-6 py-3.5 text-center">Visualizações</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5">Publicado Em</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans text-slate-700">
                        @forelse($posts as $post)
                            <tr class="hover:bg-slate-50/80 transition group">
                                {{-- Capa + Título --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="h-12 w-16 shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center shadow-2xs">
                                            @if($post->cover_path)
                                                <img src="{{ asset($post->cover_path) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                                            @else
                                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <a 
                                                href="{{ route('admin.posts.edit', $post) }}" 
                                                class="font-bold text-slate-900 group-hover:text-teal-600 transition line-clamp-1 block text-sm"
                                            >
                                                {{ $post->title }}
                                            </a>
                                            <p class="text-xs text-slate-600 line-clamp-1 mt-0.5 font-normal">
                                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 80) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Categoria --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md bg-teal-50 border border-teal-200/80 px-2.5 py-1 text-xs font-bold text-teal-800">
                                        {{ $post->category ?? 'Geral' }}
                                    </span>
                                </td>

                                {{-- Views --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1 font-mono text-xs text-slate-700 font-bold">
                                        <svg class="h-3.5 w-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ number_format($post->views_count) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($post->is_published)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-bold text-emerald-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Publicado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Rascunho
                                        </span>
                                    @endif
                                </td>

                                {{-- Data --}}
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-700 font-semibold">
                                    {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : $post->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Ações --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a 
                                            href="{{ route('blog.show', $post) }}" 
                                            target="_blank"
                                            class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 hover:text-teal-600 transition"
                                            title="Ver post público"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a 
                                            href="{{ route('admin.posts.edit', $post) }}" 
                                            class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 hover:text-teal-600 transition"
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
                                                class="rounded-lg p-2 text-slate-500 hover:bg-red-50 hover:text-red-600 transition"
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
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <svg class="h-10 w-10 mx-auto mb-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                    <p class="font-bold text-slate-700">Nenhum artigo encontrado</p>
                                    <p class="text-xs text-slate-500 mt-1">Tente ajustar seus termos de busca ou crie um novo artigo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($posts->hasPages())
                <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
