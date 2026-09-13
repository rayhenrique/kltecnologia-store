<x-app-layout>
    <x-slot:title>Painel administrativo</x-slot:title>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Visão Geral
                    </span>
                    <span class="inline-flex items-center gap-1 font-mono text-[11px] text-emerald-600 font-semibold">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Tempo Real
                    </span>
                </div>
                <h1 class="mt-1.5 font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Painel administrativo
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Acompanhe o faturamento, status de pedidos e o catálogo de produtos digitais da KL Tecnologia.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('catalog.index') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Ver Catálogo</span>
                </a>
                <a 
                    href="{{ route('admin.products.create') }}" 
                    class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-teal-500/20 transition transform active:scale-95"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Novo Produto</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-container py-8 space-y-8">
        {{-- 1. CARDS DE INDICADORES (MÉTRICAS SAAS) --}}
        <div class="grid gap-5 sm:grid-cols-3">
            {{-- Card 1: Receita Confirmada --}}
            <article class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Receita Confirmada
                    </span>
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 font-display text-3xl font-extrabold text-slate-900 tracking-tight">
                    R$ {{ number_format((float) $revenue, 2, ',', '.') }}
                </p>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <span>Mercado Pago • Liquidado</span>
                </div>
            </article>

            {{-- Card 2: Pedidos Pagos --}}
            <article class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Pedidos Pagos
                    </span>
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 font-display text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $paidOrders }}
                </p>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-blue-600">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Downloads Ativos</span>
                </div>
            </article>

            {{-- Card 3: Produtos Ativos --}}
            <article class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Produtos Ativos
                    </span>
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-teal-50 text-teal-600 border border-teal-100 shadow-2xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 font-display text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $activeProducts }}
                </p>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-teal-600">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Disponíveis para Compra</span>
                </div>
            </article>
        </div>

        {{-- 2. TABELA DE PEDIDOS RECENTES --}}
        <section class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 p-5 sm:p-6 gap-3">
                <div>
                    <h2 class="font-display text-base sm:text-lg font-bold text-slate-900">
                        Pedidos recentes
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Últimas movimentações e status de pagamento recebidos pela loja.
                    </p>
                </div>
                <a 
                    href="{{ route('admin.orders.index') }}" 
                    class="inline-flex items-center gap-1 text-xs font-bold text-teal-600 hover:text-teal-700 transition"
                >
                    <span>Ver todos os pedidos</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3.5">Pedido</th>
                            <th class="px-6 py-3.5">Cliente</th>
                            <th class="px-6 py-3.5">Produto</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($latestOrders as $order)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md">
                                        #{{ $order->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="grid h-7 w-7 place-items-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-600">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-xs">{{ $order->user->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono">{{ $order->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800 text-xs max-w-xs truncate">{{ $order->product->title }}</p>
                                    <span class="text-[10px] font-mono text-teal-600 uppercase font-semibold">Digital</span>
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$order->status" />
                                </td>
                                <td class="px-6 py-4 font-display font-bold text-slate-900 text-xs">
                                    R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-800 text-sm">Nenhum pedido registrado.</p>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Assim que os clientes realizarem compras pelo Mercado Pago, os pedidos aparecerão aqui automaticamente.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
