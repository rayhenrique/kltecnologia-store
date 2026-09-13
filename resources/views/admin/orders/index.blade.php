<x-app-layout>
    <x-slot:title>Pedidos</x-slot:title>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Vendas & Transações
                    </span>
                    <span class="font-mono text-xs text-slate-400 font-semibold">
                        {{ $orders->total() }} {{ $orders->total() === 1 ? 'pedido registrado' : 'pedidos registrados' }}
                    </span>
                </div>
                <h1 class="mt-1.5 font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Pedidos
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Histórico completo de transações processadas via gateway Mercado Pago.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Ir ao Painel</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-container py-8">
        <section class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3.5">Pedido</th>
                            <th class="px-6 py-3.5">Cliente</th>
                            <th class="px-6 py-3.5">Produto Adquirido</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Gateway / Método</th>
                            <th class="px-6 py-3.5">Valor</th>
                            <th class="px-6 py-3.5">Data & Hora</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-md">
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
                                    <p class="font-medium text-slate-900 text-xs max-w-xs truncate">{{ $order->product->title }}</p>
                                    <span class="text-[10px] font-mono text-teal-600 uppercase font-semibold">Entrega Digital</span>
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$order->status" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-600 font-medium">
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span>{{ $order->payment_method ?? 'Mercado Pago' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-display font-bold text-slate-900 text-xs sm:text-sm">
                                    R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-slate-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center">
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-800 text-sm">Nenhum pedido registrado até o momento.</p>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Assim que as vendas forem processadas, as informações completas aparecerão listadas aqui.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
