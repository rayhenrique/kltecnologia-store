<x-admin-layout title="Nova Categoria do Blog">
    <div class="space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.posts.index') }}" class="hover:text-teal-600 transition">Conteúdo & Blog</a>
                    <span>/</span>
                    <a href="{{ route('admin.blog-categories.index') }}" class="hover:text-teal-600 transition">Categorias</a>
                    <span>/</span>
                    <span class="text-teal-700 font-bold">Nova Categoria</span>
                </nav>
                <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Cadastrar Categoria do Blog
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Crie um novo tópico de conteúdo para classificar artigos, tutoriais e comunicados.
                </p>
            </div>

            <a 
                href="{{ route('admin.blog-categories.index') }}" 
                class="btn-secondary text-xs !min-h-10 !px-4 flex items-center gap-2 self-start sm:self-auto shadow-2xs"
            >
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar para Lista</span>
            </a>
        </div>

        {{-- Form Container --}}
        <div>
            @include('admin.blog_categories._form')
        </div>
    </div>
</x-admin-layout>
