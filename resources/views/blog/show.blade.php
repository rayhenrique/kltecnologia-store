<x-storefront-layout :title="$post->title">
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="page-container max-w-6xl">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6 flex-wrap">
                <a href="{{ route('storefront.index') }}" class="hover:text-teal-600 transition">Início</a>
                <span>/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-teal-600 transition">Blog</a>
                @if($post->category)
                    <span>/</span>
                    <a href="{{ route('blog.index', ['categoria' => $post->category]) }}" class="hover:text-teal-600 transition">{{ $post->category }}</a>
                @endif
                <span>/</span>
                <span class="text-slate-800 truncate max-w-xs">{{ $post->title }}</span>
            </nav>

            {{-- Artigo Header --}}
            <header class="mb-8">
                <div class="flex flex-wrap items-center gap-3 text-xs font-mono mb-4">
                    <span class="inline-flex items-center rounded-lg bg-teal-100 text-teal-800 font-bold px-3 py-1">
                        {{ $post->category ?? 'Artigo' }}
                    </span>
                    <span class="text-slate-400">&bull;</span>
                    <span class="text-slate-500">
                        {{ $post->published_at ? $post->published_at->format('d \d\e F \d\e Y') : $post->created_at->format('d \d\e F \d\e Y') }}
                    </span>
                    <span class="text-slate-400">&bull;</span>
                    <span class="inline-flex items-center gap-1 text-slate-500">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ~{{ $post->reading_time }} min de leitura
                    </span>
                    <span class="text-slate-400">&bull;</span>
                    <span class="inline-flex items-center gap-1 text-slate-500">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ number_format($post->views_count) }} visualizações
                    </span>
                </div>

                <h1 class="font-display text-2xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $post->title }}
                </h1>

                @if($post->excerpt)
                    <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed font-normal">
                        {{ $post->excerpt }}
                    </p>
                @endif
            </header>

            {{-- Imagem de Capa em Destaque --}}
            @if($post->cover_path)
                <div class="mb-10 overflow-hidden rounded-3xl border border-slate-200 bg-slate-950 shadow-lg">
                    <img 
                        src="{{ asset($post->cover_path) }}" 
                        alt="{{ $post->title }}" 
                        class="w-full max-h-[520px] object-cover mx-auto"
                    />
                </div>
            @endif

            {{-- Layout de 2 Colunas: Artigo e Barra Lateral --}}
            <div class="grid gap-10 lg:grid-cols-12 items-start">
                {{-- Coluna Principal: Conteúdo do Artigo --}}
                <main class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-xs">
                    {{-- Conteúdo do Artigo (HTML) --}}
                    <article class="blog-content">
                        {!! $post->content !!}
                    </article>

                    {{-- Botões de Compartilhamento --}}
                    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4" x-data="{ copied: false }">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <svg class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            <span>Compartilhe este artigo:</span>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- WhatsApp --}}
                            <a 
                                href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . url()->current()) }}" 
                                target="_blank" 
                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 text-xs font-semibold hover:bg-emerald-100 transition"
                            >
                                WhatsApp
                            </a>

                            {{-- Twitter/X --}}
                            <a 
                                href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" 
                                target="_blank" 
                                class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 text-slate-800 border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-200 transition"
                            >
                                X / Twitter
                            </a>

                            {{-- Copiar Link --}}
                            <button 
                                type="button" 
                                @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 px-3 py-1.5 text-xs font-semibold hover:bg-teal-100 transition"
                            >
                                <span x-show="!copied">Copiar Link</span>
                                <span x-show="copied" class="font-bold text-teal-800">Copiado!</span>
                            </button>
                        </div>
                    </div>

                    {{-- Caixa do Autor --}}
                    <div class="mt-8 rounded-2xl border border-slate-100 bg-slate-50 p-6 flex flex-col sm:flex-row items-center gap-5">
                        <div class="h-16 w-16 shrink-0 rounded-2xl bg-gradient-to-br from-teal-400 to-blue-600 grid place-items-center text-white font-display font-extrabold text-xl shadow-md shadow-teal-500/20">
                            KL
                        </div>
                        <div class="text-center sm:text-left">
                            <span class="font-mono text-[11px] font-bold uppercase tracking-wider text-teal-700">Equipe Editorial</span>
                            <h4 class="font-display text-base font-bold text-slate-900 mt-0.5">KL Tecnologia</h4>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                Plataforma pioneira em produtos digitais de alta performance, scripts autorais em PHP/Laravel e sistemas escaláveis para impulsionar negócios online com segurança e suporte.
                            </p>
                        </div>
                    </div>
                </main>

                {{-- Barra Lateral (Sticky) --}}
                <aside class="lg:col-span-4 space-y-8 lg:sticky lg:top-24">
                    {{-- Banner CTA Catálogo da Loja --}}
                    <div class="rounded-3xl border border-teal-800/60 bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950 p-6 text-white shadow-xl">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-500/20 border border-teal-500/30 px-3 py-0.5 text-[11px] font-mono font-bold text-teal-300 uppercase">
                            Catálogo Premium
                        </span>
                        <h3 class="mt-3 font-display text-lg font-bold text-white leading-snug">
                            Precisa de sistemas prontos e validados?
                        </h3>
                        <p class="mt-2 text-xs text-slate-300 leading-relaxed">
                            Acesse nosso catálogo completo com centenas de sistemas, scripts e templates para download imediato.
                        </p>
                        <a 
                            href="{{ route('catalog.index') }}" 
                            class="mt-5 btn-teal w-full text-center text-xs font-bold !min-h-10 shadow-lg shadow-teal-600/30"
                        >
                            Explorar Todos os Produtos &rarr;
                        </a>
                    </div>

                    {{-- Artigos Relacionados --}}
                    @if($relatedPosts->isNotEmpty())
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                            <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                                Artigos Relacionados
                            </h3>
                            <div class="mt-4 divide-y divide-slate-100">
                                @foreach($relatedPosts as $rel)
                                    <div class="py-3.5 first:pt-0 last:pb-0 group">
                                        <span class="font-mono text-[10px] text-teal-600 font-bold uppercase">
                                            {{ $rel->category ?? 'Blog' }}
                                        </span>
                                        <a 
                                            href="{{ route('blog.show', $rel) }}" 
                                            class="block font-display text-xs font-bold text-slate-800 group-hover:text-teal-600 transition line-clamp-2 mt-1"
                                        >
                                            {{ $rel->title }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 font-mono mt-1 block">
                                            {{ $rel->published_at ? $rel->published_at->format('d/m/Y') : $rel->created_at->format('d/m/Y') }} &bull; ~{{ $rel->reading_time }} min
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Artigos Mais Recentes --}}
                    @if($recentPosts->isNotEmpty())
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                            <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                Publicações Recentes
                            </h3>
                            <div class="mt-4 divide-y divide-slate-100">
                                @foreach($recentPosts as $rec)
                                    <div class="py-3.5 first:pt-0 last:pb-0 group">
                                        <a 
                                            href="{{ route('blog.show', $rec) }}" 
                                            class="block font-display text-xs font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-2"
                                        >
                                            {{ $rec->title }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 font-mono mt-1 block">
                                            {{ $rec->published_at ? $rec->published_at->format('d/m/Y') : $rec->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-storefront-layout>
