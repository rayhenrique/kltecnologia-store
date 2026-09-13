<x-admin-layout :title="'Detalhes do Pedido #'.$order->id">
    <div class="space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-teal-600 transition">Pedidos</a>
                    <span>/</span>
                    <span class="text-teal-700 font-bold">#{{ $order->id }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Pedido #{{ $order->id }}
                    </h1>
                    <x-status-badge :status="$order->status" />
                    <span class="font-mono text-xs text-slate-400">
                        Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a 
                    href="{{ route('admin.orders.edit', $order) }}" 
                    class="btn-primary !bg-teal-600 hover:!bg-teal-500 text-xs !min-h-10 !px-4 flex items-center gap-2 shadow-xs"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Editar Pedido</span>
                </a>

                <form 
                    method="POST" 
                    action="{{ route('admin.orders.destroy', $order) }}" 
                    onsubmit="return confirm('Tem certeza que deseja excluir o Pedido #{{ $order->id }}? Esta ação removerá o registro da transação e revogará os downloads associados.')"
                >
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="btn-secondary !text-red-600 hover:!bg-red-50 hover:!border-red-200 text-xs !min-h-10 !px-4 flex items-center gap-1.5 shadow-2xs cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Excluir</span>
                    </button>
                </form>

                <a 
                    href="{{ route('admin.orders.index') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-4 flex items-center gap-2 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        {{-- Grid Principal --}}
        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Coluna 1 & 2: Detalhes do Produto e do Cliente --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- CARD DO PRODUTO ADQUIRIDO --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-7 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                        <div class="flex items-center gap-2">
                            <span class="grid h-7 w-7 place-items-center rounded-lg bg-teal-50 text-teal-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </span>
                            <h2 class="font-display text-base font-bold text-slate-900">Produto Adquirido</h2>
                        </div>
                        <a 
                            href="{{ route('admin.products.edit', $order->product) }}" 
                            class="text-xs font-semibold text-teal-600 hover:text-teal-700 hover:underline flex items-center gap-1"
                        >
                            <span>Gerenciar Produto</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-5">
                        {{-- Capa / Thumbnail --}}
                        <div class="h-24 w-24 sm:h-28 sm:w-28 shrink-0 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs">
                            @if($order->product->cover_image)
                                <img 
                                    src="{{ asset('covers/'.$order->product->cover_image) }}" 
                                    alt="{{ $order->product->title }}" 
                                    class="h-full w-full object-cover"
                                />
                            @else
                                <div class="grid h-full w-full place-items-center text-slate-400">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Detalhes --}}
                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($order->product->category)
                                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold text-teal-700 uppercase">
                                        {{ $order->product->category }}
                                    </span>
                                @endif
                                @if($order->product->version)
                                    <span class="rounded bg-slate-100 border border-slate-200 px-2 py-0.5 font-mono text-[10px] font-bold text-slate-600">
                                        v{{ $order->product->version }}
                                    </span>
                                @endif
                                <span class="rounded bg-emerald-50 border border-emerald-200 px-2 py-0.5 font-mono text-[10px] font-bold text-emerald-700">
                                    Licença Vitalícia
                                </span>
                            </div>

                            <h3 class="font-display text-base font-bold text-slate-900 leading-snug">
                                <a href="{{ route('storefront.show', $order->product) }}" target="_blank" class="hover:text-teal-600 transition">
                                    {{ $order->product->title }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-500 line-clamp-2">
                                {{ $order->product->description }}
                            </p>

                            <div class="pt-2 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100">
                                <div class="text-xs text-slate-500">
                                    Preço original: <span class="font-bold text-slate-800">R$ {{ number_format((float) $order->product->price, 2, ',', '.') }}</span>
                                </div>

                                <div>
                                    @if($order->product->file_path)
                                        <div class="flex items-center gap-1.5 text-xs text-emerald-700 font-semibold bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-lg">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Arquivo Pronto ({{ basename($order->product->file_path) }})</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-xs text-amber-700 font-semibold bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>Upload de arquivo pendente no produto</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD DO CLIENTE COMPRADOR --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-7 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                        <div class="flex items-center gap-2">
                            <span class="grid h-7 w-7 place-items-center rounded-lg bg-blue-50 text-blue-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <h2 class="font-display text-base font-bold text-slate-900">Dados do Cliente</h2>
                        </div>
                        <span class="font-mono text-xs text-slate-400">Cliente #{{ $order->user->id }}</span>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-teal-500 to-blue-600 font-display text-base font-bold text-white shadow-sm">
                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 flex-1 text-xs">
                            <div>
                                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block">Nome Completo</span>
                                <span class="font-semibold text-slate-900 text-sm block mt-0.5">{{ $order->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block">E-mail Cadastrado</span>
                                <span class="font-mono text-slate-700 block mt-0.5">{{ $order->user->email }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block">CPF</span>
                                <span class="font-mono text-slate-700 block mt-0.5">{{ $order->user->cpf ?? 'Não informado' }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block">Telefone / WhatsApp</span>
                                <span class="font-mono text-slate-700 block mt-0.5">{{ $order->user->phone ?? 'Não informado' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna 3: Resumo Financeiro & Ações Rápidas --}}
            <div class="space-y-6">
                {{-- CARD RESUMO FINANCEIRO --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-4">
                    <h2 class="font-display text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                        Resumo da Transação
                    </h2>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Valor Cobrado</span>
                        <p class="font-display text-3xl font-extrabold text-slate-900 mt-0.5">
                            R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-slate-100 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Status:</span>
                            <x-status-badge :status="$order->status" />
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Método de Pagamento:</span>
                            <span class="font-bold text-slate-800">{{ $order->payment_method ?? 'Não especificado' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-slate-500">Código de Referência:</span>
                            <span class="font-mono text-[11px] bg-slate-100 text-slate-800 p-2 rounded-lg break-all">
                                {{ $order->gateway_reference ?? 'Sem código externo' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Data de Criação:</span>
                            <span class="font-mono text-slate-700">{{ $order->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Última Atualização:</span>
                            <span class="font-mono text-slate-700">{{ $order->updated_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>

                {{-- CARD DE ALTERAÇÃO RÁPIDA DE STATUS --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-3">
                    <h3 class="font-display text-xs font-bold uppercase tracking-wider text-slate-700">
                        Alteração Rápida de Status
                    </h3>
                    <p class="text-[11px] text-slate-500">
                        Alterne a situação deste pedido com apenas 1 clique:
                    </p>

                    <div class="space-y-2 pt-1">
                        @if($order->status->value !== 'paid')
                            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                                <input type="hidden" name="product_id" value="{{ $order->product_id }}">
                                <input type="hidden" name="amount" value="{{ $order->amount }}">
                                <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                                <input type="hidden" name="gateway_reference" value="{{ $order->gateway_reference }}">
                                <input type="hidden" name="status" value="paid">
                                <button 
                                    type="submit" 
                                    class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white py-2 text-xs font-bold shadow-2xs transition cursor-pointer"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Marcar como Pago (Liberar Download)</span>
                                </button>
                            </form>
                        @endif

                        @if($order->status->value !== 'pending')
                            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                                <input type="hidden" name="product_id" value="{{ $order->product_id }}">
                                <input type="hidden" name="amount" value="{{ $order->amount }}">
                                <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                                <input type="hidden" name="gateway_reference" value="{{ $order->gateway_reference }}">
                                <input type="hidden" name="status" value="pending">
                                <button 
                                    type="submit" 
                                    class="w-full flex items-center justify-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 py-2 text-xs font-bold shadow-2xs transition cursor-pointer"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Marcar como Pendente</span>
                                </button>
                            </form>
                        @endif

                        @if($order->status->value !== 'canceled')
                            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                                <input type="hidden" name="product_id" value="{{ $order->product_id }}">
                                <input type="hidden" name="amount" value="{{ $order->amount }}">
                                <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                                <input type="hidden" name="gateway_reference" value="{{ $order->gateway_reference }}">
                                <input type="hidden" name="status" value="canceled">
                                <button 
                                    type="submit" 
                                    class="w-full flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 py-2 text-xs font-bold shadow-2xs transition cursor-pointer"
                                >
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Marcar como Cancelado</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
