@php($editing = isset($post))

<form 
    method="POST" 
    action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}" 
    enctype="multipart/form-data" 
    novalidate 
    class="space-y-8" 
    x-data="{ 
        submitting: false, 
        dirty: false, 
        coverName: '', 
        previewUrl: '{{ $editing && $post->cover_path ? asset($post->cover_path) : '' }}',
        title: @js(old('title', $post->title ?? '')),
        content: @js(old('content', $post->content ?? '')),
        get wordCount() {
            return this.content.trim() ? this.content.replace(/<[^>]*>/g, ' ').trim().split(/\s+/).length : 0;
        },
        get readTime() {
            return Math.max(1, Math.ceil(this.wordCount / 200));
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

    {{-- SEÇÃO 1: CABEÇALHO & STATUS --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">
                    1. Informações Básicas do Artigo
                </h3>
                <p class="text-xs text-slate-600 mt-0.5 font-medium">
                    Defina o título principal, categoria de conteúdo e visibilidade no blog.
                </p>
            </div>
            
            <label class="relative inline-flex items-center cursor-pointer select-none">
                <input 
                    type="checkbox" 
                    name="is_published" 
                    value="1" 
                    class="sr-only peer"
                    {{ old('is_published', $post->is_published ?? true) ? 'checked' : '' }}
                >
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                <span class="ml-3 text-xs font-semibold text-slate-700">Publicar artigo no site</span>
            </label>
        </div>

        <div>
            <x-input-label for="title" value="Título do Artigo" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <x-text-input 
                id="title" 
                name="title" 
                type="text" 
                x-model="title"
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs font-medium" 
                placeholder="Ex: Como Configurar o Gateway Mercado Pago no Laravel 12" 
                required 
                aria-describedby="title-error" 
                :aria-invalid="$errors->has('title') ? 'true' : 'false'" 
            />
            <x-input-error id="title-error" :messages="$errors->get('title')" class="mt-1.5 text-xs text-red-500" />
        </div>

        <div>
            <x-input-label for="slug" value="Slug / URL" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <x-text-input
                id="slug"
                name="slug"
                type="text"
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs font-mono"
                :value="old('slug', $post->slug ?? '')"
                placeholder="Deixe em branco para gerar automaticamente a partir do título"
            />
            <p class="mt-1 text-xs text-slate-500">
                Alterar este campo muda a URL pública. A URL anterior será redirecionada automaticamente com HTTP 301.
            </p>
            <x-input-error :messages="$errors->get('slug')" class="mt-1.5 text-xs text-red-500" />
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <x-input-label for="blog_category_id" value="Categoria do Blog" class="text-xs font-bold uppercase text-slate-700" />
                    <a 
                        href="{{ route('admin.blog-categories.index') }}" 
                        target="_blank"
                        class="text-[11px] font-semibold text-teal-600 hover:text-teal-700 hover:underline flex items-center gap-1"
                    >
                        <span>Gerenciar Categorias</span>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                <select 
                    id="blog_category_id" 
                    name="blog_category_id" 
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs font-medium bg-white"
                >
                    <option value="">Selecione uma categoria do blog...</option>
                    @if(isset($blogCategories))
                        @foreach($blogCategories as $blogCat)
                            <option 
                                value="{{ $blogCat->id }}" 
                                {{ (old('blog_category_id', $post->blog_category_id ?? '') == $blogCat->id) || (old('category', $post->category ?? '') == $blogCat->name) ? 'selected' : '' }}
                            >
                                {{ $blogCat->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <x-input-error :messages="$errors->get('blog_category_id')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <div>
                <x-input-label for="excerpt" value="Resumo / Linha Fina (Opcional)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <input 
                    id="excerpt" 
                    name="excerpt" 
                    type="text" 
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs font-medium" 
                    placeholder="Texto curto exibido nos cards da listagem (se vazio, gerado auto)"
                    value="{{ old('excerpt', $post->excerpt ?? '') }}" 
                />
                <x-input-error :messages="$errors->get('excerpt')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>
    </div>

    {{-- SEÇÃO 2: IMAGEM DE CAPA --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-display text-base font-bold text-slate-900">
                2. Imagem de Capa
            </h3>
            <p class="text-xs text-slate-600 mt-0.5 font-medium">
                Utilize imagens em alta resolução (1200x630 recomendado para compartilhamento social).
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 items-center">
            <div>
                <label for="cover" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Arquivo de Imagem (JPG, PNG ou WEBP até 4MB)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-teal-400 transition bg-slate-50/50">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-xs text-slate-700 justify-center font-medium">
                            <label for="cover" class="relative cursor-pointer rounded-md font-semibold text-teal-600 hover:text-teal-500 focus-within:outline-hidden">
                                <span>Selecionar imagem</span>
                                <input 
                                    id="cover" 
                                    name="cover" 
                                    type="file" 
                                    accept="image/png,image/jpeg,image/webp" 
                                    class="sr-only"
                                    x-on:change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            coverName = file.name;
                                            previewUrl = URL.createObjectURL(file);
                                        }
                                    "
                                >
                            </label>
                        </div>
                        <p class="text-[11px] text-slate-600 font-medium" x-text="coverName || 'PNG, JPG ou WEBP até 4MB'"></p>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('cover')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <div>
                <span class="block text-xs font-bold uppercase text-slate-700 mb-1">Prévia da Capa</span>
                <div class="h-44 w-full rounded-xl border border-slate-200 bg-slate-900 overflow-hidden relative flex items-center justify-center">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" alt="Capa" class="h-full w-full object-cover">
                    </template>
                    <template x-if="!previewUrl">
                        <div class="text-center p-4">
                            <svg class="h-8 w-8 text-slate-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs text-slate-400">Nenhuma imagem carregada</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- SEÇÃO 3: CORPO DO ARTIGO --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">
                    3. Conteúdo Completo
                </h3>
                <p class="text-xs text-slate-600 mt-0.5 font-medium">
                    Estruture o texto usando HTML padrão (`<h2>`, `<p>`, `<ul>`, `<code>`, `<blockquote>`).
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs font-mono text-slate-600 font-semibold">
                <span class="rounded bg-slate-100 px-2.5 py-1 border border-slate-200/80"><span x-text="wordCount">0</span> palavras</span>
                <span class="rounded bg-slate-100 px-2.5 py-1 border border-slate-200/80">~<span x-text="readTime">1</span> min leitura</span>
            </div>
        </div>

        <div>
            <div class="mb-2 flex flex-wrap gap-2 text-xs">
                <span class="font-semibold text-slate-700">Tags Rápidas:</span>
                <button type="button" @click="content += '\n<h2>Subtítulo aqui</h2>\n'" class="rounded bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2 py-0.5 text-slate-700 font-mono text-[11px] font-semibold">&lt;h2&gt;</button>
                <button type="button" @click="content += '\n<p>Parágrafo explicativo...</p>\n'" class="rounded bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2 py-0.5 text-slate-700 font-mono text-[11px] font-semibold">&lt;p&gt;</button>
                <button type="button" @click="content += '\n<ul class=\'list-disc pl-5 space-y-1\'>\n  <li>Item 1</li>\n  <li>Item 2</li>\n</ul>\n'" class="rounded bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2 py-0.5 text-slate-700 font-mono text-[11px] font-semibold">&lt;ul&gt;</button>
                <button type="button" @click="content += '\n<div class=\'bg-slate-900 text-teal-300 p-4 rounded-xl font-mono text-xs overflow-x-auto\'>\n// seu código aqui\n</div>\n'" class="rounded bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2 py-0.5 text-slate-700 font-mono text-[11px] font-semibold">&lt;code box&gt;</button>
                <button type="button" @click="content += '\n<blockquote class=\'border-l-4 border-teal-500 pl-4 italic text-slate-700 my-4\'>\nCitação importante...\n</blockquote>\n'" class="rounded bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2 py-0.5 text-slate-700 font-mono text-[11px] font-semibold">&lt;quote&gt;</button>
            </div>

            <textarea 
                id="content" 
                name="content" 
                rows="16" 
                x-model="content"
                class="w-full font-mono text-xs sm:text-sm leading-relaxed rounded-xl border border-slate-300 p-4 text-slate-900 placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                placeholder="Insira aqui o corpo do artigo com formatação HTML..."
                required
            ></textarea>
            <x-input-error :messages="$errors->get('content')" class="mt-1.5 text-xs text-red-500" />
        </div>
    </div>

    {{-- SEÇÃO 4: SEO & APRESENTAÇÃO EDITORIAL --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6" x-data="{
        seoTitle: @js(old('seo_title', $post->seo_title ?? '')),
        seoDescription: @js(old('meta_description', $post->meta_description ?? '')),
        titleVal: @js(old('title', $post->title ?? '')),
        excerptVal: @js(old('excerpt', $post->excerpt ?? ''))
    }">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-display text-base font-bold text-slate-900">
                4. SEO & Apresentação no Google
            </h3>
            <p class="text-xs text-slate-600 mt-0.5 font-medium">
                Personalize os títulos e snippets de busca para maximizar o CTR orgânico do artigo no Google.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <div class="flex justify-between items-center mb-1">
                    <x-input-label for="seo_title" value="Título SEO (opcional)" class="text-xs font-bold uppercase text-slate-700" />
                    <span class="text-[11px] text-slate-600 font-mono" x-text="(seoTitle ? seoTitle.length : 0) + ' / 60'"></span>
                </div>
                <x-text-input 
                    id="seo_title" 
                    name="seo_title" 
                    type="text" 
                    x-model="seoTitle"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-500 shadow-2xs font-medium" 
                    placeholder="Se vazio, o título do artigo será utilizado" 
                />
                <x-input-error :messages="$errors->get('seo_title')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <x-input-label for="meta_description" value="Meta Description (opcional)" class="text-xs font-bold uppercase text-slate-700" />
                    <span class="text-[11px] text-slate-600 font-mono" x-text="(seoDescription ? seoDescription.length : 0) + ' / 160'"></span>
                </div>
                <textarea 
                    id="meta_description" 
                    name="meta_description" 
                    rows="2" 
                    x-model="seoDescription"
                    class="w-full rounded-xl border border-slate-300 p-2.5 text-sm text-slate-900 placeholder-slate-500 shadow-2xs font-medium" 
                    placeholder="Se vazio, o resumo ou início do texto será utilizado"
                ></textarea>
                <x-input-error :messages="$errors->get('meta_description')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>

        {{-- Prévia no Google (SERP) --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Prévia no Google</span>
            <p class="text-xs text-emerald-700 font-mono truncate">{{ url('/blog/' . ($post->slug ?? 'slug-do-artigo')) }}</p>
            <h5 class="text-base text-blue-700 font-medium hover:underline truncate" x-text="seoTitle || (titleVal ? titleVal + ' - Blog | KL Tecnologia' : 'Título do Artigo - Blog | KL Tecnologia')"></h5>
            <p class="text-xs text-slate-600 line-clamp-2" x-text="seoDescription || excerptVal || 'Leia o artigo completo no blog da KL Tecnologia com dicas práticas sobre tecnologia e desenvolvimento.'"></p>
        </div>

        @if($editing)
            <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-4 flex items-center justify-between text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-800">Slug da URL:</span>
                    <code class="rounded bg-white px-2 py-0.5 border border-slate-200 font-mono text-teal-700 font-semibold">{{ $post->slug }}</code>
                </div>
                <div class="flex items-center gap-2 font-mono">
                    <span>Publicado em: {{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Rascunho' }}</span>
                </div>
            </div>
        @endif
    </div>

    {{-- BOTÕES DE AÇÃO --}}
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-200">
        <a 
            href="{{ route('admin.posts.index') }}" 
            class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cancelar e Voltar
        </a>

        <button 
            type="submit" 
            class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-500 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-teal-600/30 transition disabled:opacity-50"
            :disabled="submitting"
        >
            <template x-if="submitting">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <span>{{ $editing ? 'Salvar Alterações' : 'Publicar Artigo' }}</span>
        </button>
    </div>
</form>
