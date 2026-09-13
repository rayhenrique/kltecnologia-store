<x-app-layout>
    <x-slot:title>Meus Downloads & Pedidos</x-slot:title>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-wider text-teal-600">Área do Cliente</p>
                <h1 class="mt-1 font-display text-2xl font-bold text-slate-900">Meus Downloads & Pedidos</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Acesse seus códigos fonte adquiridos, links assinados e acompanhe o status de aprovação de pagamentos.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('catalog.index') }}" 
                    class="btn-secondary text-xs sm:text-sm flex items-center gap-1.5"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Explorar Catálogo</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-container py-8">
        {{-- Grade de Pedidos e Downloads --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($downloads as $item)
                @php($order = $item['order'])
                <article class="panel flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
                    {{-- Capa / Imagem --}}
                    <div class="relative aspect-16/10 w-full overflow-hidden bg-slate-950">
                        @if($order->product->cover_path)
                            <img src="{{ asset($order->product->cover_path) }}" alt="{{ $order->product->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-900 to-teal-950 font-display text-3xl font-bold text-teal-400">
                                KL
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="rounded-lg bg-slate-950/80 backdrop-blur-md px-2.5 py-1 text-[11px] font-mono font-bold text-teal-300 border border-slate-800">
                                PEDIDO #{{ $order->id }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            @if($order->status === \App\Enums\OrderStatus::Paid)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-white shadow-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                                    Pago • Liberado
                                </span>
                            @elseif($order->status === \App\Enums\OrderStatus::Pending)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-white shadow-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                    Aguardando Pagamento
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-white shadow-sm">
                                    Cancelado / Falho
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Conteúdo do Card --}}
                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                            <span>Compra em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</span>
                            <span class="font-mono font-bold text-slate-800">R$ {{ number_format($order->amount, 2, ',', '.') }}</span>
                        </div>

                        <h2 class="font-display text-base sm:text-lg font-bold text-slate-900 line-clamp-2">
                            {{ $order->product->title }}
                        </h2>

                        <div class="mt-auto pt-5">
                            @if($order->status === \App\Enums\OrderStatus::Paid)
                                @if($item['download_url'])
                                    <a 
                                        href="{{ $item['download_url'] }}" 
                                        class="btn-primary w-full inline-flex items-center justify-center gap-2 !py-2.5 text-sm font-bold shadow-md shadow-teal-700/20"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span>Baixar Arquivo (.ZIP)</span>
                                    </a>
                                    <p class="mt-2 text-center text-[11px] text-slate-500">
                                        Link criptografado temporário (válido por 10 min).
                                    </p>
                                @else
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-center text-xs text-slate-500">
                                        Arquivo em processamento pela equipe técnica.
                                    </div>
                                @endif
                            @elseif($order->status === \App\Enums\OrderStatus::Pending)
                                <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-3 text-xs text-amber-900 space-y-2">
                                    <p class="font-semibold flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Aguardando confirmação do Mercado Pago</span>
                                    </p>
                                    <p class="text-[11px] text-amber-800 leading-relaxed">
                                        Se você pagou via Pix ou Cartão, a confirmação acontece em segundos. Clique no botão abaixo para atualizar.
                                    </p>
                                    <button 
                                        type="button" 
                                        onclick="window.location.reload()" 
                                        class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-amber-300 bg-white hover:bg-amber-100 py-1.5 px-3 text-xs font-bold text-amber-900 transition cursor-pointer"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span>Verificar Confirmação</span>
                                    </button>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <p class="text-xs text-rose-600 text-center">
                                        Pagamento cancelado ou não concluído no Mercado Pago.
                                    </p>
                                    <a 
                                        href="{{ route('checkout.index', ['product' => $order->product->slug]) }}" 
                                        class="btn-secondary w-full inline-flex items-center justify-center gap-1 text-xs"
                                    >
                                        <span>Tentar Novamente</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel col-span-full rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-50 text-teal-600 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h2 class="font-display text-xl font-bold text-slate-900">Sua biblioteca está vazia</h2>
                    <p class="mt-2 text-sm text-slate-500 max-w-md mx-auto">
                        Você ainda não possui pedidos ou produtos adquiridos. Navegue pelo catálogo e adquira scripts com código fonte completo.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('catalog.index') }}" class="btn-primary inline-flex items-center gap-2">
                            <span>Conhecer Catálogo de Produtos</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        <div class="mt-8">
            {{ $downloads->links() }}
        </div>
    </div>
</x-app-layout>
