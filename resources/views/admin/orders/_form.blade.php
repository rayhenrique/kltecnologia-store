@php($editing = isset($order) && $order->exists)

<form 
    method="POST" 
    action="{{ $editing ? route('admin.orders.update', $order) : route('admin.orders.store') }}" 
    novalidate 
    class="space-y-6" 
    x-data="{ 
        submitting: false, 
        dirty: false,
        amount: @js(old('amount', $order->amount ? number_format((float)$order->amount, 2, '.', '') : '')),
        productId: @js(old('product_id', $order->product_id ?? '')),
        status: @js(old('status', $order->status instanceof \BackedEnum ? $order->status->value : ($order->status ?? 'pending'))),
        paymentMethod: @js(old('payment_method', $order->payment_method ?? 'Pix')),
        onProductChange(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const price = selectedOption?.getAttribute('data-price');
            if (price && (!this.amount || this.amount === '0' || this.amount === '0.00')) {
                this.amount = parseFloat(price).toFixed(2);
            }
        }
    }" 
    x-on:change="dirty = true" 
    x-on:submit="submitting = true; dirty = false" 
    x-on:beforeunload.window="if (dirty) $event.preventDefault()"
>
    @csrf 
    @if($editing) 
        @method('PUT') 
    @endif

    {{-- CARD PRINCIPAL: CLIENTE E PRODUTO --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-display text-base font-bold text-slate-900">
                Identificação da Transação
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Vincule o comprador e o produto digital correspondente para liberação de licença.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            {{-- Cliente --}}
            <div>
                <x-input-label for="user_id" value="Cliente Comprador *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <select 
                    id="user_id" 
                    name="user_id" 
                    required 
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs bg-white"
                >
                    <option value="">Selecione um cliente cadastrado...</option>
                    @foreach($users as $user)
                        <option 
                            value="{{ $user->id }}" 
                            {{ old('user_id', $order->user_id ?? '') == $user->id ? 'selected' : '' }}
                        >
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-[11px] text-slate-400">O cliente precisa estar cadastrado no sistema.</p>
                <x-input-error :messages="$errors->get('user_id')" class="mt-1.5 text-xs text-red-500" />
            </div>

            {{-- Produto Adquirido --}}
            <div>
                <x-input-label for="product_id" value="Produto Digital *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <select 
                    id="product_id" 
                    name="product_id" 
                    required 
                    x-model="productId"
                    @change="onProductChange($event)"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs bg-white"
                >
                    <option value="">Selecione o produto...</option>
                    @foreach($products as $prod)
                        <option 
                            value="{{ $prod->id }}" 
                            data-price="{{ $prod->price }}"
                            {{ old('product_id', $order->product_id ?? '') == $prod->id ? 'selected' : '' }}
                        >
                            {{ $prod->title }} — R$ {{ number_format((float)$prod->price, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-[11px] text-slate-400">Ao selecionar, o valor sugerido será preenchido automaticamente.</p>
                <x-input-error :messages="$errors->get('product_id')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>
    </div>

    {{-- CARD FINANCEIRO E STATUS --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-display text-base font-bold text-slate-900">
                Informações Financeiras & Status
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Defina o valor cobrado, o método de pagamento e a situação atual do pedido.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Valor Total --}}
            <div>
                <x-input-label for="amount" value="Valor do Pedido (R$) *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <span class="text-slate-400 text-sm font-semibold">R$</span>
                    </div>
                    <input 
                        id="amount" 
                        name="amount" 
                        type="text" 
                        x-model="amount"
                        required 
                        placeholder="0.00" 
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                    />
                </div>
                <x-input-error :messages="$errors->get('amount')" class="mt-1.5 text-xs text-red-500" />
            </div>

            {{-- Status --}}
            <div>
                <x-input-label for="status" value="Status do Pedido *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <select 
                    id="status" 
                    name="status" 
                    required 
                    x-model="status"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-semibold text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs bg-white"
                >
                    <option value="pending">⏳ Pendente (Aguardando Pagamento)</option>
                    <option value="paid">✅ Pago (Aprovado / Download Liberado)</option>
                    <option value="canceled">❌ Cancelado</option>
                    <option value="failed">⚠️ Falhou</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1.5 text-xs text-red-500" />
            </div>

            {{-- Método de Pagamento --}}
            <div>
                <x-input-label for="payment_method" value="Método de Pagamento" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <input 
                    id="payment_method" 
                    name="payment_method" 
                    type="text" 
                    x-model="paymentMethod"
                    placeholder="Ex: Pix, Cartão de Crédito, Mercado Pago" 
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
                />
                <div class="mt-1.5 flex flex-wrap items-center gap-1.5 text-[11px] text-slate-500">
                    <span class="font-bold">Sugestões:</span>
                    <button type="button" @click="paymentMethod = 'Pix'" class="text-teal-700 font-semibold hover:underline">Pix</button>
                    <span>&bull;</span>
                    <button type="button" @click="paymentMethod = 'Cartão de Crédito'" class="text-teal-700 font-semibold hover:underline">Cartão</button>
                    <span>&bull;</span>
                    <button type="button" @click="paymentMethod = 'Mercado Pago'" class="text-teal-700 font-semibold hover:underline">Mercado Pago</button>
                    <span>&bull;</span>
                    <button type="button" @click="paymentMethod = 'Boleto'" class="text-teal-700 font-semibold hover:underline">Boleto</button>
                </div>
                <x-input-error :messages="$errors->get('payment_method')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>

        {{-- Referência do Gateway / Transação --}}
        <div>
            <x-input-label for="gateway_reference" value="Código de Transação / Referência Gateway (Opcional)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <input 
                id="gateway_reference" 
                name="gateway_reference" 
                type="text" 
                value="{{ old('gateway_reference', $order->gateway_reference ?? '') }}"
                placeholder="Ex: MP-1092837465, PIX-MANUAL-2026, ou deixe em branco" 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-mono text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
            />
            <p class="mt-1 text-[11px] text-slate-400">ID externo retornado pelo Mercado Pago ou código do comprovante Pix.</p>
            <x-input-error :messages="$errors->get('gateway_reference')" class="mt-1.5 text-xs text-red-500" />
        </div>

        {{-- Banner de aviso sobre entrega digital --}}
        <div class="rounded-xl border border-teal-200 bg-teal-50/70 p-4 flex items-start gap-3">
            <svg class="h-5 w-5 text-teal-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-xs text-slate-700">
                <span class="font-bold text-teal-900">Acesso aos Arquivos Digitais:</span>
                Ao definir ou atualizar o status para <span class="font-semibold text-emerald-700 bg-emerald-100 px-1 py-0.5 rounded">Pago</span>, o cliente vinculado receberá acesso imediato para efetuar o download seguro do produto digital no painel do cliente.
            </div>
        </div>
    </div>

    {{-- BOTÕES DE AÇÃO --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <a 
            href="{{ $editing ? route('admin.orders.show', $order) : route('admin.orders.index') }}" 
            class="btn-secondary !min-h-11 !px-5"
        >
            Cancelar
        </a>
        <button 
            type="submit" 
            :disabled="submitting" 
            class="btn-primary !bg-teal-600 hover:!bg-teal-500 !min-h-11 !px-6 flex items-center gap-2 shadow-sm shadow-teal-600/20"
        >
            <svg x-show="submitting" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ $editing ? 'Salvar Alterações do Pedido' : 'Registrar Pedido' }}</span>
        </button>
    </div>
</form>
