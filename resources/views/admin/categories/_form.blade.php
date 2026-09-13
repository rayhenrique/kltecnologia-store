@php($editing = isset($category))

<form 
    method="POST" 
    action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}" 
    novalidate 
    class="space-y-6" 
    x-data="{ 
        submitting: false, 
        dirty: false,
        name: @js(old('name', $category->name ?? '')),
        icon: @js(old('icon', $category->icon ?? 'code'))
    }" 
    x-on:change="dirty = true" 
    x-on:submit="submitting = true; dirty = false" 
    x-on:beforeunload.window="if (dirty) $event.preventDefault()"
>
    @csrf 
    @if($editing) 
        @method('PUT') 
    @endif

    {{-- CARD DE INFORMAÇÕES DA CATEGORIA --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">
                    Dados da Categoria
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Categorize seus produtos digitais para facilitar a navegação e filtragem na vitrine.
                </p>
            </div>

            <label class="relative inline-flex items-center cursor-pointer select-none">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1" 
                    class="sr-only peer"
                    {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                >
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                <span class="ml-3 text-xs font-semibold text-slate-700">Categoria Ativa</span>
            </label>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            {{-- Nome --}}
            <div>
                <x-input-label for="name" value="Nome da Categoria" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <x-text-input 
                    id="name" 
                    name="name" 
                    type="text" 
                    x-model="name"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                    placeholder="Ex: Scripts PHP, Sistemas SaaS, Automação" 
                    required 
                    aria-describedby="name-error" 
                    :aria-invalid="$errors->has('name') ? 'true' : 'false'" 
                />
                <x-input-error id="name-error" :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-500" />
            </div>

            {{-- Ícone / Identificador Visual --}}
            <div>
                <x-input-label for="icon" value="Identificador de Ícone (Opcional)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="flex gap-2">
                    <input 
                        id="icon" 
                        name="icon" 
                        type="text" 
                        x-model="icon"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                        placeholder="Ex: code, cloud, bot, chart, template, mobile" 
                    />
                </div>
                <div class="mt-1.5 flex flex-wrap gap-1.5 text-[11px] text-slate-500">
                    <span class="font-semibold">Sugestões:</span>
                    <button type="button" @click="icon = 'code'" class="hover:text-teal-600 underline">code</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'cloud'" class="hover:text-teal-600 underline">cloud</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'bot'" class="hover:text-teal-600 underline">bot</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'chart'" class="hover:text-teal-600 underline">chart</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'template'" class="hover:text-teal-600 underline">template</button>
                    <span>&bull;</span>
                    <button type="button" @click="icon = 'mobile'" class="hover:text-teal-600 underline">mobile</button>
                </div>
                <x-input-error :messages="$errors->get('icon')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>

        {{-- Descrição --}}
        <div>
            <x-input-label for="description" value="Descrição (Opcional)" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <textarea 
                id="description" 
                name="description" 
                rows="3" 
                class="w-full rounded-xl border border-slate-300 p-3.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs" 
                placeholder="Breve descrição dos produtos agrupados nesta categoria..."
            >{{ old('description', $category->description ?? '') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-1.5 text-xs text-red-500" />
        </div>

        @if($editing)
            <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-4 flex items-center justify-between text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-800">Slug da URL:</span>
                    <code class="rounded bg-white px-2 py-0.5 border border-slate-200 font-mono text-teal-700 font-semibold">{{ $category->slug }}</code>
                </div>
                <div class="flex items-center gap-2 font-mono">
                    <span>{{ $category->products_count ?? 0 }} produtos associados</span>
                </div>
            </div>
        @endif
    </div>

    {{-- BOTÕES DE AÇÃO --}}
    <div class="flex items-center justify-between pt-2">
        <a 
            href="{{ route('admin.categories.index') }}" 
            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cancelar e Voltar
        </a>

        <button 
            type="submit" 
            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-500 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-teal-600/30 transition disabled:opacity-50"
            :disabled="submitting"
        >
            <template x-if="submitting">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <span>{{ $editing ? 'Salvar Alterações' : 'Cadastrar Categoria' }}</span>
        </button>
    </div>
</form>
