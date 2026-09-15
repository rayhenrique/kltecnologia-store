<x-admin-layout title="Editar Inscrito: {{ $subscriber->email }}">
    <div class="space-y-6 max-w-4xl mx-auto">
        {{-- Breadcrumb & Header --}}
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('admin.newsletter.index') }}" class="hover:text-teal-600 transition">Newsletter</a>
                <span>/</span>
                <a href="{{ route('admin.newsletter.show', $subscriber) }}" class="hover:text-teal-600 transition font-mono">{{ $subscriber->email }}</a>
                <span>/</span>
                <span class="text-teal-600">Editar</span>
            </nav>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Editar Inscrito
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Atualize o e-mail cadastrado ou altere o status de recebimento de newsletters.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.newsletter.show', $subscriber) }}" 
                        class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                    >
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Ver Detalhes</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card de Formulário --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.newsletter.update', $subscriber) }}" method="POST">
                @method('PUT')
                @include('admin.newsletter._form')
            </form>
        </div>
    </div>
</x-admin-layout>
