<x-admin-layout :title="'Editar Pedido #'.$order->id">
    <div class="space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-teal-600 transition">Pedidos</a>
                    <span>/</span>
                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-teal-600 transition">#{{ $order->id }}</a>
                    <span>/</span>
                    <span class="text-teal-700 font-bold">Editar</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Editar Pedido #{{ $order->id }}
                    </h1>
                    <x-status-badge :status="$order->status" />
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Atualize os dados da transação, altere o status de pagamento ou vincule outro produto.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a 
                    href="{{ route('admin.orders.show', $order) }}" 
                    class="btn-secondary text-xs !min-h-10 !px-4 flex items-center gap-2 self-start sm:self-auto shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Ver Detalhes</span>
                </a>
                <a 
                    href="{{ route('admin.orders.index') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-4 flex items-center gap-2 self-start sm:self-auto shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Lista</span>
                </a>
            </div>
        </div>

        {{-- Form Container --}}
        <div>
            @include('admin.orders._form')
        </div>
    </div>
</x-admin-layout>
