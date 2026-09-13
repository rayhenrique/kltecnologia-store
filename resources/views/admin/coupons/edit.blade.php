<x-admin-layout title="Editar Cupom — {{ $coupon->code }}">
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1" aria-label="Breadcrumb">
                    <a href="{{ route('admin.coupons.index') }}" class="hover:text-teal-600 transition">Cupons</a>
                    <span>/</span>
                    <span class="text-slate-900 font-bold">Editar {{ $coupon->code }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="font-display text-2xl font-bold tracking-tight text-slate-900">
                        Editar Cupom
                    </h1>
                    <span class="rounded-lg bg-slate-900 px-2.5 py-0.5 font-mono text-xs font-bold text-teal-400 border border-slate-800">
                        {{ $coupon->code }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Atualize as regras, vigência e percentuais de desconto deste cupom.
                </p>
            </div>

            <a href="{{ route('admin.coupons.index') }}" class="btn-secondary text-xs !min-h-9 !px-3">
                &larr; Voltar
            </a>
        </div>

        {{-- Alerta de Estatísticas de Utilização --}}
        <div class="rounded-2xl border border-teal-200 bg-teal-50/70 p-4 text-teal-950 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs shadow-xs">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-teal-600 text-white font-bold">
                    ★
                </div>
                <div>
                    <span class="font-bold block">Histórico de Utilizações</span>
                    <span class="text-slate-600 block">Este cupom já foi aplicado com sucesso em <strong>{{ $coupon->times_used }}</strong> {{ $coupon->times_used === 1 ? 'pedido' : 'pedidos' }}.</span>
                </div>
            </div>

            <div class="flex items-center gap-2 font-mono text-xs">
                <span class="text-slate-500">Limite:</span>
                <span class="font-bold text-slate-900">{{ $coupon->max_uses ? $coupon->max_uses : 'Ilimitado' }}</span>
            </div>
        </div>

        {{-- Formulário --}}
        <form 
            action="{{ route('admin.coupons.update', $coupon) }}" 
            method="POST" 
            x-data="{
                discountType: '{{ old('discount_type', $coupon->discount_type) }}',
                scope: '{{ old('product_id', $coupon->product_id) ? 'specific' : 'storewide' }}'
            }"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- Bloco 1: Informações Básicas e Desconto --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs space-y-5">
                <h2 class="font-display text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-50 text-teal-700 text-xs font-bold">1</span>
                    Código & Tipo de Desconto
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Código --}}
                    <div>
                        <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Código do Cupom <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            id="code" 
                            type="text" 
                            name="code" 
                            value="{{ old('code', $coupon->code) }}" 
                            required 
                            placeholder="EX: PROMO15, BLACKFRIDAY" 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm font-mono uppercase font-bold text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20"
                            style="text-transform: uppercase;"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">O cliente digitará este código no checkout.</p>
                        <x-input-error :messages="$errors->get('code')" class="mt-1 text-xs text-rose-500" />
                    </div>

                    {{-- Descrição --}}
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Descrição Interna (Opcional)
                        </label>
                        <input 
                            id="description" 
                            type="text" 
                            name="description" 
                            value="{{ old('description', $coupon->description) }}" 
                            placeholder="Ex: Campanha de Lançamento de Sistemas Web" 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20"
                        />
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-xs text-rose-500" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    {{-- Tipo de Desconto --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Tipo de Desconto <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label 
                                class="flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-bold select-none"
                                :class="discountType === 'percentage' ? 'border-teal-500 bg-teal-50 text-teal-800 ring-2 ring-teal-500/20' : 'border-slate-200 hover:bg-slate-50 text-slate-600'"
                            >
                                <input type="radio" name="discount_type" value="percentage" x-model="discountType" class="sr-only">
                                <span>% Percentual</span>
                            </label>

                            <label 
                                class="flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-bold select-none"
                                :class="discountType === 'fixed' ? 'border-teal-500 bg-teal-50 text-teal-800 ring-2 ring-teal-500/20' : 'border-slate-200 hover:bg-slate-50 text-slate-600'"
                            >
                                <input type="radio" name="discount_type" value="fixed" x-model="discountType" class="sr-only">
                                <span>R$ Valor Fixo</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('discount_type')" class="mt-1 text-xs text-rose-500" />
                    </div>

                    {{-- Valor do Desconto --}}
                    <div>
                        <label for="discount_value" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Valor do Desconto <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span 
                                class="pointer-events-none absolute left-3.5 top-2.5 text-sm font-bold text-slate-400"
                                x-text="discountType === 'percentage' ? '%' : 'R$'"
                            ></span>
                            <input 
                                id="discount_value" 
                                type="number" 
                                step="0.01" 
                                min="0.01" 
                                :max="discountType === 'percentage' ? '100' : ''"
                                name="discount_value" 
                                value="{{ old('discount_value', $coupon->discount_value) }}" 
                                required 
                                placeholder="10.00" 
                                class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-11 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 font-mono"
                            />
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1" x-show="discountType === 'percentage'">Exemplo: 15 para 15% de desconto, 100 para 100% grátis.</p>
                        <p class="text-[11px] text-slate-400 mt-1" x-show="discountType === 'fixed'">Exemplo: 50.00 para R$ 50,00 de abatimento.</p>
                        <x-input-error :messages="$errors->get('discount_value')" class="mt-1 text-xs text-rose-500" />
                    </div>
                </div>
            </div>

            {{-- Bloco 2: Escopo & Regras de Aplicação --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs space-y-5">
                <h2 class="font-display text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-50 text-teal-700 text-xs font-bold">2</span>
                    Escopo de Aplicação & Regras
                </h2>

                {{-- Escopo --}}
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Onde o cupom pode ser aplicado?
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label 
                            class="flex items-start gap-3 p-4 rounded-xl border cursor-pointer transition select-none"
                            :class="scope === 'storewide' ? 'border-teal-500 bg-teal-50/60 ring-2 ring-teal-500/20' : 'border-slate-200 hover:bg-slate-50'"
                        >
                            <input type="radio" name="scope_radio" value="storewide" x-model="scope" @change="document.getElementById('product_id').value = ''" class="mt-0.5 text-teal-600 focus:ring-teal-500">
                            <div>
                                <span class="font-bold text-xs text-slate-900 block">Toda a Loja (Geral)</span>
                                <span class="text-[11px] text-slate-500 mt-0.5 block">Válido para qualquer produto ou combinação no carrinho.</span>
                            </div>
                        </label>

                        <label 
                            class="flex items-start gap-3 p-4 rounded-xl border cursor-pointer transition select-none"
                            :class="scope === 'specific' ? 'border-teal-500 bg-teal-50/60 ring-2 ring-teal-500/20' : 'border-slate-200 hover:bg-slate-50'"
                        >
                            <input type="radio" name="scope_radio" value="specific" x-model="scope" class="mt-0.5 text-teal-600 focus:ring-teal-500">
                            <div>
                                <span class="font-bold text-xs text-slate-900 block">Produto Específico</span>
                                <span class="text-[11px] text-slate-500 mt-0.5 block">Aplica o desconto somente se o produto selecionado estiver no pedido.</span>
                            </div>
                        </label>
                    </div>

                    {{-- Seleção de Produto --}}
                    <div x-show="scope === 'specific'" x-cloak class="pt-2">
                        <label for="product_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Selecione o Produto Elegível <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="product_id" 
                            name="product_id"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20"
                        >
                            <option value="">Selecione um produto...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $coupon->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->title }} (R$ {{ number_format((float) $product->price, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_id')" class="mt-1 text-xs text-rose-500" />
                    </div>
                </div>

                {{-- Valor Mínimo do Pedido & Limite de Usos --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                    <div>
                        <label for="min_order_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Valor Mínimo do Pedido (R$)
                        </label>
                        <input 
                            id="min_order_amount" 
                            type="number" 
                            step="0.01" 
                            min="0" 
                            name="min_order_amount" 
                            value="{{ old('min_order_amount', $coupon->min_order_amount) }}" 
                            placeholder="0.00" 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 font-mono"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">Informe 0,00 caso não haja valor mínimo exigido.</p>
                        <x-input-error :messages="$errors->get('min_order_amount')" class="mt-1 text-xs text-rose-500" />
                    </div>

                    <div>
                        <label for="max_uses" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Limite Máximo de Usos
                        </label>
                        <input 
                            id="max_uses" 
                            type="number" 
                            min="1" 
                            name="max_uses" 
                            value="{{ old('max_uses', $coupon->max_uses) }}" 
                            placeholder="Ilimitado" 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 font-mono"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">Deixe em branco para permitir utilizações ilimitadas.</p>
                        <x-input-error :messages="$errors->get('max_uses')" class="mt-1 text-xs text-rose-500" />
                    </div>
                </div>
            </div>

            {{-- Bloco 3: Período de Vigência & Status --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs space-y-5">
                <h2 class="font-display text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-50 text-teal-700 text-xs font-bold">3</span>
                    Vigência Temporal & Status
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="starts_at" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Data e Hora de Início
                        </label>
                        <input 
                            id="starts_at" 
                            type="datetime-local" 
                            name="starts_at" 
                            value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}" 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">Deixe vazio para começar a valer imediatamente.</p>
                        <x-input-error :messages="$errors->get('starts_at')" class="mt-1 text-xs text-rose-500" />
                    </div>

                    <div>
                        <label for="expires_at" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Data e Hora de Expiração
                        </label>
                        <input 
                            id="expires_at" 
                            type="datetime-local" 
                            name="expires_at" 
                            value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}" 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">Deixe vazio para nunca expirar.</p>
                        <x-input-error :messages="$errors->get('expires_at')" class="mt-1 text-xs text-rose-500" />
                    </div>
                </div>

                {{-- Status Ativo --}}
                <div class="pt-3 border-t border-slate-100 flex items-center gap-3">
                    <input 
                        id="is_active" 
                        type="checkbox" 
                        name="is_active" 
                        value="1" 
                        {{ old('is_active', $coupon->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500"
                    />
                    <label for="is_active" class="text-xs font-semibold text-slate-700 cursor-pointer">
                        Cupom ativo e pronto para uso
                    </label>
                </div>
            </div>

            {{-- Botões de Ação --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.coupons.index') }}" class="btn-secondary text-xs">
                    Cancelar
                </a>
                <button type="submit" class="btn-teal text-xs !px-6 cursor-pointer">
                    Atualizar Cupom
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
