<x-admin-layout title="Clientes">
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

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 flex items-center justify-between text-sm text-rose-800 shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Header & Ações --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Gestão de Usuários
                    </span>
                    <span class="font-mono text-xs text-slate-400 font-semibold">
                        {{ $customers->total() }} {{ $customers->total() === 1 ? 'cliente listado' : 'clientes listados' }}
                    </span>
                </div>
                <h1 class="mt-1.5 font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Gerenciador de Clientes
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Visualize compradores, histórico financeiro (LTV), dados de contato e gerencie inscrições na loja.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a 
                    href="{{ route('admin.customers.create') }}" 
                    class="btn-primary !bg-teal-600 hover:!bg-teal-500 text-xs !min-h-10 !px-4 flex items-center gap-1.5 shadow-sm shadow-teal-600/20"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Novo Cliente</span>
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
            {{-- Total de Clientes --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total de Clientes</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                </div>
                <p class="font-display text-2xl font-extrabold text-slate-900 mt-2">{{ $metrics['total'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Cadastrados na plataforma</p>
            </div>

            {{-- Clientes com Compras --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Compradores Ativos</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-teal-50 text-teal-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                </div>
                <p class="font-display text-2xl font-extrabold text-teal-700 mt-2">{{ $metrics['with_orders'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Com pelo menos 1 pedido</p>
            </div>

            {{-- Novos este Mês --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Novos este Mês</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-cyan-50 text-cyan-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>
                <p class="font-display text-2xl font-extrabold text-slate-900 mt-2">{{ $metrics['new_this_month'] }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Inscritos no mês atual</p>
            </div>

            {{-- LTV / Faturamento Acumulado --}}
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
                    R$ {{ number_format($metrics['total_spent'], 2, ',', '.') }}
                </p>
                <p class="text-[11px] text-emerald-600 mt-0.5 font-medium">Receita de pedidos aprovados</p>
            </div>
        </div>

        {{-- BARRA DE FILTROS & BUSCA --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-12 items-center">
                {{-- Input Busca --}}
                <div class="relative sm:col-span-5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $search }}" 
                        placeholder="Buscar por nome, e-mail, CPF ou telefone..."
                        class="w-full rounded-xl border border-slate-300 bg-white pl-9 pr-3 py-2 text-xs sm:text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition"
                    />
                </div>

                {{-- Filtro de Status / Tipo --}}
                <div class="sm:col-span-3">
                    <select 
                        name="status" 
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs sm:text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition"
                    >
                        <option value="all" @selected($currentStatus === 'all')>Todos os Usuários</option>
                        <option value="customer" @selected($currentStatus === 'customer')>Apenas Clientes</option>
                        <option value="admin" @selected($currentStatus === 'admin')>Apenas Administradores</option>
                        <option value="with_orders" @selected($currentStatus === 'with_orders')>Com Qualquer Pedido</option>
                        <option value="with_paid_orders" @selected($currentStatus === 'with_paid_orders')>Com Pedidos Pagos</option>
                        <option value="no_orders" @selected($currentStatus === 'no_orders')>Sem Pedidos</option>
                    </select>
                </div>

                {{-- Ordenação --}}
                <div class="sm:col-span-2">
                    <select 
                        name="sort" 
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs sm:text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition"
                    >
                        <option value="recent" @selected($currentSort === 'recent')>Mais Recentes</option>
                        <option value="oldest" @selected($currentSort === 'oldest')>Mais Antigos</option>
                        <option value="spent_desc" @selected($currentSort === 'spent_desc')>Maior Gasto (LTV)</option>
                        <option value="orders_desc" @selected($currentSort === 'orders_desc')>Mais Pedidos</option>
                        <option value="name_asc" @selected($currentSort === 'name_asc')>Nome (A-Z)</option>
                    </select>
                </div>

                {{-- Botões Filtrar & Limpar --}}
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="btn-primary !bg-slate-900 hover:!bg-slate-800 text-xs !min-h-9 w-full flex items-center justify-center gap-1.5"
                    >
                        <span>Filtrar</span>
                    </button>
                    @if ($search !== '' || $currentStatus !== 'all' || $currentSort !== 'recent')
                        <a 
                            href="{{ route('admin.customers.index') }}" 
                            class="btn-secondary text-xs !min-h-9 !px-2.5 text-slate-500 hover:text-slate-800"
                            title="Limpar filtros"
                        >
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABELA DE CLIENTES --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-xs overflow-hidden">
            @if ($customers->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-4">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Nenhum cliente encontrado</h3>
                    <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                        @if ($search !== '' || $currentStatus !== 'all')
                            Nenhum registro corresponde aos filtros selecionados. Tente ajustar os termos de busca.
                        @else
                            Ainda não há clientes cadastrados na loja.
                        @endif
                    </p>
                    @if ($search !== '' || $currentStatus !== 'all')
                        <a href="{{ route('admin.customers.index') }}" class="btn-secondary mt-4 inline-flex text-xs">
                            Limpar Filtros
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 pl-6 pr-3">Cliente</th>
                                <th class="py-3.5 px-3">Contato</th>
                                <th class="py-3.5 px-3">CPF</th>
                                <th class="py-3.5 px-3">Pedidos</th>
                                <th class="py-3.5 px-3">Gasto Total</th>
                                <th class="py-3.5 px-3">Newsletter</th>
                                <th class="py-3.5 pl-3 pr-6 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @foreach ($customers as $customer)
                                @php
                                    $isSubscribed = $newsletterMap[$customer->email] ?? false;
                                    $hasPhone = !empty($customer->phone);
                                    $cleanPhone = $customer->clean_phone;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    {{-- Nome & Avatar --}}
                                    <td class="py-4 pl-6 pr-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white font-display text-xs font-bold shadow-xs">
                                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('admin.customers.show', $customer) }}" class="font-bold text-slate-900 hover:text-teal-600 transition truncate block max-w-xs">
                                                    {{ $customer->name }}
                                                </a>
                                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 mt-0.5">
                                                    <span>Cadastrado em {{ $customer->created_at->format('d/m/Y') }}</span>
                                                    @if ($customer->isAdmin())
                                                        <span class="rounded bg-rose-50 border border-rose-200 px-1 text-[9px] font-bold text-rose-700 uppercase">
                                                            Admin
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Contato (Email & Telefone) --}}
                                    <td class="py-4 px-3 whitespace-nowrap">
                                        <div class="flex flex-col gap-0.5">
                                            <a href="mailto:{{ $customer->email }}" class="text-slate-800 hover:text-teal-600 transition flex items-center gap-1">
                                                <span class="font-mono text-xs">{{ $customer->email }}</span>
                                            </a>
                                            @if ($hasPhone)
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="font-mono text-[11px] text-slate-500">{{ $customer->phone }}</span>
                                                    <a 
                                                        href="https://wa.me/55{{ $cleanPhone }}" 
                                                        target="_blank" 
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-[10px] font-bold transition"
                                                        title="Chamar no WhatsApp"
                                                    >
                                                        <span>WhatsApp</span>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-[11px] text-slate-400 italic">Sem telefone</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- CPF --}}
                                    <td class="py-4 px-3 font-mono text-xs text-slate-600 whitespace-nowrap">
                                        {{ $customer->cpf ?: '—' }}
                                    </td>

                                    {{-- Pedidos --}}
                                    <td class="py-4 px-3 whitespace-nowrap">
                                        @if ($customer->orders_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $customer->paid_orders_count > 0 ? 'bg-teal-50 text-teal-700 border border-teal-200/80' : 'bg-amber-50 text-amber-700 border border-amber-200/80' }}">
                                                {{ $customer->orders_count }} {{ $customer->orders_count === 1 ? 'pedido' : 'pedidos' }}
                                                @if ($customer->paid_orders_count > 0)
                                                    <span class="text-[10px] font-medium opacity-80">({{ $customer->paid_orders_count }} pagos)</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 font-normal">Nenhum</span>
                                        @endif
                                    </td>

                                    {{-- Gasto Total / LTV --}}
                                    <td class="py-4 px-3 whitespace-nowrap font-mono font-bold text-xs {{ $customer->total_spent > 0 ? 'text-emerald-600 font-bold' : 'text-slate-400 font-normal' }}">
                                        R$ {{ number_format((float) ($customer->total_spent ?? 0), 2, ',', '.') }}
                                    </td>

                                    {{-- Newsletter --}}
                                    <td class="py-4 px-3 whitespace-nowrap">
                                        @if ($isSubscribed)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Inscrito
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-semibold">
                                                Inativo
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Ações --}}
                                    <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            {{-- Ver Detalhes --}}
                                            <a 
                                                href="{{ route('admin.customers.show', $customer) }}" 
                                                class="rounded-lg p-1.5 text-slate-500 hover:bg-teal-50 hover:text-teal-700 transition"
                                                title="Ver Detalhes do Cliente"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Editar --}}
                                            <a 
                                                href="{{ route('admin.customers.edit', $customer) }}" 
                                                class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition"
                                                title="Editar Cliente"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Excluir (Se não for si mesmo e sem pedidos pagos) --}}
                                            @if ($customer->id !== auth()->id() && $customer->paid_orders_count == 0)
                                                <form 
                                                    action="{{ route('admin.customers.destroy', $customer) }}" 
                                                    method="POST" 
                                                    class="inline-block"
                                                    onsubmit="return confirm('Tem certeza que deseja excluir o cliente {{ addslashes($customer->name) }}? Esta ação não poderá ser desfeita.');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition cursor-pointer"
                                                        title="Excluir Cliente"
                                                    >
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if ($customers->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4">
                        {{ $customers->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-admin-layout>
