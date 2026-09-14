@php($editing = isset($product))

<form 
    method="POST" 
    action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" 
    enctype="multipart/form-data" 
    novalidate 
    class="space-y-8" 
    x-ref="form"
    x-data="productFormHandler()" 
    x-on:change="dirty = true" 
    x-on:submit="submitForm($event)" 
    x-on:beforeunload.window="if (dirty && !submitting) $event.preventDefault()"
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

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <x-input-label for="category_id" value="Categoria do Produto" class="text-xs font-bold uppercase text-slate-700" />
                    <a href="{{ route('admin.categories.index') }}" target="_blank" class="text-[11px] font-semibold text-teal-600 hover:text-teal-700 hover:underline">
                        Gerenciar Categorias &rarr;
                    </a>
                </div>

                @php($registeredCategories = \App\Models\Category::active()->orderBy('name')->get())
                <div class="space-y-1">
                    <select 
                        id="category_id" 
                        name="category_id" 
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs"
                        x-on:change="
                            const sel = $event.target.options[$event.target.selectedIndex];
                            if (sel && sel.value) {
                                $refs.catInput.value = sel.text.trim();
                            }
                        "
                    >
                        <option value="">-- Selecione uma Categoria Cadastrada --</option>
                        @foreach($registeredCategories as $cat)
                            <option 
                                value="{{ $cat->id }}" 
                                {{ (string) old('category_id', $product->category_id ?? '') === (string) $cat->id || (!old('category_id') && ($product->category ?? '') === $cat->name) ? 'selected' : '' }}
                            >
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <input 
                        type="hidden" 
                        id="category" 
                        name="category" 
                        x-ref="catInput" 
                        value="{{ old('category', $product->category ?? 'Scripts & SaaS') }}" 
                    />
                </div>
                <x-input-error id="category_id-error" :messages="$errors->get('category_id')" class="mt-1.5 text-xs text-red-500" />
                <x-input-error id="category-error" :messages="$errors->get('category')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <div>
                <x-input-label for="version" value="Versão do Sistema" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <x-text-input 
                    id="version" 
                    name="version" 
                    type="text" 
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                    placeholder="Ex: 1.0 ou 2.1.0"
                    :value="old('version', $product->version ?? '1.0')" 
                />
                <x-input-error id="version-error" :messages="$errors->get('version')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="description" value="Descrição Completa" class="text-xs font-bold uppercase text-slate-700" />
                <span class="text-[11px] text-teal-700 font-medium">Suporta Markdown & Listas</span>
            </div>
            <textarea 
                id="description" 
                name="description" 
                rows="7" 
                required 
                placeholder="Descreva as funcionalidades, requisitos técnicos, versão e o que está incluso no pacote..."
                aria-describedby="description-hint description-error" 
                aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}" 
                class="field w-full rounded-xl border border-slate-300 p-3.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs resize-y leading-relaxed"
            >{{ old('description', $product->description ?? '') }}</textarea>
            <p id="description-hint" class="mt-1 text-[11px] text-slate-500">
                Dica de formatação: Linhas iniciadas com <code class="text-teal-700 font-mono font-semibold">###</code> viram títulos de seção e linhas com <code class="text-teal-700 font-mono font-semibold">•</code> ou <code class="text-teal-700 font-mono font-semibold">-</code> viram tópicos destacados com checkmark.
            </p>
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

        <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-3">
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
                        min="0.00" 
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
                <p id="price-hint" class="mt-1 text-[11px] text-slate-500">Valor cobrado no checkout (R$ 0,00 = Grátis).</p>
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

            <div>
                <x-input-label value="Destaque na Loja" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <label class="flex min-h-[46px] items-center gap-3 rounded-xl border border-amber-200 bg-amber-50/40 hover:bg-amber-50/80 px-4 py-2.5 cursor-pointer transition select-none">
                    <input 
                        type="checkbox" 
                        name="is_featured" 
                        value="1" 
                        @checked(old('is_featured', $product->is_featured ?? false)) 
                        class="h-4 w-4 rounded border-amber-300 text-amber-500 focus:ring-amber-500/30"
                    >
                    <div>
                        <span class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                            <span>Colocar em Destaque</span>
                            <span class="rounded bg-amber-100 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 border border-amber-300">★ HOT</span>
                        </span>
                        <span class="text-[11px] text-amber-700/80 block">Aparece na seção "Produtos em Destaque" da Home</span>
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
                    x-on:change="onCoverChange($event)" 
                    aria-describedby="cover-hint cover-error" 
                    aria-invalid="{{ $errors->has('cover') ? 'true' : 'false' }}"
                >
                <p id="cover-hint" class="mt-2 text-[11px] text-slate-500" x-text="coverName || 'Formatos: JPG, PNG ou WebP • máx 4 MB'"></p>
                <div x-show="coverError" x-cloak class="mt-2 text-xs font-semibold text-red-500" x-text="coverError"></div>
                <x-input-error id="cover-error" :messages="$errors->get('cover')" class="mt-1 text-xs text-red-500" />
            </div>

            {{-- Arquivo do Produto --}}
            <div class="rounded-xl border border-teal-200/80 bg-teal-50/20 p-4">
                <div class="flex items-center justify-between mb-2">
                    <x-input-label for="file" value="Arquivo Digital Protegido" class="text-xs font-bold uppercase text-teal-900" />
                    <span class="rounded bg-teal-100/70 px-1.5 py-0.5 text-[10px] font-mono font-bold text-teal-800">Storage Privado</span>
                </div>

                @if($editing && empty($product->file_path))
                    <div class="mb-3 rounded-lg border border-amber-300 bg-amber-50/90 p-2.5 text-xs text-amber-800 flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span><strong>Pendente de upload:</strong> Selecione o arquivo (.zip) abaixo e salve para disponibilizá-lo aos compradores.</span>
                    </div>
                @elseif($editing && !empty($product->file_path))
                    <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50/80 p-2 text-xs text-emerald-800 flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Arquivo ativo no storage privado. Envie outro se desejar substituir.</span>
                    </div>
                @endif
                <input 
                    id="file" 
                    name="file" 
                    type="file" 
                    @required(!$editing) 
                    accept=".zip,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.rar,.7z,.tar,.gz" 
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-600 file:text-white hover:file:bg-teal-700 cursor-pointer" 
                    x-on:change="onFileChange($event)" 
                    aria-describedby="file-hint file-error" 
                    aria-invalid="{{ $errors->has('file') ? 'true' : 'false' }}"
                >
                <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                    <span x-text="fileName ? 'Arquivo selecionado: ' + fileName : '{{ $editing ? 'Envie somente se desejar substituir • ' : '' }}ZIP, PDF, RAR ou Docs • máx 512 MB'"></span>
                    <span x-show="fileSizeFormatted" class="font-bold text-teal-700" x-text="fileSizeFormatted"></span>
                </div>

                {{-- Alerta Instantâneo: Arquivo excede 512MB --}}
                <div x-show="fileError" x-cloak class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-xs text-red-800 flex items-start gap-2">
                    <svg class="h-4 w-4 shrink-0 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong class="block font-semibold">Tamanho Excedido:</strong>
                        <span x-text="fileError"></span>
                    </div>
                </div>

                {{-- Alerta Instantâneo: Aviso de arquivo sem extensão --}}
                <div x-show="fileWarning" x-cloak class="mt-3 rounded-xl border border-amber-300 bg-amber-50 p-3 text-xs text-amber-900 flex items-start gap-2">
                    <svg class="h-4 w-4 shrink-0 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <strong class="block font-semibold">Aviso de formato de arquivo:</strong>
                        <span x-text="fileWarning"></span>
                    </div>
                </div>

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

    {{-- CARD DE PROGRESSO DE UPLOAD EM TEMPO REAL --}}
    <div x-show="submitting && uploadProgress > 0" x-cloak class="rounded-2xl border border-teal-200 bg-gradient-to-br from-teal-50/90 to-emerald-50/70 p-5 shadow-sm space-y-3 transition">
        <div class="flex items-center justify-between text-xs font-bold text-teal-950">
            <span class="flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin text-teal-600 shrink-0" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="uploadStatus"></span>
            </span>
            <span class="font-mono text-sm font-black text-teal-700" x-text="uploadProgress + '%'"></span>
        </div>

        {{-- Barra de Progresso --}}
        <div class="h-3 w-full overflow-hidden rounded-full bg-slate-200/90 p-0.5">
            <div 
                class="h-full rounded-full bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-600 transition-all duration-200 ease-out shadow-xs" 
                :style="'width: ' + uploadProgress + '%'"
            ></div>
        </div>

        <div class="flex items-center justify-between text-[11px] text-slate-600">
            <span>Enviado: <strong class="text-slate-800" x-text="uploadedMb"></strong> de <strong class="text-slate-800" x-text="totalMb"></strong></span>
            <span x-show="uploadProgress < 100" class="text-teal-700 font-medium">Não feche esta página até o término</span>
            <span x-show="uploadProgress >= 100" class="text-emerald-700 font-bold">Processando no servidor...</span>
        </div>
    </div>

    {{-- MENSAGEM DE ERRO DO SERVIDOR / INTERCEPTADA --}}
    <div x-show="formError" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs text-red-900 flex items-start gap-3 shadow-xs">
        <svg class="h-5 w-5 shrink-0 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="space-y-1 leading-relaxed" x-html="formError"></div>
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
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 px-6 py-2.5 text-xs font-bold text-white shadow-sm shadow-teal-500/20 transition transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" 
            :disabled="submitting || !!fileError || !!coverError"
        >
            <span x-show="!submitting">{{ $editing ? 'Salvar Alterações' : 'Cadastrar Produto' }}</span>
            <span x-show="submitting" x-cloak class="flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="uploadProgress > 0 ? (uploadProgress + '%') : 'Enviando...'"></span>
            </span>
        </button>
    </div>
</form>

<script>
function productFormHandler() {
    return {
        submitting: false,
        dirty: false,
        coverName: '',
        coverError: '',
        fileName: '',
        fileSize: 0,
        fileSizeFormatted: '',
        fileError: '',
        fileWarning: '',
        uploadProgress: 0,
        uploadedMb: '0 MB',
        totalMb: '0 MB',
        uploadStatus: '',
        formError: '',

        onCoverChange(event) {
            this.dirty = true;
            this.coverError = '';
            const file = event.target.files[0];
            if (!file) {
                this.coverName = '';
                return;
            }
            this.coverName = file.name;
            if (file.size > 4 * 1024 * 1024) {
                this.coverError = 'A imagem de capa não pode ultrapassar 4 MB.';
            }
        },

        onFileChange(event) {
            this.dirty = true;
            this.fileError = '';
            this.fileWarning = '';
            this.formError = '';
            const file = event.target.files[0];
            if (!file) {
                this.fileName = '';
                this.fileSize = 0;
                this.fileSizeFormatted = '';
                return;
            }

            this.fileName = file.name;
            this.fileSize = file.size;
            const sizeInMb = file.size / (1024 * 1024);
            this.fileSizeFormatted = sizeInMb < 1 
                ? (file.size / 1024).toFixed(1) + ' KB' 
                : sizeInMb.toFixed(1) + ' MB';

            // 1. Limite de tamanho máximo: 512 MB
            if (sizeInMb > 512) {
                this.fileError = `O arquivo selecionado possui ${this.fileSizeFormatted} e excede o limite máximo permitido de 512 MB. Compacte ou divida o arquivo antes de enviar.`;
                return;
            }

            // 2. Validação da extensão
            const parts = file.name.split('.');
            const hasExtension = parts.length > 1;
            const ext = hasExtension ? parts.pop().toLowerCase() : '';
            const allowed = ['zip', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'rar', '7z', 'tar', 'gz'];

            if (!hasExtension || !ext) {
                this.fileWarning = 'Atenção: O arquivo parece estar sem extensão (ex: .zip). Se for um arquivo baixado do Google Drive, adicione ".zip" ao nome do arquivo antes de enviar.';
            } else if (!allowed.includes(ext)) {
                this.fileError = `A extensão ".${ext}" não é permitida. Envie arquivos nos formatos: ${allowed.join(', ')}.`;
            }
        },

        submitForm(event) {
            event.preventDefault();

            if (this.fileError || this.coverError) {
                return;
            }

            this.formError = '';
            this.submitting = true;
            this.uploadProgress = 0;
            this.uploadedMb = '0 MB';
            this.totalMb = this.fileSizeFormatted || '0 MB';
            this.uploadStatus = 'Iniciando upload...';

            const form = this.$refs.form;
            const formData = new FormData(form);

            const xhr = new XMLHttpRequest();
            xhr.open(form.method, form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    const percent = Math.min(100, Math.round((e.loaded / e.total) * 100));
                    this.uploadProgress = percent;
                    this.uploadedMb = (e.loaded / (1024 * 1024)).toFixed(1) + ' MB';
                    this.totalMb = (e.total / (1024 * 1024)).toFixed(1) + ' MB';
                    if (percent < 100) {
                        this.uploadStatus = `Enviando arquivo: ${this.uploadedMb} de ${this.totalMb}`;
                    } else {
                        this.uploadStatus = 'Upload concluído! Gravando no storage do servidor...';
                    }
                }
            };

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    this.dirty = false;
                    this.uploadProgress = 100;
                    this.uploadStatus = 'Produto salvo com sucesso! Redirecionando...';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                            return;
                        }
                    } catch (err) {}
                    window.location.href = '{{ route('admin.products.index') }}';
                } else if (xhr.status === 413) {
                    this.submitting = false;
                    this.uploadProgress = 0;
                    this.formError = `<strong>⛔ Erro 413 (Request Entity Too Large):</strong> O servidor Nginx rejeitou a transmissão do arquivo (${this.totalMb}).<br><span class="text-xs">Para corrigir no servidor: certifique-se de que a diretiva <code>client_max_body_size 512M;</code> está inserida no Vhost do Nginx.</span>`;
                } else if (xhr.status === 422) {
                    this.submitting = false;
                    this.uploadProgress = 0;
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.errors) {
                            const messages = Object.values(response.errors).flat().join('<br>• ');
                            this.formError = `<strong>Erros de Validação:</strong><br>• ${messages}`;
                            return;
                        }
                    } catch (err) {}
                    this.formError = 'Verifique os dados preenchidos no formulário.';
                } else if (xhr.status === 419) {
                    this.submitting = false;
                    this.uploadProgress = 0;
                    this.formError = 'Sua sessão expirou (Erro 419). Por favor, recarregue a página e tente novamente.';
                } else {
                    this.submitting = false;
                    this.uploadProgress = 0;
                    this.formError = `Ocorreu um erro no servidor (Status: ${xhr.status}). Não foi possível concluir o salvamento.`;
                }
            };

            xhr.onerror = () => {
                this.submitting = false;
                this.uploadProgress = 0;
                this.formError = '<strong>⛔ Falha de Conexão:</strong> O envio foi cancelado ou interrompido pelo servidor (o Nginx pode ter encerrado a conexão por limite de tamanho <code>client_max_body_size</code>).';
            };

            xhr.send(formData);
        }
    };
}
</script>
