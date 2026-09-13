<x-app-layout>
    <x-slot:title>Novo produto</x-slot:title>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.products.index') }}" class="hover:text-teal-600 transition">Produtos</a>
                    <span>/</span>
                    <span class="text-slate-800">Novo</span>
                </nav>
                <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Cadastrar novo produto
                </h1>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary text-xs !min-h-10">
                &larr; Voltar para lista
            </a>
        </div>
    </x-slot>

    <div class="page-container py-8">
        <div class="mx-auto max-w-3xl rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            @include('admin.products._form')
        </div>
    </div>
</x-app-layout>
