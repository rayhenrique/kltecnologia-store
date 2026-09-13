<x-app-layout>
    <x-slot:title>Painel administrativo</x-slot:title>
    <x-slot name="header"><div><p class="eyebrow">Visão geral</p><h1 class="mt-1 font-display text-2xl font-bold text-slate-900">Painel administrativo</h1></div></x-slot>
    <div class="page-container py-8">
        <div class="grid gap-4 sm:grid-cols-3">
            <article class="panel p-6"><p class="text-sm font-semibold text-slate-500">Produtos ativos</p><p class="mt-2 font-display text-3xl font-bold">{{ $activeProducts }}</p></article>
            <article class="panel p-6"><p class="text-sm font-semibold text-slate-500">Pedidos pagos</p><p class="mt-2 font-display text-3xl font-bold">{{ $paidOrders }}</p></article>
            <article class="panel p-6"><p class="text-sm font-semibold text-slate-500">Receita confirmada</p><p class="mt-2 font-display text-3xl font-bold">R$ {{ number_format((float) $revenue, 2, ',', '.') }}</p></article>
        </div>
        <section class="panel mt-6 overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-200 p-5"><div><h2 class="font-display text-lg font-bold">Pedidos recentes</h2><p class="text-sm text-slate-500">Últimas movimentações da loja.</p></div><a class="text-sm font-bold text-blue-700" href="{{ route('admin.orders.index') }}">Ver todos</a></div>
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Pedido</th><th class="px-5 py-3">Cliente</th><th class="px-5 py-3">Produto</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Valor</th></tr></thead><tbody class="divide-y divide-slate-100">
                @forelse($latestOrders as $order)<tr><td class="px-5 py-4 font-mono">#{{ $order->id }}</td><td class="px-5 py-4">{{ $order->user->name }}</td><td class="px-5 py-4">{{ $order->product->title }}</td><td class="px-5 py-4"><x-status-badge :status="$order->status" /></td><td class="px-5 py-4 font-semibold">R$ {{ number_format((float) $order->amount, 2, ',', '.') }}</td></tr>
                @empty<tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Nenhum pedido registrado.</td></tr>@endforelse
            </tbody></table></div>
        </section>
    </div>
</x-app-layout>
