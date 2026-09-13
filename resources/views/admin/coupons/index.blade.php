<x-admin-layout title="Cupons de Desconto">
    <div class="space-y-6">
        {{-- Header da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        E-commerce & Promoções
                    </span>
                    <span class="font-mono text-xs text-slate-500 font-semibold">
                        {{ $coupons->total() }} {{ $coupons->total() === 1 ? 'cupom' : 'cupons' }}
                    </span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Cupons de Desconto
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Gerencie cupons promocionais com regras de datas, produtos específicos ou gerais e limites de uso.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('admin.coupons.create') }}" 
                    class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-teal-600/20 transition transform active:scale-95 cursor-pointer"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Novo Cupom</span>
                </a>
            </div>
        </div>

        {{-- Métricas Rápidas --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 block">Total de Cupons</span>
                    <span class="font-display text-2xl font-bold text-slate-900 mt-0.5 block">{{ $metrics['total'] }}</span>
                </div>
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-emerald-600 block">Cupons Ativos & Válidos</span>
                    <span class="font-display text-2xl font-bold text-emerald-600 mt-0.5 block">{{ $metrics['active'] }}</span>
                </div>
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-teal-600 block">Utilizações em Pedidos</span>
                    <span class="font-display text-2xl font-bold text-teal-600 mt-0.5 block">{{ $metrics['total_uses'] }}</span>
                </div>
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-teal-50 text-teal-600 border border-teal-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Barra de Busca e Filtros --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <form action="{{ route('admin.coupons.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}"
                        placeholder="Buscar cupom por código ou descrição..." 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-4 py-2.5 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
                    />
                    <svg class="pointer-events-none absolute left-3 top-3 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-2">
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-300 bg-slate-50/60 px-3 py-2.5 text-xs font-semibold text-slate-700 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 shadow-2xs cursor-pointer"
                    >
                        <option value="">Todos os status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Ativos & Válidos</option>
                        <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expirados</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inativos</option>
                    </select>

                    <button type="submit" class="btn-secondary text-xs !min-h-10 !px-4">
                        Filtrar
                    </button>

                    @if($search !== '' || $status !== '')
                        <a href="{{ route('admin.coupons.index') }}" class="btn-secondary text-xs !min-h-10 text-slate-500 hover:text-rose-600">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Cupons --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
            @if($coupons->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-teal-50 text-teal-600 border border-teal-100">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <h2 class="font-display text-base font-bold text-slate-900 mt-4">Nenhum cupom encontrado</h2>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        @if($search !== '' || $status !== '')
                            Nenhum cupom corresponde aos filtros aplicados. Tente ajustar os termos da busca.
                        @else
                            Crie seu primeiro cupom de desconto para alavancar suas campanhas de vendas e conversão.
                        @endif
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('admin.coupons.create') }}" class="btn-teal text-xs !px-4">
                            Cadastrar Cupom
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="border-b border-slate-200 bg-slate-50/80 text-slate-600 uppercase font-mono text-[10px] tracking-wider">
                            <tr>
                                <th class="py-3 px-4 font-bold">Código & Descrição</th>
                                <th class="py-3 px-4 font-bold">Desconto</th>
                                <th class="py-3 px-4 font-bold">Aplicação</th>
                                <th class="py-3 px-4 font-bold">Usos / Limite</th>
                                <th class="py-3 px-4 font-bold">Vigência</th>
                                <th class="py-3 px-4 font-bold">Status</th>
                                <th class="py-3 px-4 font-bold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($coupons as $coupon)
                                <tr class="hover:bg-slate-50/70 transition">
                                    {{-- Código & Descrição --}}
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-2.5 py-1 font-mono text-xs font-extrabold text-teal-400 border border-slate-800">
                                                {{ $coupon->code }}
                                            </span>
                                            <button 
                                                type="button" 
                                                onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); alert('Código copiado: {{ $coupon->code }}');"
                                                class="text-slate-400 hover:text-teal-600 transition" 
                                                title="Copiar código"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                        @if($coupon->description)
                                            <p class="text-[11px] text-slate-500 mt-1 max-w-xs truncate">{{ $coupon->description }}</p>
                                        @endif
                                    </td>

                                    {{-- Desconto --}}
                                    <td class="py-3 px-4">
                                        @if($coupon->discount_type === 'percentage')
                                            <span class="inline-flex items-center rounded-md bg-teal-50 border border-teal-200 px-2 py-0.5 font-bold font-mono text-teal-700">
                                                {{ number_format((float) $coupon->discount_value, 0) }}% OFF
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-emerald-50 border border-emerald-200 px-2 py-0.5 font-bold font-mono text-emerald-700">
                                                R$ {{ number_format((float) $coupon->discount_value, 2, ',', '.') }} OFF
                                            </span>
                                        @endif
                                        @if($coupon->min_order_amount > 0)
                                            <span class="block text-[10px] text-slate-400 mt-0.5">
                                                Mín. R$ {{ number_format((float) $coupon->min_order_amount, 2, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aplicação --}}
                                    <td class="py-3 px-4">
                                        @if($coupon->isApplicableToStorewide())
                                            <span class="inline-flex items-center gap-1 text-slate-700 font-semibold">
                                                <svg class="h-3.5 w-3.5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Toda a Loja (Geral)
                                            </span>
                                        @else
                                            <div class="max-w-[200px] truncate" title="{{ $coupon->product?->title }}">
                                                <span class="text-[10px] uppercase font-bold text-purple-600 block">Produto Específico:</span>
                                                <span class="text-slate-800 font-medium">{{ $coupon->product?->title ?? 'Produto excluído' }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Usos / Limite --}}
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-1.5 font-mono text-xs">
                                            <span class="font-bold text-slate-900">{{ $coupon->times_used }}</span>
                                            <span class="text-slate-400">/</span>
                                            <span class="text-slate-500">
                                                {{ $coupon->max_uses ? $coupon->max_uses : '∞' }}
                                            </span>
                                        </div>
                                        @if($coupon->hasReachedLimit())
                                            <span class="inline-flex text-[10px] font-bold text-rose-600">Limite esgotado</span>
                                        @endif
                                    </td>

                                    {{-- Vigência --}}
                                    <td class="py-3 px-4">
                                        @if(!$coupon->starts_at && !$coupon->expires_at)
                                            <span class="text-slate-400">Sem limite de data</span>
                                        @else
                                            <div class="space-y-0.5 text-[11px]">
                                                @if($coupon->starts_at)
                                                    <span class="text-slate-500 block">Início: {{ $coupon->starts_at->format('d/m/Y') }}</span>
                                                @endif
                                                @if($coupon->expires_at)
                                                    <span class="{{ $coupon->isExpired() ? 'text-rose-600 font-bold' : 'text-slate-700' }} block">
                                                        Fim: {{ $coupon->expires_at->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-3 px-4">
                                        @if(!$coupon->is_active)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 font-semibold text-slate-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                Inativo
                                            </span>
                                        @elseif($coupon->isExpired())
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 font-semibold text-amber-700 border border-amber-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Expirado
                                            </span>
                                        @elseif($coupon->hasReachedLimit())
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 font-semibold text-rose-700 border border-rose-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                Esgotado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 font-semibold text-emerald-700 border border-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Ativo
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Ações --}}
                                    <td class="py-3 px-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a 
                                                href="{{ route('admin.coupons.edit', $coupon) }}" 
                                                class="rounded-lg p-1.5 text-slate-500 hover:text-teal-600 hover:bg-slate-100 transition"
                                                title="Editar Cupom"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <form 
                                                action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('Deseja realmente remover o cupom {{ $coupon->code }}?');"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="rounded-lg p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                    title="Excluir Cupom"
                                                >
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($coupons->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $coupons->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-admin-layout>
