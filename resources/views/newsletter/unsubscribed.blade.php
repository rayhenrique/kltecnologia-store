<x-storefront-layout>
    <x-slot:title>Inscrição Cancelada - Newsletter KL Tecnologia</x-slot:title>

    <div class="min-h-[60vh] flex items-center justify-center py-16 px-4">
        <div class="max-w-md w-full text-center panel p-8 sm:p-10 bg-white border border-slate-200/80 rounded-3xl shadow-xl">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 mb-6">
                <svg class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" class="text-rose-500 stroke-2" />
                </svg>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-wider mb-4">
                Descadastrado com Sucesso
            </span>

            <h1 class="font-display text-2xl font-black text-slate-900 tracking-tight">
                Inscrição cancelada
            </h1>

            <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                O endereço <strong class="text-slate-800 font-semibold">{{ $email }}</strong> foi removido da nossa lista de novidades. Você não receberá mais nossos informativos periódicos.
            </p>

            <div class="mt-8 pt-6 border-t border-slate-100 space-y-3">
                <p class="text-xs text-slate-500">
                    Seus acessos a produtos já adquiridos e downloads continuam ativos normalmente.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('storefront.index') }}" class="btn-primary inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm transition">
                        Voltar à Loja
                    </a>
                    @auth
                        <a href="{{ route('customer.downloads') }}" class="btn-secondary inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-sm transition">
                            Meus Downloads
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-storefront-layout>
