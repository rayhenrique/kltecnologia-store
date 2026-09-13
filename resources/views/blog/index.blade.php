<x-storefront-layout title="Blog & Artigos">
    {{-- Hero Section --}}
    <section class="hero-tech-bg text-white py-14 sm:py-20 border-b border-slate-800">
        <div class="page-container text-center max-w-4xl mx-auto">
            <span class="inline-flex items-center gap-2 rounded-full bg-teal-500/10 border border-teal-500/30 px-3.5 py-1 text-xs font-mono font-bold tracking-wider uppercase text-teal-300">
                <span class="h-2 w-2 rounded-full bg-teal-400 animate-pulse"></span>
                Blog & Conteúdo KL Tecnologia
            </span>

            <h1 class="mt-5 font-display text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight">
                Artigos, Tutoriais & <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-blue-400">Estratégias Digitais</span>
            </h1>

            <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto">
                Explore conteúdos práticos sobre desenvolvimento web, automação, PHP, SaaS, marketing e escala de negócios online.
            </p>

            {{-- Formulário de Busca --}}
            <form action="{{ route('blog.index') }}" method="GET" class="mt-8 max-w-2xl mx-auto flex flex-col sm:flex-row gap-2">
                @if($selectedCategory)
                    <input type="hidden" name="categoria" value="{{ $selectedCategory }}">
                @endif
                <div class="relative flex-1">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}"
                        placeholder="Buscar por palavras-chave, assuntos ou tecnologias..." 
                        class="w-full rounded-xl border border-slate-700 bg-slate-900/90 pl-11 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:border-teal-400 focus:ring-2 focus:ring-teal-400/30 shadow-inner"
                    />
                    <svg class="pointer-events-none absolute left-4 top-3.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="btn-teal whitespace-nowrap shadow-lg shadow-teal-600/30">
                    Buscar Artigos
                </button>
            </form>

            {{-- Categorias Filter Pills --}}
            @if($categories->isNotEmpty())
                <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
                    <a 
                        href="{{ route('blog.index', array_filter(['q' => $search])) }}" 
                        class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-semibold transition {{ empty($selectedCategory) ? 'bg-teal-500 text-slate-950 font-bold shadow-md shadow-teal-500/20' : 'bg-slate-900/80 border border-slate-800 text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >
                        <span>Todos</span>
                        <span class="rounded-full bg-slate-950/40 px-1.5 py-0.2 text-[10px]">{{ \App\Models\Post::published()->count() }}</span>
                    </a>

                    @foreach($categories as $cat)
                        <a 
                            href="{{ route('blog.index', array_filter(['categoria' => $cat->category, 'q' => $search])) }}" 
                            class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-semibold transition {{ $selectedCategory === $cat->category ? 'bg-teal-500 text-slate-950 font-bold shadow-md shadow-teal-500/20' : 'bg-slate-900/80 border border-slate-800 text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        >
                            <span>{{ $cat->category }}</span>
                            <span class="rounded-full bg-slate-950/40 px-1.5 py-0.2 text-[10px]">{{ $cat->count }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Artigos Listing --}}
    <div class="py-12 sm:py-16 bg-slate-50 min-h-[500px]">
        <div class="page-container">
            {{-- Breadcrumb / Info bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-8 mb-8 border-b border-slate-200 gap-4">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                        <a href="{{ route('storefront.index') }}" class="hover:text-teal-600 transition">Início</a>
                        <span>/</span>
                        <a href="{{ route('blog.index') }}" class="text-teal-700">Blog</a>
                        @if($selectedCategory)
                            <span>/</span>
                            <span class="text-slate-800">{{ $selectedCategory }}</span>
                        @endif
                    </nav>
                    <h2 class="font-display text-xl font-bold text-slate-900">
                        @if($search)
                            Resultados para: <span class="text-teal-600">"{{ $search }}"</span>
                        @elseif($selectedCategory)
                            Categoria: <span class="text-teal-600">{{ $selectedCategory }}</span>
                        @else
                            Últimas Publicações
                        @endif
                    </h2>
                </div>

                <div class="text-xs font-mono text-slate-500">
                    Exibindo <span class="font-bold text-slate-800">{{ $posts->total() }}</span> artigos publicados
                </div>
            </div>

            @if($posts->isEmpty())
                {{-- Empty State --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center max-w-lg mx-auto shadow-sm">
                    <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-teal-50 text-teal-600 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-lg font-bold text-slate-900">Nenhum artigo encontrado</h3>
                    <p class="mt-2 text-sm text-slate-600">
                        Não encontramos publicações correspondentes aos filtros selecionados.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('blog.index') }}" class="btn-secondary text-xs">
                            Limpar Filtros e Ver Todos
                        </a>
                    </div>
                </div>
            @else
                {{-- Grid de Artigos --}}
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($posts as $post)
                        <article class="group flex flex-col rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-xs hover:shadow-xl hover:border-teal-500/50 transition duration-300 transform hover:-translate-y-1">
                            {{-- Capa --}}
                            <a href="{{ route('blog.show', $post) }}" class="relative block aspect-video w-full overflow-hidden bg-slate-900 shrink-0">
                                @if($post->cover_path)
                                    <img 
                                        src="{{ asset($post->cover_path) }}" 
                                        alt="{{ $post->title }}" 
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 p-6 text-center">
                                        <span class="font-display text-lg font-bold text-teal-400/80">KL Tecnologia</span>
                                    </div>
                                @endif

                                {{-- Categoria Badge Flutuante --}}
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center rounded-lg bg-slate-950/80 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-teal-400 border border-teal-500/30 shadow-xs">
                                        {{ $post->category ?? 'Artigo' }}
                                    </span>
                                </div>
                            </a>

                            {{-- Conteúdo do Card --}}
                            <div class="flex flex-1 flex-col p-6">
                                {{-- Metadados --}}
                                <div class="flex items-center justify-between text-xs text-slate-400 mb-3 font-mono">
                                    <span>
                                        {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        ~{{ $post->reading_time }} min leitura
                                    </span>
                                </div>

                                {{-- Título --}}
                                <h3 class="font-display text-lg font-bold text-slate-900 group-hover:text-teal-600 transition line-clamp-2 leading-snug">
                                    <a href="{{ route('blog.show', $post) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                {{-- Resumo --}}
                                <p class="mt-3 text-sm text-slate-600 line-clamp-3 leading-relaxed flex-1">
                                    {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
                                </p>

                                {{-- Rodapé do Card --}}
                                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ asset('images/logo-kltecnologia.png') }}" alt="KL" class="h-6 w-6 rounded-md object-cover shadow-xs" />
                                        <span class="font-semibold text-slate-700">KL Tecnologia</span>
                                    </div>

                                    <a 
                                        href="{{ route('blog.show', $post) }}" 
                                        class="inline-flex items-center gap-1 font-bold text-teal-600 group-hover:text-teal-700 group-hover:translate-x-0.5 transition"
                                    >
                                        <span>Ler artigo</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Paginação --}}
                @if($posts->hasPages())
                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-storefront-layout>
