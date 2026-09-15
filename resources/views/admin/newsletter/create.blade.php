<x-admin-layout title="Novo Inscrito na Newsletter">
    <div class="space-y-6 max-w-4xl mx-auto">
        {{-- Breadcrumb & Header --}}
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('admin.newsletter.index') }}" class="hover:text-teal-600 transition">Newsletter</a>
                <span>/</span>
                <span class="text-teal-600">Novo Inscrito</span>
            </nav>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Cadastrar Novo Inscrito
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Adicione manualmente um lead ou assinante para a lista de comunicados da KL Tecnologia.
                    </p>
                </div>
                <a 
                    href="{{ route('admin.newsletter.index') }}" 
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
            <form action="{{ route('admin.newsletter.store') }}" method="POST">
                @include('admin.newsletter._form')
            </form>
        </div>
    </div>
</x-admin-layout>
