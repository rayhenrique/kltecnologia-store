<x-admin-layout title="Pedidos">
    <div class="space-y-6">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 flex items-center justify-between text-sm text-emerald-800 shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Header & Ações --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Vendas & Transações
                    </span>
                    <span class="font-mono text-xs text-slate-400 font-semibold">
                        {{ $orders->total() }} {{ $orders->total() === 1 ? 'pedido listado' : 'pedidos listados' }}
                    </span>
                </div>
                <h1 class="mt-1.5 font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Gerenciador de Pedidos
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Acompanhe histórico de vendas, registre pedidos manuais, altere status e gerencie autorizações de download.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a 
                    href="{{ route('admin.orders.create') }}" 
                    class="btn-primary !bg-teal-600 hover:!bg-teal-500 text-xs !min-h-10 !px-4 flex items-center gap-1.5 shadow-sm shadow-teal-600/20"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Novo Pedido</span>
                </a>

                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Painel</span>
                </a>
            </div>
        </div>

        {{-- CARDS DE MÉTRICAS --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total de Pedidos --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total de Pedidos</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                </div>
                <p class="font-display text-2xl font-extrabold text-slate-900 mt-2">{{ $metrics['total'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Registrados em todas as fontes</p>
            </div>

            {{-- Faturamento Pago --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Faturamento Pago</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="font-display text-2xl font-extrabold text-slate-900 mt-2">
                    R$ {{ number_format($metrics['revenue'], 2, ',', '.') }}
                </p>
                <p class="text-[11px] text-emerald-600 mt-0.5 font-medium">Transações aprovadas</p>
            </div>

            {{-- Pedidos Pagos --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pedidos Aprovados</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-teal-50 text-teal-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="font-display text-2xl font-extrabold text-teal-700 mt-2">{{ $metrics['paid'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Downloads disponíveis</p>
            </div>

            {{-- Pendentes --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pendentes</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="font-display text-2xl font-extrabold text-amber-600 mt-2">{{ $metrics['pending'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Aguardando pagamento</p>
            </div>
        </div>

        {{-- BARRA DE FILTROS E BUSCA --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                {{-- Input Busca --}}
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Buscar por ID, cliente, e-mail, produto ou código gateway..." 
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
                    />
                </div>

                {{-- Filtro por Status --}}
                <div class="w-full sm:w-52">
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs sm:text-sm font-semibold text-slate-800 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs bg-white"
                    >
                        <option value="all">Todos os Status</option>
                        <option value="paid" {{ $currentStatus === 'paid' ? 'selected' : '' }}>✅ Pagos ({{ $metrics['paid'] }})</option>
                        <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>⏳ Pendentes ({{ $metrics['pending'] }})</option>
                        <option value="canceled" {{ $currentStatus === 'canceled' ? 'selected' : '' }}>❌ Cancelados</option>
                        <option value="failed" {{ $currentStatus === 'failed' ? 'selected' : '' }}>⚠️ Falhos</option>
                    </select>
                </div>

                {{-- Botões do Formulário --}}
                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="btn-primary !bg-teal-600 hover:!bg-teal-500 !min-h-10 !px-4 text-xs font-bold"
                    >
                        Filtrar
                    </button>
                    @if($search !== '' || ($currentStatus !== '' && $currentStatus !== 'all'))
                        <a 
                            href="{{ route('admin.orders.index') }}" 
                            class="btn-secondary !min-h-10 !px-3 text-xs text-slate-500 hover:text-slate-900"
                            title="Limpar Filtros"
                        >
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABELA DE PEDIDOS --}}
        <section class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Pedido</th>
                            <th class="px-5 py-3.5">Cliente</th>
                            <th class="px-5 py-3.5">Produto Adquirido</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Método / Gateway</th>
                            <th class="px-5 py-3.5">Valor</th>
                            <th class="px-5 py-3.5">Data</th>
                            <th class="px-5 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/60 transition group">
                                <td class="px-5 py-4">
                                    <a 
                                        href="{{ route('admin.orders.show', $order) }}" 
                                        class="font-mono text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200/60 px-2.5 py-1 rounded-md hover:bg-teal-100 transition inline-block"
                                    >
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="grid h-7 w-7 place-items-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-600 shrink-0">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0 max-w-[160px] truncate">
                                            <p class="font-semibold text-slate-900 text-xs truncate">{{ $order->user->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono truncate">{{ $order->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900 text-xs max-w-xs truncate" title="{{ $order->product->title }}">
                                        {{ $order->product->title }}
                                    </p>
                                    <span class="text-[10px] font-mono text-teal-600 uppercase font-semibold">Entrega Digital</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$order->status" />
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-700 font-medium">
                                        <span class="h-2 w-2 rounded-full {{ $order->status->value === 'paid' ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                        <span>{{ $order->payment_method ?? 'Mercado Pago' }}</span>
                                    </div>
                                    @if($order->gateway_reference)
                                        <span class="text-[10px] font-mono text-slate-400 block truncate max-w-[140px]">
                                            {{ $order->gateway_reference }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-display font-bold text-slate-900 text-xs sm:text-sm">
                                    R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-slate-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Ver Detalhes --}}
                                        <a 
                                            href="{{ route('admin.orders.show', $order) }}" 
                                            class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-slate-100 rounded-lg transition"
                                            title="Ver Detalhes do Pedido"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- Editar --}}
                                        <a 
                                            href="{{ route('admin.orders.edit', $order) }}" 
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition"
                                            title="Editar Pedido"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Excluir --}}
                                        <form 
                                            method="POST" 
                                            action="{{ route('admin.orders.destroy', $order) }}" 
                                            onsubmit="return confirm('Deseja realmente excluir o Pedido #{{ $order->id }}?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer"
                                                title="Excluir Pedido"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-14 text-center">
                                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-50 text-slate-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-800 text-sm">Nenhum pedido encontrado.</p>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                        @if($search !== '' || ($currentStatus !== '' && $currentStatus !== 'all'))
                                            Nenhum resultado corresponde aos filtros aplicados. Tente limpar os filtros.
                                        @else
                                            Nenhum pedido registrado até o momento. Você pode criar o primeiro pedido manual clicando abaixo.
                                        @endif
                                    </p>
                                    <div class="mt-4">
                                        @if($search !== '' || ($currentStatus !== '' && $currentStatus !== 'all'))
                                            <a href="{{ route('admin.orders.index') }}" class="btn-secondary text-xs !min-h-9 !px-3">
                                                Limpar Filtros
                                            </a>
                                        @else
                                            <a href="{{ route('admin.orders.create') }}" class="btn-primary !bg-teal-600 hover:!bg-teal-500 text-xs !min-h-9 !px-3.5">
                                                + Novo Pedido
                                            </a>
                                        @endif
                                    </div>
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
</x-admin-layout>
