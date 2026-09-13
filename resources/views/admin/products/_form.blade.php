@php($editing = isset($product))

<form 
    method="POST" 
    action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" 
    enctype="multipart/form-data" 
    novalidate 
    class="space-y-8" 
    x-data="{ submitting: false, dirty: false, coverName: '', fileName: '' }" 
    x-on:change="dirty = true" 
    x-on:submit="submitting = true; dirty = false" 
    x-on:beforeunload.window="if (dirty) $event.preventDefault()"
>
    @csrf 
    @if($editing) 
        @method('PUT') 
    @endif

    {{-- SEÇÃO 1: INFORMAÇÕES BÁSICAS --}}
    <div class="space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-900">
                1. Informações Básicas
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Defina o nome de exibição e os detalhes técnicos que aparecerão na vitrine.
            </p>
        </div>

        <div>
            <x-input-label for="title" value="Título do Produto" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <x-text-input 
                id="title" 
                name="title" 
                type="text" 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                placeholder="Ex: Script PHP Sistema SaaS de Assinaturas"
                :value="old('title', $product->title ?? '')" 
                required 
                aria-describedby="title-error" 
                :aria-invalid="$errors->has('title') ? 'true' : 'false'" 
            />
            <x-input-error id="title-error" :messages="$errors->get('title')" class="mt-1.5 text-xs text-red-500" />
        </div>

        <div>
            <x-input-label for="description" value="Descrição Completa" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <textarea 
                id="description" 
                name="description" 
                rows="6" 
                required 
                placeholder="Descreva as funcionalidades, requisitos técnicos, versão e o que está incluso no pacote..."
                aria-describedby="description-error" 
                aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}" 
                class="field w-full rounded-xl border border-slate-300 p-3.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs resize-y leading-relaxed"
            >{{ old('description', $product->description ?? '') }}</textarea>
            <x-input-error id="description-error" :messages="$errors->get('description')" class="mt-1.5 text-xs text-red-500" />
        </div>
    </div>

    {{-- SEÇÃO 2: PREÇO & DISPONIBILIDADE --}}
    <div class="space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-900">
                2. Preço & Disponibilidade
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Configure o valor unitário de venda e se o item estará visível imediatamente.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="price" value="Preço em Reais (BRL)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 font-bold text-slate-400 text-sm">
                        R$
                    </div>
                    <x-text-input 
                        id="price" 
                        name="price" 
                        type="number" 
                        min="0.01" 
                        max="99999999.99" 
                        step="0.01" 
                        placeholder="0,00"
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm font-bold text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                        :value="old('price', $product->price ?? '')" 
                        required 
                        aria-describedby="price-hint price-error" 
                        :aria-invalid="$errors->has('price') ? 'true' : 'false'" 
                    />
                </div>
                <p id="price-hint" class="mt-1 text-[11px] text-slate-500">Valor cobrado no checkout do Mercado Pago.</p>
                <x-input-error id="price-error" :messages="$errors->get('price')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <div>
                <x-input-label value="Status de Publicação" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <label class="flex min-h-[46px] items-center gap-3 rounded-xl border border-slate-300 bg-slate-50/60 hover:bg-slate-50 px-4 py-2.5 cursor-pointer transition select-none">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1" 
                        @checked(old('is_active', $product->is_active ?? true)) 
                        class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500/30"
                    >
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Produto ativo na vitrine</span>
                        <span class="text-[11px] text-slate-500 block">Disponível para busca e compra no catálogo</span>
                    </div>
                </label>
            </div>
        </div>
    </div>

    {{-- SEÇÃO 3: ARQUIVOS & MÍDIAS DIGITAIS --}}
    <div class="space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-900">
                3. Arquivos & Mídias Digitais
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Capa pública para o card da vitrine e arquivo protegido entregue pós-pagamento.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            {{-- Capa --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50/40 p-4">
                <x-input-label for="cover" value="Imagem de Capa (Vitrine)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <input 
                    id="cover" 
                    name="cover" 
                    type="file" 
                    accept="image/jpeg,image/png,image/webp" 
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer" 
                    x-on:change="coverName = $event.target.files[0]?.name || ''" 
                    aria-describedby="cover-hint cover-error" 
                    aria-invalid="{{ $errors->has('cover') ? 'true' : 'false' }}"
                >
                <p id="cover-hint" class="mt-2 text-[11px] text-slate-500" x-text="coverName || 'Formatos: JPG, PNG ou WebP • máx 4 MB'"></p>
                <x-input-error id="cover-error" :messages="$errors->get('cover')" class="mt-1 text-xs text-red-500" />
            </div>

            {{-- Arquivo do Produto --}}
            <div class="rounded-xl border border-teal-200/80 bg-teal-50/20 p-4">
                <div class="flex items-center justify-between mb-1">
                    <x-input-label for="file" value="Arquivo Digital Protegido" class="text-xs font-bold uppercase text-teal-900" />
                    <span class="rounded bg-teal-100/70 px-1.5 py-0.5 text-[10px] font-mono font-bold text-teal-800">Storage Privado</span>
                </div>
                <input 
                    id="file" 
                    name="file" 
                    type="file" 
                    @required(!$editing) 
                    accept=".zip,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" 
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-600 file:text-white hover:file:bg-teal-700 cursor-pointer" 
                    x-on:change="fileName = $event.target.files[0]?.name || ''" 
                    aria-describedby="file-hint file-error" 
                    aria-invalid="{{ $errors->has('file') ? 'true' : 'false' }}"
                >
                <p id="file-hint" class="mt-2 text-[11px] text-slate-500" x-text="fileName || '{{ $editing ? 'Envie somente se desejar substituir • ' : '' }}ZIP, PDF ou Docs • máx 100 MB'"></p>
                <x-input-error id="file-error" :messages="$errors->get('file')" class="mt-1 text-xs text-red-500" />
            </div>
        </div>

        <div class="rounded-xl bg-slate-100/70 border border-slate-200 p-3.5 text-xs text-slate-600 flex items-start gap-2.5">
            <svg class="h-4 w-4 text-teal-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="leading-relaxed">
                <strong>Segurança Garantida:</strong> O arquivo do produto é protegido no disco local privado e liberado apenas após a confirmação do pagamento via assinatura temporária criptografada de 10 minutos.
            </p>
        </div>
    </div>

    {{-- BARRA DE AÇÕES --}}
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-slate-200 pt-6">
        <a 
            href="{{ route('admin.products.index') }}" 
            class="btn-secondary text-xs !min-h-10 text-center"
        >
            Cancelar
        </a>

        <button 
            type="submit" 
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 px-6 py-2.5 text-xs font-bold text-white shadow-sm shadow-teal-500/20 transition transform active:scale-95 disabled:opacity-60" 
            :disabled="submitting"
        >
            <span x-show="!submitting">{{ $editing ? 'Salvar Alterações' : 'Cadastrar Produto' }}</span>
            <span x-show="submitting" x-cloak class="flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Enviando dados...</span>
            </span>
        </button>
    </div>
</form>
