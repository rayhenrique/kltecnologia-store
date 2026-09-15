<x-admin-layout title="Novo Cliente">
    <div class="space-y-6 max-w-4xl mx-auto">
        {{-- Breadcrumb & Header --}}
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('admin.customers.index') }}" class="hover:text-teal-600 transition">Clientes</a>
                <span>/</span>
                <span class="text-teal-600">Novo Cliente</span>
            </nav>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Cadastrar Novo Cliente
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Preencha as informações cadastrais para criar um usuário manualmente no sistema.
                    </p>
                </div>
                <a 
                    href="{{ route('admin.customers.index') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar à Lista</span>
                </a>
            </div>
        </div>

        {{-- Card de Formulário --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @include('admin.customers._form')
            </form>
        </div>
    </div>
</x-admin-layout>
