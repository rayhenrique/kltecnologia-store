<x-admin-layout title="Cliente: {{ $customer->name }}">
    <div class="space-y-6 max-w-7xl mx-auto">
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

        {{-- Breadcrumb & Header --}}
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('admin.customers.index') }}" class="hover:text-teal-600 transition">Clientes</a>
                <span>/</span>
                <span class="text-teal-600">{{ $customer->name }}</span>
            </nav>
            
            {{-- Perfil Banner --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div class="flex items-start sm:items-center gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white font-display text-2xl font-black shadow-md shadow-teal-500/20">
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                    {{ $customer->name }}
                                </h1>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $customer->isAdmin() ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-teal-50 text-teal-700 border border-teal-200' }}">
                                    {{ $customer->isAdmin() ? 'Administrador' : 'Cliente' }}
                                </span>
                                @if ($newsletterSubscriber?->is_active)
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Newsletter Ativa
                                    </span>
                                @endif
                            </div>
                            <div class="mt-1 flex flex-wrap items-center gap-4 text-xs sm:text-sm text-slate-500 font-mono">
                                <span>{{ $customer->email }}</span>
                                @if ($customer->phone)
                                    <span>•</span>
                                    <span>{{ $customer->phone }}</span>
                                @endif
                                <span>•</span>
                                <span>Cliente desde {{ $customer->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Ações Rápidas --}}
                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                        @if ($customer->phone)
                            <a 
                                href="https://wa.me/55{{ $customer->clean_phone }}" 
                                target="_blank" 
                                class="btn-primary !bg-emerald-600 hover:!bg-emerald-500 text-xs !min-h-10 !px-4 flex items-center gap-1.5 shadow-sm shadow-emerald-600/20"
                            >
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.397-12.416c-5.523 0-10 4.477-10 10 0 1.767.459 3.428 1.261 4.872l-1.341 4.9 5.019-1.316c1.402.766 3.007 1.204 4.717 1.204 5.523 0 10-4.477 10-10 0-5.522-4.477-10-10-10z"/>
                                </svg>
                                <span>WhatsApp</span>
                            </a>
                        @endif

                        <a 
                            href="mailto:{{ $customer->email }}" 
                            class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                        >
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>E-mail</span>
                        </a>

                        <a 
                            href="{{ route('admin.customers.edit', $customer) }}" 
                            class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                        >
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Editar</span>
                        </a>

                        <a 
                            href="{{ route('admin.customers.index') }}" 
                            class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                        >
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Voltar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- RESUMO FINANCEIRO (KPIS DO CLIENTE) --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total de Pedidos</span>
                <p class="font-display text-2xl font-extrabold text-slate-900 mt-2">{{ $stats['total_orders'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Registrados em todas as fontes</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pedidos Aprovados</span>
                <p class="font-display text-2xl font-extrabold text-teal-700 mt-2">{{ $stats['paid_orders'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Itens liberados para download</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Gasto (LTV)</span>
                <p class="font-display text-2xl font-extrabold text-emerald-600 mt-2">
                    R$ {{ number_format($stats['total_spent'], 2, ',', '.') }}
                </p>
                <p class="text-[11px] text-emerald-600 mt-0.5 font-medium">Valor total acumulado</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Ticket Médio</span>
                <p class="font-display text-2xl font-extrabold text-cyan-700 mt-2">
                    R$ {{ number_format($stats['average_ticket'], 2, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Por compra aprovada</p>
            </div>
        </div>

        {{-- CORPO DE 2 COLUNAS: DADOS CADASTRAIS + HISTÓRICO DE PEDIDOS --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Coluna Esquerda: Dados Pessoais & Contato --}}
            <div class="space-y-6 lg:col-span-1">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-4">
                    <h3 class="font-display text-base font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <svg class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Dados Cadastrais
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Nome Completo</span>
                            <span class="font-medium text-slate-900 text-sm block mt-0.5">{{ $customer->name }}</span>
                        </div>

                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">E-mail</span>
                            <span class="font-mono text-slate-900 text-xs block mt-0.5">{{ $customer->email }}</span>
                        </div>

                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">CPF</span>
                            <span class="font-mono text-slate-900 text-xs block mt-0.5">{{ $customer->cpf ?: 'Não informado' }}</span>
                        </div>

                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Telefone / WhatsApp</span>
                            <span class="font-mono text-slate-900 text-xs block mt-0.5">{{ $customer->phone ?: 'Não informado' }}</span>
                        </div>

                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Verificação de E-mail</span>
                            @if ($customer->email_verified_at)
                                <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold mt-0.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Verificado em {{ $customer->email_verified_at->format('d/m/Y H:i') }}
                                </span>
                            @else
                                <span class="text-amber-600 font-semibold mt-0.5 block">Não verificado</span>
                            @endif
                        </div>

                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Inscrição na Newsletter</span>
                            @if ($newsletterSubscriber?->is_active)
                                <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold mt-0.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Inscrito desde {{ $newsletterSubscriber->subscribed_at?->format('d/m/Y') ?? 'N/A' }}
                                </span>
                            @else
                                <span class="text-slate-500 mt-0.5 block">Inativo ou descadastrado</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Linha do Tempo / Datas --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-4">
                    <h3 class="font-display text-base font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <svg class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Histórico de Atividade
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Cadastro Realizado</span>
                            <span class="text-slate-800 font-medium block mt-0.5">{{ $customer->created_at->format('d/m/Y \à\s H:i') }}</span>
                        </div>

                        @if ($stats['first_order'])
                            <div>
                                <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Primeira Compra</span>
                                <span class="text-slate-800 font-medium block mt-0.5">{{ $stats['first_order']->created_at->format('d/m/Y \à\s H:i') }}</span>
                            </div>
                        @endif

                        @if ($stats['last_order'])
                            <div>
                                <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Última Compra</span>
                                <span class="text-slate-800 font-medium block mt-0.5">{{ $stats['last_order']->created_at->format('d/m/Y \à\s H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Coluna Direita: Histórico Completo de Pedidos --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-xs overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-display text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Histórico de Compras & Pedidos
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Todos os pedidos vinculados a este cliente.
                            </p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-mono font-bold text-slate-600">
                            {{ $customer->orders->count() }} {{ $customer->orders->count() === 1 ? 'pedido' : 'pedidos' }}
                        </span>
                    </div>

                    @if ($customer->orders->isEmpty())
                        <div class="p-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900">Nenhum pedido realizado ainda</h4>
                            <p class="mt-1 text-xs text-slate-500">
                                Este cliente ainda não realizou compras na loja.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs sm:text-sm">
                                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                    <tr>
                                        <th class="py-3.5 pl-6 pr-3">Pedido</th>
                                        <th class="py-3.5 px-3">Produto</th>
                                        <th class="py-3.5 px-3">Status</th>
                                        <th class="py-3.5 px-3">Valor</th>
                                        <th class="py-3.5 px-3">Data</th>
                                        <th class="py-3.5 pl-3 pr-6 text-right">Ação</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                    @foreach ($customer->orders as $order)
                                        <tr class="hover:bg-slate-50/70 transition">
                                            {{-- ID do Pedido --}}
                                            <td class="py-4 pl-6 pr-3 font-mono font-bold text-slate-900 whitespace-nowrap">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-teal-600 transition">
                                                    #{{ $order->id }}
                                                </a>
                                            </td>

                                            {{-- Produto --}}
                                            <td class="py-4 px-3">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    @if ($order->product?->cover_path)
                                                        <img 
                                                            src="{{ asset($order->product->cover_path) }}" 
                                                            alt="{{ $order->product->title }}" 
                                                            class="h-8 w-8 rounded-lg object-cover border border-slate-200 shrink-0" 
                                                        />
                                                    @endif
                                                    <span class="font-bold text-slate-900 truncate max-w-xs block">
                                                        {{ $order->product?->title ?? 'Produto excluído' }}
                                                    </span>
                                                </div>
                                            </td>

                                            {{-- Status --}}
                                            <td class="py-4 px-3 whitespace-nowrap">
                                                <x-status-badge :status="$order->status" />
                                            </td>

                                            {{-- Valor --}}
                                            <td class="py-4 px-3 font-mono font-bold text-xs {{ $order->isPaid() ? 'text-emerald-600' : 'text-slate-700' }} whitespace-nowrap">
                                                R$ {{ number_format($order->amount, 2, ',', '.') }}
                                            </td>

                                            {{-- Data --}}
                                            <td class="py-4 px-3 text-xs text-slate-500 whitespace-nowrap">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>

                                            {{-- Ação --}}
                                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                                <a 
                                                    href="{{ route('admin.orders.show', $order) }}" 
                                                    class="btn-secondary text-xs !min-h-8 !px-2.5 inline-flex items-center gap-1"
                                                >
                                                    <span>Ver Pedido</span>
                                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
