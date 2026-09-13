<x-admin-layout :title="'Editar: ' . $post->title">
    <div class="space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.posts.index') }}" class="hover:text-teal-400 transition">Artigos</a>
                    <span>/</span>
                    <span class="text-teal-400 truncate max-w-xs">{{ $post->title }}</span>
                </nav>
                <h1 class="font-display text-2xl font-bold tracking-tight text-white">
                    Editar Artigo
                </h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    Modifique as informações do artigo, imagem de capa ou status de publicação.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a 
                    href="{{ route('blog.show', $post) }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-3.5 py-2 text-xs font-semibold text-teal-300 hover:bg-slate-700 transition"
                >
                    <svg class="h-4 w-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver no Blog</span>
                </a>

                <a 
                    href="{{ route('admin.posts.index') }}" 
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition"
                >
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        {{-- Form Container --}}
        <div>
            @include('admin.posts._form')
        </div>
    </div>
</x-admin-layout>
