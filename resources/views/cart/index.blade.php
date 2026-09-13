<x-storefront-layout>
    <x-slot:title>Meu Carrinho de Compras</x-slot:title>
<section 
    class="py-8 sm:py-12 bg-slate-950 min-h-[75vh]"
    x-data="{
        items: [],
        couponInput: '',
        appliedCoupon: null,
        couponError: '',
        couponSuccess: '',
        init() {
            this.loadCart();
            window.addEventListener('cart-updated', () => {
                this.loadCart();
            });
        },
        loadCart() {
            try {
                const stored = JSON.parse(localStorage.getItem('kl_cart') || '[]');
                this.items = Array.isArray(stored) ? stored : [];
            } catch(e) {
                this.items = [];
            }
        },
        removeItem(id) {
            this.items = this.items.filter(item => item.id !== id);
            localStorage.setItem('kl_cart', JSON.stringify(this.items));
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.items.length } }));
            window.dispatchEvent(new CustomEvent('toast-message', {
                detail: {
                    message: 'Item removido do carrinho',
                    type: 'info',
                    cartUrl: ''
                }
            }));
        },
        clearCart() {
            if (confirm('Tem certeza que deseja esvaziar seu carrinho?')) {
                this.items = [];
                localStorage.removeItem('kl_cart');
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } }));
                this.appliedCoupon = null;
                this.couponError = '';
                this.couponSuccess = '';
            }
        },
        applyCoupon() {
            const code = this.couponInput.trim().toUpperCase();
            this.couponError = '';
            this.couponSuccess = '';

            if (!code) {
                this.couponError = 'Digite um cupom válido.';
                return;
            }

            if (code === 'VIP10' || code === 'KL10') {
                this.appliedCoupon = { code: code, percent: 10 };
                this.couponSuccess = 'Cupom de 10% de desconto aplicado com sucesso!';
                this.couponInput = '';
            } else if (code === 'KL2026' || code === 'PROMO15') {
                this.appliedCoupon = { code: code, percent: 15 };
                this.couponSuccess = 'Cupom VIP de 15% de desconto aplicado!';
                this.couponInput = '';
            } else {
                this.couponError = 'Cupom inválido ou expirado. Tente VIP10 ou KL2026.';
            }
        },
        removeCoupon() {
            this.appliedCoupon = null;
            this.couponSuccess = '';
            this.couponError = '';
        },
        getSubtotal() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0);
        },
        getDiscount() {
            if (!this.appliedCoupon) return 0;
            return this.getSubtotal() * (this.appliedCoupon.percent / 100);
        },
        getTotal() {
            return Math.max(0, this.getSubtotal() - this.getDiscount());
        },
        formatMoney(amount) {
            return 'R$ ' + (parseFloat(amount) || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }"
>
    <div class="page-container">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-slate-400 mb-6">
            <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition flex items-center gap-1">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Início
            </a>
            <span>/</span>
            <span class="text-teal-400 font-semibold">Meu Carrinho</span>
        </nav>

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                    Carrinho de Compras
                    <span 
                        x-show="items.length > 0" 
                        x-text="items.length + (items.length === 1 ? ' item' : ' itens')" 
                        class="text-xs font-semibold px-2.5 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400"
                    ></span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">
                    Revise seus itens antes de prosseguir para o pagamento seguro via Mercado Pago.
                </p>
            </div>

            <div x-show="items.length > 0" class="flex items-center gap-3">
                <button 
                    type="button" 
                    @click="clearCart()" 
                    class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-rose-400 transition cursor-pointer"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Esvaziar Carrinho
                </button>
            </div>
        </div>

        {{-- Cart Content --}}
        <div class="mt-8">
            {{-- Empty Cart State --}}
            <div 
                x-show="items.length === 0" 
                x-cloak 
                class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 sm:p-16 text-center shadow-xl backdrop-blur-sm"
            >
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-800/80 border border-slate-700 text-teal-400 shadow-inner">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="mt-6 font-display text-xl sm:text-2xl font-bold text-white">Seu carrinho está vazio</h2>
                <p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
                    Você ainda não adicionou nenhum script, sistema ou ferramenta digital. Explore nosso catálogo com dezenas de produtos prontos para produção.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a 
                        href="{{ route('catalog.index') }}" 
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-500 px-6 py-3.5 text-sm font-bold text-slate-950 hover:bg-teal-400 transition shadow-lg shadow-teal-500/25"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Explorar Catálogo de Produtos
                    </a>
                    <a 
                        href="{{ route('storefront.index') }}" 
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-6 py-3.5 text-sm font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition"
                    >
                        Voltar à Página Inicial
                    </a>
                </div>

                {{-- Trust Badges --}}
                <div class="mt-12 pt-8 border-t border-slate-800/60 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left max-w-3xl mx-auto">
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-950/50 border border-slate-800/80">
                        <div class="h-9 w-9 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center shrink-0">
                            ⚡
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Entrega Imediata</p>
                            <p class="text-[11px] text-slate-400">Download liberado no ato</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-950/50 border border-slate-800/80">
                        <div class="h-9 w-9 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center shrink-0">
                            🛡️
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Garantia de 7 Dias</p>
                            <p class="text-[11px] text-slate-400">Código 100% verificado</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-950/50 border border-slate-800/80">
                        <div class="h-9 w-9 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center shrink-0">
                            💬
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Suporte Humano</p>
                            <p class="text-[11px] text-slate-400">Atendimento WhatsApp</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filled Cart Layout --}}
            <div x-show="items.length > 0" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                {{-- Items List --}}
                <div class="lg:col-span-8 space-y-4">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 overflow-hidden shadow-xl backdrop-blur-sm">
                        <div class="p-4 sm:p-6 divide-y divide-slate-800/70">
                            <template x-for="item in items" :key="item.id">
                                <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition group">
                                    {{-- Item Info --}}
                                    <div class="flex items-center gap-4">
                                        {{-- Thumbnail --}}
                                        <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-2xl bg-slate-950 border border-slate-800 overflow-hidden shrink-0 flex items-center justify-center">
                                            <template x-if="item.cover_image">
                                                <img :src="item.cover_image" :alt="item.title" class="h-full w-full object-cover group-hover:scale-105 transition duration-300" />
                                            </template>
                                            <template x-if="!item.cover_image">
                                                <svg class="h-8 w-8 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                </svg>
                                            </template>
                                        </div>

                                        {{-- Title & Meta --}}
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="rounded bg-teal-500/10 border border-teal-500/20 px-2 py-0.5 text-[10px] font-bold text-teal-400" x-text="item.category || 'Sistema Web'"></span>
                                                <span class="text-[11px] text-emerald-400 flex items-center gap-1 font-mono">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                                    Download Imediato
                                                </span>
                                            </div>
                                            <h3 class="text-sm sm:text-base font-bold text-white hover:text-teal-400 transition line-clamp-2">
                                                <a :href="'/produtos/' + item.slug" x-text="item.title"></a>
                                            </h3>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                Licença Vitalícia com Acesso Ilimitado
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Price & Actions --}}
                                    <div class="flex items-center justify-between sm:justify-end gap-6 shrink-0 pt-2 sm:pt-0 border-t border-slate-800/50 sm:border-0">
                                        <div class="text-left sm:text-right">
                                            <p class="text-xs text-slate-400">Valor unitário</p>
                                            <p class="font-mono text-base sm:text-lg font-bold text-teal-400" x-text="formatMoney(item.price)"></p>
                                        </div>

                                        <button 
                                            type="button" 
                                            @click="removeItem(item.id)" 
                                            class="rounded-xl p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition cursor-pointer"
                                            title="Remover produto"
                                            aria-label="Remover produto do carrinho"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Card Footer Actions --}}
                        <div class="p-4 sm:p-6 bg-slate-950/60 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <a 
                                href="{{ route('catalog.index') }}" 
                                class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-teal-400 transition"
                            >
                                ← Continuar Explorando Catálogo
                            </a>
                            <span class="text-xs text-slate-500">
                                Preços em Reais (BRL) • Sem mensalidades ou taxas ocultas
                            </span>
                        </div>
                    </div>

                    {{-- Security info banner --}}
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center shrink-0">
                                🔒
                            </div>
                            <span>
                                Pagamento 100% criptografado e seguro processado pelo <strong class="text-white font-semibold">Mercado Pago</strong>.
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-emerald-400 font-mono text-[11px] shrink-0">
                            <span>Pix Instantâneo</span> • <span>Cartão de Crédito</span>
                        </div>
                    </div>
                </div>

                {{-- Order Summary Sidebar --}}
                <div class="lg:col-span-4 sticky top-24 space-y-6">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-6 shadow-2xl backdrop-blur-md">
                        <h2 class="font-display text-lg font-bold text-white pb-4 border-b border-slate-800 flex items-center justify-between">
                            <span>Resumo do Pedido</span>
                            <span class="text-xs font-mono font-normal text-teal-400 bg-teal-500/10 px-2 py-0.5 rounded-md border border-teal-500/20">Checkout Seguro</span>
                        </h2>

                        {{-- Price Breakdown --}}
                        <div class="mt-6 space-y-3 text-sm">
                            <div class="flex items-center justify-between text-slate-300">
                                <span>Subtotal (<span x-text="items.length"></span> <span x-text="items.length === 1 ? 'item' : 'itens'"></span>)</span>
                                <span class="font-mono font-semibold" x-text="formatMoney(getSubtotal())"></span>
                            </div>

                            {{-- Applied Coupon Row --}}
                            <div x-show="appliedCoupon" x-cloak class="flex items-center justify-between text-emerald-400">
                                <span class="flex items-center gap-1.5">
                                    <span>Desconto Cupom (<span x-text="appliedCoupon?.code"></span>)</span>
                                    <button type="button" @click="removeCoupon()" class="text-rose-400 hover:text-rose-300 text-xs font-bold cursor-pointer" title="Remover cupom">×</button>
                                </span>
                                <span class="font-mono font-semibold" x-text="'- ' + formatMoney(getDiscount())"></span>
                            </div>

                            <div class="flex items-center justify-between text-slate-400 text-xs">
                                <span>Entrega Digital</span>
                                <span class="text-emerald-400 font-bold uppercase tracking-wider text-[11px]">Grátis / Imediata</span>
                            </div>

                            <div class="pt-4 border-t border-slate-800 flex items-baseline justify-between">
                                <div>
                                    <span class="font-display text-base font-bold text-white">Valor Total</span>
                                    <p class="text-[11px] text-slate-400">À vista no Pix ou até 12x no Cartão</p>
                                </div>
                                <span class="font-mono text-2xl font-black text-teal-400" x-text="formatMoney(getTotal())"></span>
                            </div>
                        </div>

                        {{-- Coupon Input --}}
                        <div class="mt-6 pt-6 border-t border-slate-800">
                            <label for="coupon-code-input" class="block text-xs font-semibold text-slate-300 mb-2">Possui cupom de desconto?</label>
                            <div class="flex items-center gap-2">
                                <input 
                                    id="coupon-code-input"
                                    type="text" 
                                    x-model="couponInput"
                                    placeholder="Ex: VIP10 ou KL2026"
                                    class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 uppercase font-mono focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                    @keydown.enter.prevent="applyCoupon()"
                                />
                                <button 
                                    type="button" 
                                    @click="applyCoupon()" 
                                    class="rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 px-4 py-2.5 text-xs font-bold text-white transition shrink-0 cursor-pointer"
                                >
                                    Aplicar
                                </button>
                            </div>
                            <p x-show="couponError" x-text="couponError" x-cloak class="mt-2 text-xs text-rose-400"></p>
                            <p x-show="couponSuccess" x-text="couponSuccess" x-cloak class="mt-2 text-xs text-emerald-400"></p>
                        </div>

                        {{-- Checkout Action --}}
                        <div class="mt-6 pt-6 border-t border-slate-800">
                            @auth
                                {{-- Checkout Button for Authenticated User --}}
                                <template x-if="items.length === 1">
                                    <form method="POST" :action="'/checkout/' + items[0].id" x-data="{ submitting: false }" x-on:submit="submitting = true">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            :disabled="submitting" 
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-500 hover:bg-teal-400 px-6 py-4 text-sm font-extrabold text-slate-950 shadow-xl shadow-teal-500/25 transition-all duration-200 cursor-pointer disabled:opacity-50"
                                        >
                                            <span x-show="!submitting" class="flex items-center gap-2">
                                                <span>Finalizar Compra Agora</span>
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </span>
                                            <span x-show="submitting" x-cloak class="flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4 text-slate-950" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                <span>Abrindo Mercado Pago...</span>
                                            </span>
                                        </button>
                                    </form>
                                </template>

                                <template x-if="items.length > 1">
                                    <div class="space-y-3">
                                        <p class="text-xs text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded-xl p-3">
                                            💡 <strong>Compras Individuais:</strong> Os sistemas e scripts possuem licenças independentes. Escolha abaixo qual item deseja iniciar o checkout primeiro:
                                        </p>
                                        <div class="space-y-2">
                                            <template x-for="item in items" :key="item.id">
                                                <form method="POST" :action="'/checkout/' + item.id">
                                                    @csrf
                                                    <button 
                                                        type="submit" 
                                                        class="w-full flex items-center justify-between rounded-xl border border-teal-500/40 bg-teal-500/10 hover:bg-teal-500/20 px-4 py-2.5 text-xs font-bold text-teal-300 transition cursor-pointer text-left"
                                                    >
                                                        <span class="truncate pr-2" x-text="'Pagar ' + item.title"></span>
                                                        <span class="font-mono shrink-0" x-text="formatMoney(item.price)"></span>
                                                    </button>
                                                </form>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            @else
                                {{-- Guest Flow: Prompt to Login / Register --}}
                                <div class="space-y-3">
                                    <a 
                                        href="{{ route('login') }}" 
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-500 hover:bg-teal-400 px-6 py-4 text-sm font-extrabold text-slate-950 shadow-xl shadow-teal-500/25 transition-all duration-200"
                                    >
                                        <span>Entrar para Finalizar Compra</span>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                    <p class="text-[11px] text-slate-400 text-center">
                                        Ainda não tem conta? <a href="{{ route('register') }}" class="text-teal-400 hover:underline font-semibold">Cadastre-se grátis</a> em menos de 1 minuto.
                                    </p>
                                </div>
                            @endauth

                            {{-- Payment Icons & Badges --}}
                            <div class="mt-6 pt-6 border-t border-slate-800 text-center">
                                <p class="text-[11px] text-slate-400 mb-3">Formas de pagamento aceitas:</p>
                                <div class="flex items-center justify-center gap-3 text-slate-300">
                                    <span class="rounded-lg bg-slate-950 border border-slate-800 px-2.5 py-1 text-xs font-mono font-bold text-teal-400">⚡ Pix</span>
                                    <span class="rounded-lg bg-slate-950 border border-slate-800 px-2.5 py-1 text-xs font-semibold">💳 Cartão de Crédito</span>
                                    <span class="rounded-lg bg-slate-950 border border-slate-800 px-2.5 py-1 text-xs font-semibold">📄 Boleto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recommended Products Section --}}
        @if($featuredProducts->isNotEmpty())
            <div class="mt-20 pt-12 border-t border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="text-xs font-mono font-bold uppercase tracking-wider text-teal-400">
                            Recomendações Especiais
                        </span>
                        <h2 class="font-display text-xl sm:text-2xl font-bold text-white mt-1">
                            Você Também Pode Gostar
                        </h2>
                    </div>
                    <a 
                        href="{{ route('catalog.index') }}" 
                        class="text-xs sm:text-sm font-semibold text-teal-400 hover:text-teal-300 transition flex items-center gap-1 group"
                    >
                        Ver Catálogo Completo 
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        <div class="group relative flex flex-col rounded-3xl border border-slate-800 bg-slate-900/60 p-4 transition-all duration-300 hover:-translate-y-1 hover:border-teal-500/40 hover:shadow-xl hover:shadow-teal-950/30">
                            {{-- Cover --}}
                            <div class="relative aspect-16/10 w-full overflow-hidden rounded-2xl bg-slate-950 border border-slate-800/80">
                                @if($product->cover_image)
                                    <img 
                                        src="{{ $product->cover_image }}" 
                                        alt="{{ $product->title }}" 
                                        class="h-full w-full object-cover group-hover:scale-105 transition duration-500"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-slate-700">
                                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    </div>
                                @endif
                                <span class="absolute top-2.5 left-2.5 rounded-lg bg-slate-950/80 backdrop-blur-md px-2 py-0.5 text-[10px] font-bold text-teal-400 border border-slate-800">
                                    {{ $product->categoryGroup?->name ?? $product->category ?? 'Sistema Web' }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <div class="mt-4 flex flex-1 flex-col justify-between">
                                <div>
                                    <h3 class="font-display text-sm font-bold text-white line-clamp-2 group-hover:text-teal-400 transition">
                                        <a href="{{ route('storefront.show', $product) }}">
                                            {{ $product->title }}
                                        </a>
                                    </h3>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between gap-2">
                                    <div>
                                        <span class="text-[10px] text-slate-400">Valor</span>
                                        <p class="font-mono text-base font-bold text-teal-400">
                                            R$ {{ number_format($product->price, 2, ',', '.') }}
                                        </p>
                                    </div>

                                    <button 
                                        type="button" 
                                        @click="window.addToCart({
                                            id: {{ $product->id }},
                                            title: {{ json_encode($product->title) }},
                                            slug: {{ json_encode($product->slug) }},
                                            price: {{ (float) $product->price }},
                                            cover_image: {{ json_encode($product->cover_image) }},
                                            category: {{ json_encode($product->categoryGroup?->name ?? $product->category ?? 'Sistema Web') }}
                                        })"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 hover:bg-teal-500 hover:text-slate-950 transition cursor-pointer"
                                        title="Adicionar ao Carrinho"
                                        aria-label="Adicionar produto ao carrinho"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
</x-storefront-layout>
