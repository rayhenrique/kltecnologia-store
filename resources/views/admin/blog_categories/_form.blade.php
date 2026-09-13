@php($editing = isset($blogCategory) && $blogCategory->exists)

<form 
    method="POST" 
    action="{{ $editing ? route('admin.blog-categories.update', $blogCategory) : route('admin.blog-categories.store') }}" 
    novalidate 
    class="space-y-6" 
    x-data="{ 
        submitting: false, 
        dirty: false,
        name: @js(old('name', $blogCategory->name ?? '')),
        icon: @js(old('icon', $blogCategory->icon ?? 'sparkles'))
    }" 
    x-on:change="dirty = true" 
    x-on:submit="submitting = true; dirty = false" 
    x-on:beforeunload.window="if (dirty) $event.preventDefault()"
>
    @csrf 
    @if($editing) 
        @method('PUT') 
    @endif

    {{-- CARD DE INFORMAÇÕES DA CATEGORIA DO BLOG --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">
                    Dados da Categoria do Blog
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Categorize seus artigos, tutoriais e comunicados para organizar o feed e os filtros do blog.
                </p>
            </div>

            <label class="relative inline-flex items-center cursor-pointer select-none">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1" 
                    class="sr-only peer"
                    {{ old('is_active', $blogCategory->is_active ?? true) ? 'checked' : '' }}
                >
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                <span class="ml-3 text-xs font-semibold text-slate-700">Categoria Ativa no Blog</span>
            </label>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            {{-- Nome --}}
            <div>
                <x-input-label for="name" value="Nome da Categoria *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <x-text-input 
                    id="name" 
                    name="name" 
                    type="text" 
                    x-model="name"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                    placeholder="Ex: Atualizações, Tutoriais & Guias, Tecnologia" 
                    required 
                    aria-describedby="name-error" 
                    :aria-invalid="$errors->has('name') ? 'true' : 'false'" 
                />
                <x-input-error id="name-error" :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-500" />
            </div>

            {{-- Ícone / Identificador Visual --}}
            <div>
                <x-input-label for="icon" value="Identificador de Ícone (Opcional)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <input 
                    id="icon" 
                    name="icon" 
                    type="text" 
                    x-model="icon"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs font-mono" 
                    placeholder="Ex: sparkles, book-open, code, chart, shield-check, rocket" 
                />
                <div class="mt-1.5 flex flex-wrap items-center gap-1.5 text-xs text-slate-600">
                    <span class="font-bold text-slate-700">Sugestões:</span>
                    <button type="button" @click="icon = 'sparkles'" class="text-teal-700 font-semibold hover:text-teal-900 underline">sparkles</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'book-open'" class="text-teal-700 font-semibold hover:text-teal-900 underline">book-open</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'code'" class="text-teal-700 font-semibold hover:text-teal-900 underline">code</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'chart'" class="text-teal-700 font-semibold hover:text-teal-900 underline">chart</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'shield-check'" class="text-teal-700 font-semibold hover:text-teal-900 underline">shield-check</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'newspaper'" class="text-teal-700 font-semibold hover:text-teal-900 underline">newspaper</button>
                </div>
                <x-input-error :messages="$errors->get('icon')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>

        {{-- Descrição --}}
        <div>
            <x-input-label for="description" value="Descrição da Categoria (Opcional)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <textarea 
                id="description" 
                name="description" 
                rows="3" 
                class="w-full rounded-xl border border-slate-300 p-3.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                placeholder="Breve resumo sobre os temas abordados nos artigos desta categoria..."
            >{{ old('description', $blogCategory->description ?? '') }}</textarea>
            <p class="mt-1 text-xs text-slate-400">Esta descrição pode ser exibida no cabeçalho da página de filtragem do blog.</p>
            <x-input-error :messages="$errors->get('description')" class="mt-1.5 text-xs text-red-500" />
        </div>
    </div>

    {{-- BOTÕES DE AÇÃO --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <a 
            href="{{ route('admin.blog-categories.index') }}" 
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
            <span>{{ $editing ? 'Salvar Alterações' : 'Cadastrar Categoria do Blog' }}</span>
        </button>
    </div>
</form>
