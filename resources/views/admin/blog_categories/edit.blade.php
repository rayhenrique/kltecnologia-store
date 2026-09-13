<x-admin-layout :title="'Editar Categoria do Blog - '.$blogCategory->name">
    <div class="space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.posts.index') }}" class="hover:text-teal-600 transition">Conteúdo & Blog</a>
                    <span>/</span>
                    <a href="{{ route('admin.blog-categories.index') }}" class="hover:text-teal-600 transition">Categorias</a>
                    <span>/</span>
                    <span class="text-teal-700 font-bold">Editar</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Editar Categoria: {{ $blogCategory->name }}
                    </h1>
                    @if($blogCategory->is_active)
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-bold font-mono text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Ativa
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-xs font-bold font-mono text-slate-600">
                            Inativa
                        </span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Possui {{ $blogCategory->posts_count ?? $blogCategory->posts()->count() }} {{ ($blogCategory->posts_count ?? $blogCategory->posts()->count()) === 1 ? 'artigo vinculado' : 'artigos vinculados' }}.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a 
                    href="{{ route('blog.index', ['categoria' => $blogCategory->name]) }}" 
                    target="_blank"
                    class="btn-secondary text-xs !min-h-10 !px-4 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver no Blog</span>
                </a>
                <a 
                    href="{{ route('admin.blog-categories.index') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-4 flex items-center gap-2 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        {{-- Form Container --}}
        <div>
            @include('admin.blog_categories._form')
        </div>
    </div>
</x-admin-layout>
