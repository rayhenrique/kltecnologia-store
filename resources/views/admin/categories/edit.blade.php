<x-admin-layout :title="'Editar: ' . $category->name">
    <div class="space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.categories.index') }}" class="hover:text-teal-400 transition">Categorias</a>
                    <span>/</span>
                    <span class="text-teal-400">{{ $category->name }}</span>
                </nav>
                <h1 class="font-display text-2xl font-bold tracking-tight text-white">
                    Editar Categoria
                </h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    Modifique o nome, ícone ou status de visibilidade da categoria.
                </p>
            </div>

            <a 
                href="{{ route('admin.categories.index') }}" 
                class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition self-start sm:self-auto"
            >
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar para Lista</span>
            </a>
        </div>

        {{-- Form Container --}}
        <div>
            @include('admin.categories._form')
        </div>
    </div>
</x-admin-layout>
