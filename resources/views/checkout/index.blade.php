<x-storefront-layout>
    <x-slot:title>Checkout Seguro — Finalizar Compra</x-slot:title>

    <div 
        class="bg-slate-950 py-8 sm:py-12 min-h-[80vh]"
        x-data="{
            isDirectProduct: {{ $product ? 'true' : 'false' }},
            items: @if($product) [
                {
                    id: {{ $product->id }},
                    title: {{ json_encode($product->title, JSON_UNESCAPED_UNICODE) }},
                    slug: {{ json_encode($product->slug, JSON_UNESCAPED_UNICODE) }},
                    price: {{ (float) $product->price }},
                    cover_image: {{ json_encode($product->cover_image, JSON_UNESCAPED_UNICODE) }},
                    category: {{ json_encode($product->categoryGroup?->name ?? $product->category ?? 'Sistema Web', JSON_UNESCAPED_UNICODE) }}
                }
            ] @else [] @endif,
            couponInput: '',
            appliedCoupon: null,
            couponError: '',
            couponSuccess: '',
            submitting: false,
            showPassword: false,
            showPasswordConfirm: false,
            init() {
                if (!this.isDirectProduct) {
                    try {
                        const stored = JSON.parse(localStorage.getItem('kl_cart') || '[]');
                        this.items = Array.isArray(stored) ? stored : [];
                    } catch(e) {
                        this.items = [];
                    }
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
            },
            handleSubmit() {
                this.submitting = true;
                // Esvazia carrinho local caso compra seja iniciada com sucesso
                if (!this.isDirectProduct) {
                    localStorage.removeItem('kl_cart');
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } }));
                }
            }
        }"
    >
        <div class="page-container">
            {{-- Breadcrumbs --}}
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-slate-400 mb-6">
                <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Início
                </a>
                <span>/</span>
                <a href="{{ route('cart.index') }}" class="hover:text-teal-400 transition">Meu Carrinho</a>
                <span>/</span>
                <span class="text-teal-400 font-semibold">Checkout Seguro</span>
            </nav>

            {{-- Page Header --}}
            <div class="pb-6 border-b border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-teal-500/30 bg-teal-500/10 px-3 py-1 text-xs font-semibold text-teal-400 mb-2">
                            <span class="h-2 w-2 rounded-full bg-teal-400 animate-pulse"></span>
                            Ambiente 100% Criptografado SSL 256-Bit
                        </div>
                        <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Finalizar Compra
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-400 mt-1">
                            Preencha os dados abaixo para gerar sua conta e receber o acesso imediato ao código fonte e downloads.
                        </p>
                    </div>

                    {{-- Trust indicators --}}
                    <div class="flex items-center gap-4 text-xs text-slate-400 shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-emerald-400 text-base">⚡</span>
                            <span>Acesso Imediato</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-teal-400 text-base">🛡️</span>
                            <span>Garantia 7 Dias</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('error'))
                <div class="mt-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 text-xs sm:text-sm text-rose-300 flex items-start gap-3">
                    <svg class="h-5 w-5 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-bold">Atenção ao finalizar pedido</p>
                        <p class="mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 text-xs sm:text-sm text-rose-300">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Por favor, corrija os seguintes itens antes de prosseguir:
                    </p>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Empty Items Warning (quando não há produto direto nem itens no carrinho) --}}
            <div x-show="items.length === 0" x-cloak class="mt-8 rounded-3xl border border-slate-800 bg-slate-900/60 p-8 sm:p-12 text-center shadow-xl">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-800 text-teal-400 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="font-display text-xl font-bold text-white">Nenhum produto selecionado</h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-400 max-w-md mx-auto">
                    Seu carrinho de compras está vazio. Navegue pelo catálogo para escolher o script ou sistema que deseja adquirir.
                </p>
                <div class="mt-6">
                    <a href="{{ route('catalog.index') }}" class="btn-teal inline-flex items-center gap-2">
                        <span>Explorar Catálogo de Produtos</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            {{-- Main Checkout Layout --}}
            <div x-show="items.length > 0" class="mt-8">
                <form 
                    method="POST" 
                    action="{{ route('checkout.process') }}" 
                    id="checkout-form"
                    novalidate 
                    x-on:submit="handleSubmit()"
                >
                    @csrf

                    {{-- Hidden Fields para Produtos e Cupom --}}
                    @if($product)
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @else
                        <template x-for="item in items" :key="item.id">
                            <input type="hidden" name="items[]" :value="item.id">
                        </template>
                    @endif
                    <input type="hidden" name="coupon" :value="appliedCoupon ? appliedCoupon.code : ''">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        {{-- Coluna da Esquerda: Formulário de Identificação / Conta & Pagamento (7 cols) --}}
                        <div class="lg:col-span-7 space-y-6">
                            
                            {{-- Card 1: Identificação do Comprador / Criação de Conta --}}
                            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 sm:p-8 shadow-xl backdrop-blur-md">
                                <div class="flex items-center justify-between pb-5 border-b border-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 font-display font-bold flex items-center justify-center shrink-0">
                                            1
                                        </div>
                                        <div>
                                            <h2 class="font-display text-base sm:text-lg font-bold text-white">
                                                @auth
                                                    Seus Dados Cadastrais
                                                @else
                                                    Identificação & Criação de Conta
                                                @endauth
                                            </h2>
                                            <p class="text-xs text-slate-400">
                                                @auth
                                                    Confirme suas informações para emissão e download.
                                                @else
                                                    Sua conta na KL Tecnologia será criada com estes dados.
                                                @endauth
                                            </p>
                                        </div>
                                    </div>

                                    @guest
                                        <a 
                                            href="{{ route('login', ['redirect' => request()->fullUrl()]) }}" 
                                            class="text-xs text-teal-400 hover:text-teal-300 transition font-semibold hover:underline hidden sm:inline-block"
                                        >
                                            Já tem conta? Fazer login &rarr;
                                        </a>
                                    @endguest
                                </div>

                                @auth
                                    {{-- Usuário Logado --}}
                                    <div class="mt-6 space-y-5">
                                        <div class="rounded-2xl border border-teal-500/30 bg-teal-500/5 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-xl bg-teal-500/20 text-teal-300 font-bold flex items-center justify-center font-mono">
                                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-white text-sm">{{ auth()->user()->name }}</span>
                                                        <span class="rounded bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 text-[10px] font-semibold text-teal-400">Conectado</span>
                                                    </div>
                                                    <p class="text-xs text-slate-400 mt-0.5">{{ auth()->user()->email }}</p>
                                                </div>
                                            </div>

                                            <span class="text-xs text-slate-400 font-mono">
                                                ID Cliente: #{{ auth()->id() }}
                                            </span>
                                        </div>

                                        {{-- Campos de CPF e Telefone (exigências Mercado Pago para emissão correta) --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="cpf" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                    CPF <span class="text-rose-400">*</span>
                                                    <span class="text-[10px] font-normal text-slate-500 lowercase">(exigido pelo Pix/gateway)</span>
                                                </label>
                                                <input 
                                                    id="cpf" 
                                                    type="text" 
                                                    name="cpf" 
                                                    value="{{ old('cpf', auth()->user()->cpf) }}" 
                                                    required 
                                                    inputmode="numeric"
                                                    maxlength="14"
                                                    placeholder="000.000.000-00"
                                                    class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                                />
                                                <x-input-error :messages="$errors->get('cpf')" class="mt-1 text-xs text-rose-400" />
                                            </div>

                                            <div>
                                                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                    WhatsApp / Celular <span class="text-rose-400">*</span>
                                                    <span class="text-[10px] font-normal text-slate-500 lowercase">(com DDD)</span>
                                                </label>
                                                <input 
                                                    id="phone" 
                                                    type="tel" 
                                                    name="phone" 
                                                    value="{{ old('phone', auth()->user()->phone) }}" 
                                                    required 
                                                    inputmode="tel"
                                                    maxlength="15"
                                                    placeholder="(11) 99999-9999"
                                                    class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                                />
                                                <x-input-error :messages="$errors->get('phone')" class="mt-1 text-xs text-rose-400" />
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Visitante: Criação de Conta Integrada no Checkout --}}
                                    <div class="mt-6 space-y-4">
                                        <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-4 text-xs text-slate-400 flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-2.5">
                                                <span class="text-teal-400 text-base">✨</span>
                                                <span>Sua conta de acesso à biblioteca de downloads será criada automaticamente nesta compra.</span>
                                            </div>
                                            <a 
                                                href="{{ route('login', ['redirect' => request()->fullUrl()]) }}" 
                                                class="text-teal-400 hover:text-teal-300 underline font-semibold shrink-0 sm:hidden text-xs"
                                            >
                                                Já tem conta?
                                            </a>
                                        </div>

                                        {{-- Nome Completo --}}
                                        <div>
                                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                Nome Completo <span class="text-rose-400">*</span>
                                            </label>
                                            <input 
                                                id="name" 
                                                type="text" 
                                                name="name" 
                                                value="{{ old('name') }}" 
                                                required 
                                                autofocus 
                                                placeholder="Seu nome completo"
                                                class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                            />
                                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-400" />
                                        </div>

                                        {{-- E-mail --}}
                                        <div>
                                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                Endereço de E-mail <span class="text-rose-400">*</span>
                                                <span class="text-[10px] font-normal text-slate-500 lowercase">(onde receberá os downloads)</span>
                                            </label>
                                            <input 
                                                id="email" 
                                                type="email" 
                                                name="email" 
                                                value="{{ old('email') }}" 
                                                required 
                                                placeholder="seu.email@exemplo.com"
                                                class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                            />
                                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400" />
                                        </div>

                                        {{-- Grid CPF e Telefone --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="cpf" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                    CPF <span class="text-rose-400">*</span>
                                                    <span class="text-[10px] font-normal text-slate-500 lowercase">(Mercado Pago/Pix)</span>
                                                </label>
                                                <input 
                                                    id="cpf" 
                                                    type="text" 
                                                    name="cpf" 
                                                    value="{{ old('cpf') }}" 
                                                    required 
                                                    inputmode="numeric"
                                                    maxlength="14"
                                                    placeholder="000.000.000-00"
                                                    class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                                />
                                                <x-input-error :messages="$errors->get('cpf')" class="mt-1 text-xs text-rose-400" />
                                            </div>

                                            <div>
                                                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                    WhatsApp / Celular <span class="text-rose-400">*</span>
                                                    <span class="text-[10px] font-normal text-slate-500 lowercase">(com DDD)</span>
                                                </label>
                                                <input 
                                                    id="phone" 
                                                    type="tel" 
                                                    name="phone" 
                                                    value="{{ old('phone') }}" 
                                                    required 
                                                    inputmode="tel"
                                                    maxlength="15"
                                                    placeholder="(11) 99999-9999"
                                                    class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                                />
                                                <x-input-error :messages="$errors->get('phone')" class="mt-1 text-xs text-rose-400" />
                                            </div>
                                        </div>

                                        {{-- Grid Senha e Confirmação --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                    Criar Senha de Acesso <span class="text-rose-400">*</span>
                                                </label>
                                                <div class="relative">
                                                    <input 
                                                        id="password" 
                                                        :type="showPassword ? 'text' : 'password'" 
                                                        name="password" 
                                                        required 
                                                        placeholder="Mínimo 8 dígitos"
                                                        class="block w-full rounded-xl border border-slate-700 bg-slate-950 pl-3.5 pr-10 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                                    />
                                                    <button 
                                                        type="button" 
                                                        @click="showPassword = !showPassword" 
                                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300 cursor-pointer"
                                                        tabindex="-1"
                                                    >
                                                        <span x-show="!showPassword" class="text-xs font-mono">👁️</span>
                                                        <span x-show="showPassword" x-cloak class="text-xs font-mono">🙈</span>
                                                    </button>
                                                </div>
                                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400" />
                                            </div>

                                            <div>
                                                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                                                    Confirmar Senha <span class="text-rose-400">*</span>
                                                </label>
                                                <div class="relative">
                                                    <input 
                                                        id="password_confirmation" 
                                                        :type="showPasswordConfirm ? 'text' : 'password'" 
                                                        name="password_confirmation" 
                                                        required 
                                                        placeholder="Repita a senha"
                                                        class="block w-full rounded-xl border border-slate-700 bg-slate-950 pl-3.5 pr-10 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                                    />
                                                    <button 
                                                        type="button" 
                                                        @click="showPasswordConfirm = !showPasswordConfirm" 
                                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300 cursor-pointer"
                                                        tabindex="-1"
                                                    >
                                                        <span x-show="!showPasswordConfirm" class="text-xs font-mono">👁️</span>
                                                        <span x-show="showPasswordConfirm" x-cloak class="text-xs font-mono">🙈</span>
                                                    </button>
                                                </div>
                                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-400" />
                                            </div>
                                        </div>
                                    </div>
                                @endauth
                            </div>

                            {{-- Card 2: Pagamento Seguro Mercado Pago --}}
                            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 sm:p-8 shadow-xl backdrop-blur-md">
                                <div class="flex items-center gap-3 pb-5 border-b border-slate-800">
                                    <div class="h-9 w-9 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 font-display font-bold flex items-center justify-center shrink-0">
                                        2
                                    </div>
                                    <div>
                                        <h2 class="font-display text-base sm:text-lg font-bold text-white">
                                            Forma de Pagamento
                                        </h2>
                                        <p class="text-xs text-slate-400">
                                            Processado com tecnologia e antifraude oficial do Mercado Pago.
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 space-y-4">
                                    {{-- Box Informativo para Pedido Gratuito --}}
                                    <div x-show="getTotal() === 0" x-cloak class="rounded-2xl border-2 border-emerald-500/50 bg-emerald-500/10 p-5 relative overflow-hidden">
                                        <div class="flex items-start gap-3.5">
                                            <div class="h-10 w-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                                                🎁
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm text-white flex items-center gap-2">
                                                    <span>Pedido 100% Gratuito</span>
                                                    <span class="rounded bg-emerald-500/20 text-emerald-300 px-2 py-0.5 text-[10px] font-mono font-bold">Sem Cobrança</span>
                                                </p>
                                                <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                                                    Nenhum pagamento ou cartão é necessário. Ao clicar em <strong>Liberar Download Grátis</strong>, sua conta será confirmada e seus arquivos serão liberados imediatamente.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Card Mercado Pago Selecionado (Exibido apenas quando houver cobrança) --}}
                                    <div x-show="getTotal() > 0" class="rounded-2xl border-2 border-teal-500/50 bg-teal-500/5 p-5 relative overflow-hidden">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="h-5 w-5 rounded-full border-2 border-teal-400 bg-teal-400 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-slate-950"></div>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-sm text-white flex items-center gap-2">
                                                        <span>Mercado Pago Oficial</span>
                                                        <span class="rounded bg-teal-500/20 text-teal-300 px-2 py-0.5 text-[10px] font-mono font-bold">Aprovação Imediata</span>
                                                    </p>
                                                    <p class="text-xs text-slate-400 mt-0.5">
                                                        Pix Instantâneo, Cartão de Crédito em até 12x ou Boleto Bancário
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Badges de Métodos Aceitos --}}
                                        <div class="mt-4 pt-4 border-t border-slate-800/80 flex flex-wrap items-center gap-2.5 text-xs">
                                            <span class="rounded-lg bg-slate-950 border border-slate-800 px-3 py-1 text-teal-400 font-bold font-mono">
                                                ⚡ Pix (QR Code & Copia e Cola)
                                            </span>
                                            <span class="rounded-lg bg-slate-950 border border-slate-800 px-3 py-1 text-slate-300 font-medium">
                                                💳 Cartão de Crédito (até 12x)
                                            </span>
                                            <span class="rounded-lg bg-slate-950 border border-slate-800 px-3 py-1 text-slate-400 font-medium">
                                                📄 Boleto Bancário
                                            </span>
                                        </div>

                                        <p class="mt-3 text-[11px] text-slate-400 leading-relaxed">
                                            Ao clicar em <strong>Finalizar Compra</strong>, você será encaminhado para o ambiente seguro do Mercado Pago para efetuar o pagamento. Assim que confirmado, seu download será liberado no mesmo segundo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Coluna da Direita: Resumo do Pedido & Ação (5 cols) --}}
                        <div class="lg:col-span-5 sticky top-24 space-y-6">
                            <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 sm:p-7 shadow-2xl backdrop-blur-md">
                                <h2 class="font-display text-lg font-bold text-white pb-4 border-b border-slate-800 flex items-center justify-between">
                                    <span>Resumo da Compra</span>
                                    <span class="text-xs font-mono text-teal-400 bg-teal-500/10 px-2.5 py-1 rounded-md border border-teal-500/20">
                                        <span x-text="items.length"></span> <span x-text="items.length === 1 ? 'item' : 'itens'"></span>
                                    </span>
                                </h2>

                                {{-- Lista de Itens do Checkout --}}
                                <div class="mt-4 divide-y divide-slate-800/60 max-h-72 overflow-y-auto pr-1">
                                    @if($product)
                                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center gap-3">
                                            <div class="h-14 w-14 rounded-xl bg-slate-950 border border-slate-800 overflow-hidden shrink-0 flex items-center justify-center">
                                                @if($product->cover_image)
                                                    <img src="{{ $product->cover_image }}" alt="{{ $product->title }}" class="h-full w-full object-cover">
                                                @else
                                                    <span class="text-xs font-bold text-teal-400">KL</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <span class="text-[10px] font-bold text-teal-400 uppercase">{{ $product->categoryGroup?->name ?? $product->category ?? 'Sistema Web' }}</span>
                                                <h3 class="text-xs font-bold text-white truncate">{{ $product->title }}</h3>
                                                <p class="text-[11px] text-slate-400">Acesso Vitalício</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                @if($product->price <= 0)
                                                    <span class="rounded bg-emerald-500/10 border border-emerald-500/30 px-2 py-0.5 text-xs font-bold font-mono text-emerald-400">GRÁTIS</span>
                                                @else
                                                    <p class="font-mono text-sm font-bold text-teal-400">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <template x-for="item in items" :key="item.id">
                                            <div class="py-3.5 first:pt-0 last:pb-0 flex items-center gap-3">
                                                <div class="h-14 w-14 rounded-xl bg-slate-950 border border-slate-800 overflow-hidden shrink-0 flex items-center justify-center">
                                                    <template x-if="item.cover_image">
                                                        <img :src="item.cover_image" :alt="item.title" class="h-full w-full object-cover">
                                                    </template>
                                                    <template x-if="!item.cover_image">
                                                        <span class="text-xs font-bold text-teal-400">KL</span>
                                                    </template>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <span class="text-[10px] font-bold text-teal-400 uppercase" x-text="item.category || 'Sistema Web'"></span>
                                                    <h3 class="text-xs font-bold text-white truncate" x-text="item.title"></h3>
                                                    <p class="text-[11px] text-slate-400">Acesso Vitalício</p>
                                                </div>
                                                <div class="text-right shrink-0">
                                                    <template x-if="parseFloat(item.price) <= 0">
                                                        <span class="rounded bg-emerald-500/10 border border-emerald-500/30 px-2 py-0.5 text-xs font-bold font-mono text-emerald-400">GRÁTIS</span>
                                                    </template>
                                                    <template x-if="parseFloat(item.price) > 0">
                                                        <p class="font-mono text-sm font-bold text-teal-400" x-text="formatMoney(item.price)"></p>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                </div>

                                {{-- Linhas de Preço --}}
                                <div class="mt-5 pt-4 border-t border-slate-800 space-y-2.5 text-xs sm:text-sm">
                                    <div class="flex items-center justify-between text-slate-300">
                                        <span>Subtotal</span>
                                        <span class="font-mono font-semibold" x-text="formatMoney(getSubtotal())"></span>
                                    </div>

                                    {{-- Cupom Aplicado --}}
                                    <div x-show="appliedCoupon" x-cloak class="flex items-center justify-between text-emerald-400">
                                        <span class="flex items-center gap-1.5">
                                            <span>Desconto Cupom (<span x-text="appliedCoupon?.code"></span>)</span>
                                            <button type="button" @click="removeCoupon()" class="text-rose-400 hover:text-rose-300 text-xs font-bold cursor-pointer" title="Remover cupom">×</button>
                                        </span>
                                        <span class="font-mono font-semibold" x-text="'- ' + formatMoney(getDiscount())"></span>
                                    </div>

                                    <div class="flex items-center justify-between text-slate-400 text-xs">
                                        <span>Entrega Digital</span>
                                        <span class="text-emerald-400 font-bold uppercase tracking-wider text-[11px]">Instantânea / Imediata</span>
                                    </div>

                                    <div class="pt-4 border-t border-slate-800 flex items-baseline justify-between">
                                        <div>
                                            <span class="font-display text-base font-bold text-white">Total a Pagar</span>
                                            <p x-show="getTotal() > 0" class="text-[11px] text-slate-400">À vista no Pix ou até 12x no Cartão</p>
                                            <p x-show="getTotal() === 0" x-cloak class="text-[11px] text-emerald-400">100% Gratuito sem cobranças</p>
                                        </div>
                                        <div>
                                            <span x-show="getTotal() > 0" class="font-mono text-2xl font-black text-teal-400" x-text="formatMoney(getTotal())"></span>
                                            <span x-show="getTotal() === 0" x-cloak class="font-display text-2xl font-black text-emerald-400">GRÁTIS</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Campo de Cupom de Desconto --}}
                                <div class="mt-5 pt-5 border-t border-slate-800">
                                    <label for="checkout-coupon" class="block text-xs font-semibold text-slate-300 mb-1.5">Possui cupom promocional?</label>
                                    <div class="flex items-center gap-2">
                                        <input 
                                            id="checkout-coupon"
                                            type="text" 
                                            x-model="couponInput"
                                            placeholder="Ex: VIP10 ou KL2026"
                                            class="w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white placeholder-slate-500 uppercase font-mono focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                            @keydown.enter.prevent="applyCoupon()"
                                        />
                                        <button 
                                            type="button" 
                                            @click="applyCoupon()" 
                                            class="rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 px-3.5 py-2 text-xs font-bold text-white transition shrink-0 cursor-pointer"
                                        >
                                            Aplicar
                                        </button>
                                    </div>
                                    <p x-show="couponError" x-text="couponError" x-cloak class="mt-1.5 text-xs text-rose-400"></p>
                                    <p x-show="couponSuccess" x-text="couponSuccess" x-cloak class="mt-1.5 text-xs text-emerald-400"></p>
                                </div>

                                {{-- Botão Principal de Finalizar Compra --}}
                                <div class="mt-6 pt-6 border-t border-slate-800">
                                    <button 
                                        type="submit" 
                                        :disabled="submitting || items.length === 0" 
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-500 hover:bg-teal-400 px-6 py-4 text-sm font-extrabold text-slate-950 shadow-xl shadow-teal-500/25 transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed group"
                                    >
                                        <span x-show="!submitting" class="flex items-center gap-2">
                                            <span x-show="getTotal() > 0">Finalizar Compra e Pagar</span>
                                            <span x-show="getTotal() === 0" x-cloak>Liberar Download Grátis</span>
                                            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </span>
                                        <span x-show="submitting" x-cloak class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-slate-950" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                            </svg>
                                            <span x-show="getTotal() > 0">Abrindo Mercado Pago...</span>
                                            <span x-show="getTotal() === 0" x-cloak>Liberando seus arquivos...</span>
                                        </span>
                                    </button>

                                    <p class="mt-3 text-[11px] text-slate-400 text-center flex items-center justify-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span>Garantia incondicional de 7 dias com suporte humano</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Badges de Confiança --}}
                            <div class="rounded-2xl border border-slate-800/80 bg-slate-900/40 p-4 space-y-2.5 text-xs text-slate-400">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-teal-400 font-bold">✓</span>
                                    <span>Código fonte completo sem travas de domínio.</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-teal-400 font-bold">✓</span>
                                    <span>Download liberado automaticamente no ato do pagamento.</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-teal-400 font-bold">✓</span>
                                    <span>Suporte via WhatsApp diretamente com especialistas.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Máscaras automáticas de CPF e Telefone em JavaScript nativo --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cpfInput = document.getElementById('cpf');
            const phoneInput = document.getElementById('phone');

            if (cpfInput) {
                const formatCpf = (v) => {
                    v = v.replace(/\D/g, '').slice(0, 11);
                    if (v.length > 9) {
                        return v.replace(/^(\d{3})(\d{3})(\d{3})(\d{1,2})$/, '$1.$2.$3-$4');
                    } else if (v.length > 6) {
                        return v.replace(/^(\d{3})(\d{3})(\d{1,3})$/, '$1.$2.$3');
                    } else if (v.length > 3) {
                        return v.replace(/^(\d{3})(\d{1,3})$/, '$1.$2');
                    }
                    return v;
                };

                cpfInput.addEventListener('input', function (e) {
                    e.target.value = formatCpf(e.target.value);
                });

                if (cpfInput.value) {
                    cpfInput.value = formatCpf(cpfInput.value);
                }
            }

            if (phoneInput) {
                const formatPhone = (v) => {
                    v = v.replace(/\D/g, '').slice(0, 11);
                    if (v.length > 10) {
                        return v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
                    } else if (v.length > 6) {
                        return v.replace(/^(\d{2})(\d{4,5})(\d{0,4})$/, '($1) $2-$3');
                    } else if (v.length > 2) {
                        return v.replace(/^(\d{2})(\d{1,5})$/, '($1) $2');
                    }
                    return v;
                };

                phoneInput.addEventListener('input', function (e) {
                    e.target.value = formatPhone(e.target.value);
                });

                if (phoneInput.value) {
                    phoneInput.value = formatPhone(phoneInput.value);
                }
            }
        });
    </script>
</x-storefront-layout>
