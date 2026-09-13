<x-storefront-layout>
    <x-slot:title>{{ $product->title }}</x-slot:title>

    {{-- Page Header Banner --}}
    <section class="hero-tech-bg border-b border-slate-800 text-white py-8 lg:py-12 relative overflow-hidden">
        <div class="page-container relative z-10">
            {{-- Breadcrumbs --}}
            <nav aria-label="Breadcrumb" class="flex items-center flex-wrap gap-2 text-xs sm:text-sm text-slate-400">
                <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition">Início</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('catalog.index') }}" class="hover:text-teal-400 transition">Catálogo</a>
                <span class="text-slate-600">/</span>
                <span class="text-slate-400">Scripts & SaaS</span>
                <span class="text-slate-600">/</span>
                <span class="text-teal-400 font-medium truncate max-w-[200px] sm:max-w-xs md:max-w-md">{{ $product->title }}</span>
            </nav>

            {{-- Badges & Title --}}
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="badge-new">
                    ★ Código Fonte Incluso
                </span>
                <span class="inline-flex items-center gap-1 rounded bg-slate-800 border border-slate-700 px-2 py-0.5 text-[11px] font-semibold text-slate-300">
                    ⚡ Entrega Imediata
                </span>
                <span class="inline-flex items-center gap-1 rounded bg-slate-800 border border-slate-700 px-2 py-0.5 text-[11px] font-semibold text-slate-300">
                    ♾️ Uso Vitalício
                </span>
                <span class="text-xs text-slate-400 ml-auto hidden sm:inline-block">
                    Atualizado em {{ $product->updated_at->format('d/m/Y') }}
                </span>
            </div>

            <h1 class="mt-4 font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-snug max-w-4xl">
                {{ $product->title }}
            </h1>
        </div>
    </section>

    {{-- Main Content Grid (2 Columns) --}}
    <section class="page-container py-10 lg:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            
            {{-- Coluna Esquerda (~68%): Capa, Destaque e Abas --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- Imagem de Capa do Produto --}}
                <div class="panel overflow-hidden border border-slate-200/80 shadow-md bg-white rounded-2xl">
                    <div class="relative bg-slate-900 group">
                        @if($product->cover_path)
                            <img 
                                src="{{ asset($product->cover_path) }}" 
                                alt="{{ $product->title }}" 
                                class="w-full aspect-[16/10] object-cover object-center group-hover:scale-[1.01] transition duration-300"
                            />
                        @else
                            <div class="grid aspect-[16/10] w-full place-items-center bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 font-display text-6xl font-bold text-teal-400">
                                <span>KL</span>
                            </div>
                        @endif

                        <div class="absolute top-4 left-4">
                            <span class="rounded-lg bg-slate-950/80 backdrop-blur-md border border-slate-700/60 px-3 py-1.5 text-xs font-bold text-teal-300 shadow-lg">
                                ★ Licença Comercial Definitiva
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Box de Destaque / Garantia de Acesso --}}
                <div class="rounded-2xl border border-teal-200 bg-gradient-to-r from-teal-50 via-white to-emerald-50 p-5 text-teal-950 flex items-start gap-3.5 shadow-sm">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-display text-sm font-bold text-teal-950">Acesso Vitalício & Código 100% Desbloqueado</h2>
                        <p class="mt-1 text-xs sm:text-sm text-teal-800/90 leading-relaxed">
                            Ao adquirir este item, você recebe o download imediato dos arquivos fontes completos, banco de dados SQL e manual de instalação. Sem mensalidades, sem travas de domínio e pronto para produção.
                        </p>
                    </div>
                </div>

                {{-- Abas Interativas (Alpine.js) --}}
                <div x-data="{ tab: 'description' }" class="panel border border-slate-200/80 shadow-sm rounded-2xl overflow-hidden bg-white">
                    {{-- Tab Headers --}}
                    <div class="flex items-center border-b border-slate-200 bg-slate-50/75 px-4 pt-2 gap-2 overflow-x-auto">
                        <button 
                            type="button" 
                            @click="tab = 'description'" 
                            :class="tab === 'description' ? 'border-teal-600 text-teal-700 bg-white font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-medium'"
                            class="inline-flex items-center gap-2 border-b-2 py-3.5 px-4 text-sm transition whitespace-nowrap rounded-t-lg"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Descrição do Item
                        </button>

                        <button 
                            type="button" 
                            @click="tab = 'features'" 
                            :class="tab === 'features' ? 'border-teal-600 text-teal-700 bg-white font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-medium'"
                            class="inline-flex items-center gap-2 border-b-2 py-3.5 px-4 text-sm transition whitespace-nowrap rounded-t-lg"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Recursos & Requisitos
                        </button>

                        <button 
                            type="button" 
                            @click="tab = 'reviews'" 
                            :class="tab === 'reviews' ? 'border-teal-600 text-teal-700 bg-white font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-medium'"
                            class="inline-flex items-center gap-2 border-b-2 py-3.5 px-4 text-sm transition whitespace-nowrap rounded-t-lg"
                        >
                            <svg class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Avaliações & Garantia
                        </button>
                    </div>

                    {{-- Tab 1: Descrição Detalhada --}}
                    <div x-show="tab === 'description'" class="p-6 sm:p-8 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="font-display text-lg font-bold text-slate-900">Sobre este produto</h3>
                            <span class="text-xs font-mono text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">ID: #{{ $product->id }}</span>
                        </div>

                        <div class="space-y-3 pt-2 text-slate-700">
                            @foreach(explode("\n", $product->description) as $line)
                                @php $trimmed = trim($line); @endphp
                                @if(empty($trimmed))
                                    {{-- Linha vazia --}}
                                @elseif(str_starts_with($trimmed, '###') || str_starts_with($trimmed, '##'))
                                    <div class="pt-5 pb-1 border-b border-slate-100">
                                        <h4 class="font-display text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                                            {{ ltrim($trimmed, '# ') }}
                                        </h4>
                                    </div>
                                @elseif(str_starts_with($trimmed, '•') || str_starts_with($trimmed, '-') || str_starts_with($trimmed, '*') || str_starts_with($trimmed, '✅'))
                                    <div class="flex items-start gap-3 pl-1 py-0.5">
                                        <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-teal-100 text-teal-700 text-xs font-bold mt-0.5">
                                            ✓
                                        </span>
                                        <span class="text-sm sm:text-base text-slate-700 leading-relaxed">{{ ltrim($trimmed, '•-* ✅') }}</span>
                                    </div>
                                @else
                                    <p class="text-sm sm:text-base text-slate-700 leading-relaxed">{{ $trimmed }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab 2: Recursos & Requisitos Técnicos --}}
                    <div x-show="tab === 'features'" x-cloak class="p-6 sm:p-8 space-y-6">
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900">Requisitos Recomendados do Servidor</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">Compatível com 99% das hospedagens compartilhadas e servidores VPS do mercado.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                <span class="text-xs font-mono uppercase font-bold text-teal-700">Linguagem Backend</span>
                                <p class="font-display text-sm font-bold text-slate-900 mt-1">PHP 8.1 / 8.2 / 8.3</p>
                                <p class="text-xs text-slate-500 mt-1">Com extensões PDO, cURL, OpenSSL e Mbstring habilitadas.</p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                <span class="text-xs font-mono uppercase font-bold text-teal-700">Banco de Dados</span>
                                <p class="font-display text-sm font-bold text-slate-900 mt-1">MySQL 5.7+ ou MariaDB 10.3+</p>
                                <p class="text-xs text-slate-500 mt-1">Acompanha arquivo .SQL com todas as tabelas prontas para importação.</p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                <span class="text-xs font-mono uppercase font-bold text-teal-700">Servidor Web</span>
                                <p class="font-display text-sm font-bold text-slate-900 mt-1">Apache ou Nginx</p>
                                <p class="text-xs text-slate-500 mt-1">Suporte completo a mod_rewrite e URLs amigáveis.</p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                <span class="text-xs font-mono uppercase font-bold text-teal-700">Painéis Suportados</span>
                                <p class="font-display text-sm font-bold text-slate-900 mt-1">cPanel, Plesk, CloudPanel, VPS</p>
                                <p class="text-xs text-slate-500 mt-1">Instalação simples via gerenciador de arquivos ou SSH/FTP.</p>
                            </div>
                        </div>

                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 text-emerald-950 flex items-start gap-3">
                            <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-xs sm:text-sm">
                                <strong class="font-semibold">Licença Livre de Domínio:</strong> Você não precisa de chaves de ativação ou autorização prévia. Pode utilizar em seus projetos ou instalar diretamente em clientes.
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Avaliações & Suporte --}}
                    <div x-show="tab === 'reviews'" x-cloak class="p-6 sm:p-8 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-display text-3xl font-extrabold text-slate-900">5.0</span>
                                    <div class="flex text-amber-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-slate-500">(48 avaliações de clientes verificados)</span>
                                </div>
                                <p class="mt-1 text-xs sm:text-sm text-slate-500">100% dos compradores avaliaram como excelente.</p>
                            </div>

                            <a href="https://wa.me/5541998608485?text={{ urlencode('Olá! Gostaria de falar sobre o produto ' . $product->title) }}" target="_blank" rel="noopener noreferrer" class="btn-secondary text-xs sm:text-sm">
                                Tirar Dúvida com Especialista
                            </a>
                        </div>

                        {{-- Depoimentos Verificados --}}
                        <div class="space-y-4">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                <div class="flex items-center justify-between">
                                    <span class="font-display text-sm font-bold text-slate-900">Rafael Mendonça — Desenvolvedor</span>
                                    <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Compra Verificada
                                    </span>
                                </div>
                                <div class="flex text-amber-400 mt-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                                <p class="mt-2 text-xs sm:text-sm text-slate-600">
                                    "Código fonte muito limpo e bem comentado. Subi no cPanel da Hostinger e funcionou de primeira. O suporte também me respondeu super rápido no WhatsApp."
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                <div class="flex items-center justify-between">
                                    <span class="font-display text-sm font-bold text-slate-900">Lucas Teixeira — Agência Digital</span>
                                    <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Compra Verificada
                                    </span>
                                </div>
                                <div class="flex text-amber-400 mt-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                                <p class="mt-2 text-xs sm:text-sm text-slate-600">
                                    "O melhor investimento para nossa agência. Compramos com pagamento único e já revendemos como serviço para dois clientes na mesma semana. Download liberado no segundo seguinte ao Pix."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Coluna Direita (~32%): Sidebar Sticky com Compra e Ficha Técnica --}}
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                {{-- Card de Compra (Purchase Card) --}}
                <div class="panel rounded-2xl border-2 border-teal-500/40 bg-white p-6 shadow-xl shadow-teal-950/5 relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-teal-500/10 blur-xl pointer-events-none"></div>

                    {{-- Top Flag --}}
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 rounded-full bg-teal-100 text-teal-800 px-3 py-0.5 text-xs font-bold uppercase tracking-wider">
                            <span class="h-1.5 w-1.5 rounded-full bg-teal-600"></span>
                            Pagamento Único
                        </span>
                        <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                            ⚡ Envio Imediato
                        </span>
                    </div>

                    {{-- Price Display --}}
                    <div class="mt-5 border-y border-slate-100 py-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-xs font-medium text-slate-400">De:</span>
                            <del class="text-sm font-semibold text-slate-400">
                                R$ {{ number_format((float) ($product->price * 1.35), 2, ',', '.') }}
                            </del>
                        </div>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-xs font-semibold text-slate-600">Por apenas:</span>
                            <span class="font-display text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-tight">
                                R$ {{ number_format((float) $product->price, 2, ',', '.') }}
                            </span>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">
                            Disponível no Pix à vista ou parcelado no Cartão de Crédito.
                        </p>
                    </div>

                    {{-- Buttons: Comprar Agora & WhatsApp --}}
                    <div class="mt-5 space-y-3">
                        @auth
                            <form method="POST" action="{{ route('checkout.store', $product) }}" novalidate x-data="{ submitting: false }" x-on:submit="submitting = true">
                                @csrf
                                <button 
                                    class="w-full btn-teal text-base py-3.5 px-6 font-bold shadow-lg shadow-teal-600/30 flex items-center justify-center gap-2 group transition" 
                                    type="submit" 
                                    :disabled="submitting"
                                >
                                    <span x-show="!submitting" class="flex items-center gap-2">
                                        <svg class="h-5 w-5 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        Comprar Agora
                                    </span>
                                    <span x-show="submitting" x-cloak class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        Processando checkout...
                                    </span>
                                </button>
                            </form>
                        @else
                            <a 
                                href="{{ route('login', ['redirect' => route('storefront.show', $product, false)]) }}" 
                                class="w-full btn-teal text-base py-3.5 px-6 font-bold shadow-lg shadow-teal-600/30 flex items-center justify-center gap-2 group transition"
                            >
                                <svg class="h-5 w-5 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Entrar para Comprar
                            </a>
                        @endauth

                        {{-- Botão de WhatsApp --}}
                        <a 
                            href="https://wa.me/5541998608485?text={{ urlencode('Olá! Gostaria de tirar dúvidas sobre o produto: ' . $product->title) }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-500/40 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold py-3 px-4 text-sm transition"
                        >
                            <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.062-2.18-.553-1.614-.666-2.736-2.3-2.825-2.42-.089-.12-1.042-1.385-1.042-2.641 0-1.256.657-1.874.887-2.13.23-.257.51-.322.68-.322.17 0 .34.003.49.01.157.009.366-.06.574.44.214.512.73 1.776.794 1.905.064.13.107.28.021.451-.085.17-.128.277-.255.426-.128.149-.268.332-.383.447-.128.128-.261.267-.112.523.149.256.662 1.089 1.42 1.764.975.869 1.796 1.139 2.052 1.267.256.128.405.107.554-.064.15-.17.639-.746.81-1.002.17-.256.341-.213.575-.128.234.085 1.491.703 1.747.831.256.128.426.192.49.3.064.106.064.618-.08 1.023z"/>
                            </svg>
                            Tirar Dúvidas no WhatsApp
                        </a>
                    </div>

                    {{-- Security & Delivery Checklist --}}
                    <div class="mt-6 border-t border-slate-100 pt-5 space-y-2.5 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Download liberado automaticamente em <strong>Meus Downloads</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Pagamento seguro via <strong>Mercado Pago</strong> (Pix ou Cartão)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Código 100% aberto e sem limites de domínios</span>
                        </div>
                    </div>
                </div>

                {{-- Informações do Produto (Product Info Card) --}}
                <div class="panel rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">
                        Informações do Produto
                    </h3>

                    <dl class="mt-4 divide-y divide-slate-100 text-xs sm:text-sm">
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Categoria</dt>
                            <dd class="font-semibold text-slate-900">Scripts PHP / SaaS</dd>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Atualizado</dt>
                            <dd class="font-semibold text-slate-900">{{ $product->updated_at->format('d/m/Y') }}</dd>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Licença</dt>
                            <dd class="font-semibold text-teal-700">Comercial & Vitalícia</dd>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Entrega</dt>
                            <dd class="font-semibold text-emerald-600">Download Imediato</dd>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Arquivos Inclusos</dt>
                            <dd class="font-semibold text-slate-900">Código Fonte + SQL</dd>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <dt class="text-slate-500 font-medium">Versão</dt>
                            <dd class="font-semibold text-slate-900">1.0 (Produção)</dd>
                        </div>
                    </dl>
                </div>

            </div>

        </div>
    </section>

    {{-- Trust Badges Section ("Por que comprar em nosso site?") --}}
    <section class="border-y border-slate-200 bg-white py-14">
        <div class="page-container">
            <div class="text-center max-w-2xl mx-auto">
                <span class="eyebrow">Segurança & Confiabilidade</span>
                <h2 class="mt-2 font-display text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    Por que comprar na KL Tecnologia?
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Garantimos uma experiência segura, transparente e com entrega digital automatizada.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                {{-- Badge 1 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Compra Segura</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Ambiente 100% criptografado com checkout oficial Mercado Pago.
                    </p>
                </div>

                {{-- Badge 2 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Acesso Imediato</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Arquivos liberados na sua conta assim que o pagamento é aprovado.
                    </p>
                </div>

                {{-- Badge 3 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Suporte Dedicado</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Atendimento direto via WhatsApp e e-mail para tirar dúvidas.
                    </p>
                </div>

                {{-- Badge 4 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Código Completo</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Sem criptografia ou travas de domínio. Modifique como desejar.
                    </p>
                </div>

                {{-- Badge 5 --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-center hover:border-teal-300 hover:shadow-md transition">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-600 text-white shadow-md shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-sm font-bold text-slate-900">Pagamento Único</h3>
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Sem mensalidades ou surpresas. O sistema é seu para sempre.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Products Section ("Você pode gostar") --}}
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="page-container py-14">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="eyebrow">Recomendações</span>
                    <h2 class="mt-1 font-display text-2xl font-bold text-slate-900 tracking-tight">
                        Você pode gostar
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Outros scripts, sistemas e ferramentas selecionados para acelerar seus projetos.
                    </p>
                </div>
                <a href="{{ route('catalog.index') }}" class="btn-secondary text-xs sm:text-sm font-semibold">
                    Ver Catálogo Completo →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <article class="panel group flex flex-col justify-between overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-lg hover:border-teal-500/50 transition duration-200 rounded-2xl bg-white">
                        <div>
                            {{-- Cover --}}
                            <div class="relative aspect-[16/10] overflow-hidden bg-slate-900">
                                @if($related->cover_path)
                                    <img 
                                        src="{{ asset($related->cover_path) }}" 
                                        alt="{{ $related->title }}" 
                                        class="h-full w-full object-cover group-hover:scale-105 transition duration-300"
                                    />
                                @else
                                    <div class="grid h-full w-full place-items-center bg-gradient-to-br from-slate-800 to-teal-950 font-display text-3xl font-bold text-teal-400">
                                        KL
                                    </div>
                                @endif
                                <div class="absolute top-2.5 left-2.5">
                                    <span class="badge-hot">Código Fonte</span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-teal-700">Scripts & SaaS</span>
                                <h3 class="mt-1 font-display text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-teal-700 transition">
                                    <a href="{{ route('storefront.show', $related) }}">
                                        {{ $related->title }}
                                    </a>
                                </h3>
                            </div>
                        </div>

                        {{-- Footer / Price --}}
                        <div class="p-4 pt-0 border-t border-slate-100 flex items-center justify-between mt-3">
                            <div>
                                <del class="text-[11px] font-medium text-slate-400">
                                    R$ {{ number_format((float) ($related->price * 1.35), 2, ',', '.') }}
                                </del>
                                <p class="font-display text-base font-extrabold text-slate-950">
                                    R$ {{ number_format((float) $related->price, 2, ',', '.') }}
                                </p>
                            </div>
                            <a href="{{ route('storefront.show', $related) }}" class="inline-flex items-center justify-center rounded-xl bg-slate-100 hover:bg-teal-50 text-slate-700 hover:text-teal-700 font-bold px-3 py-1.5 text-xs transition">
                                Ver Detalhes
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Dicas do Blog / Artigos & Guias --}}
    <section class="border-t border-slate-200 bg-slate-50 py-14">
        <div class="page-container">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="eyebrow">Conhecimento & Tutoriais</span>
                    <h2 class="mt-1 font-display text-2xl font-bold text-slate-900 tracking-tight">
                        Do Blog & Guias Técnicos
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Dicas práticas para você implementar e lucrar com sistemas web.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <article class="panel overflow-hidden border border-slate-200/80 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="h-40 bg-gradient-to-br from-teal-900 via-slate-900 to-slate-950 p-5 flex flex-col justify-end text-white">
                        <span class="text-xs font-mono text-teal-400 font-bold uppercase">Hospedagem & VPS</span>
                        <h3 class="font-display text-base font-bold mt-1 text-white">Como configurar sistemas PHP em VPS de alta performance</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Aprenda a subir sistemas e configurar bancos de dados no CloudPanel ou cPanel com segurança e SSL gratuito.
                        </p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-teal-700 hover:text-teal-800">
                            Ler artigo completo →
                        </a>
                    </div>
                </article>

                <article class="panel overflow-hidden border border-slate-200/80 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="h-40 bg-gradient-to-br from-blue-900 via-slate-900 to-slate-950 p-5 flex flex-col justify-end text-white">
                        <span class="text-xs font-mono text-blue-400 font-bold uppercase">Vendas & Conversão</span>
                        <h3 class="font-display text-base font-bold mt-1 text-white">Automatizando pedidos e catálogos via API de WhatsApp</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Descubra como integrar webhooks e mensagens automáticas para aumentar a taxa de conversão do seu negócio.
                        </p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-teal-700 hover:text-teal-800">
                            Ler artigo completo →
                        </a>
                    </div>
                </article>

                <article class="panel overflow-hidden border border-slate-200/80 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="h-40 bg-gradient-to-br from-emerald-900 via-slate-900 to-slate-950 p-5 flex flex-col justify-end text-white">
                        <span class="text-xs font-mono text-emerald-400 font-bold uppercase">Segurança & SaaS</span>
                        <h3 class="font-display text-base font-bold mt-1 text-white">Boas práticas de proteção para bancos de dados MySQL</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Passos essenciais para blindar seu servidor contra invasões e manter os dados de clientes protegidos.
                        </p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-teal-700 hover:text-teal-800">
                            Ler artigo completo →
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Dúvidas Frequentes (FAQ Accordion) --}}
    <section class="page-container py-14">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="eyebrow">Dúvidas Frequentes</span>
            <h2 class="mt-2 font-display text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Perguntas Frequentes sobre a Compra
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                Tire suas dúvidas antes de finalizar sua aquisição na KL Tecnologia.
            </p>
        </div>

        <div x-data="{ openFaq: 1 }" class="max-w-3xl mx-auto space-y-3">
            {{-- FAQ 1 --}}
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <button 
                    type="button" 
                    @click="openFaq = openFaq === 1 ? null : 1" 
                    class="w-full flex items-center justify-between p-5 text-left font-display text-sm sm:text-base font-bold text-slate-900 hover:text-teal-700 transition"
                >
                    <span>Como recebo o sistema após a confirmação do pagamento?</span>
                    <svg :class="openFaq === 1 ? 'rotate-180 text-teal-600' : 'text-slate-400'" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openFaq === 1" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    A entrega é 100% digital e imediata! Assim que o Mercado Pago aprova o pagamento (Pix ou Cartão), o link de download fica liberado automaticamente na sua conta no menu <strong>Meus Downloads</strong>.
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <button 
                    type="button" 
                    @click="openFaq = openFaq === 2 ? null : 2" 
                    class="w-full flex items-center justify-between p-5 text-left font-display text-sm sm:text-base font-bold text-slate-900 hover:text-teal-700 transition"
                >
                    <span>O código fonte é 100% aberto ou possui arquivos criptografados?</span>
                    <svg :class="openFaq === 2 ? 'rotate-180 text-teal-600' : 'text-slate-400'" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openFaq === 2" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Todos os nossos produtos acompanham o código fonte completo e desbloqueado (sem criptografia ou ionCube), permitindo que você personalize a identidade visual, funcionalidades e hospede no servidor que desejar.
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <button 
                    type="button" 
                    @click="openFaq = openFaq === 3 ? null : 3" 
                    class="w-full flex items-center justify-between p-5 text-left font-display text-sm sm:text-base font-bold text-slate-900 hover:text-teal-700 transition"
                >
                    <span>Posso instalar em quantos domínios eu quiser?</span>
                    <svg :class="openFaq === 3 ? 'rotate-180 text-teal-600' : 'text-slate-400'" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openFaq === 3" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Sim! A licença é comercial e vitalícia, sem qualquer trava por domínio. Você pode implementar em seus próprios negócios ou até mesmo customizar e instalar para seus clientes finais como prestador de serviços.
                </div>
            </div>

            {{-- FAQ 4 --}}
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <button 
                    type="button" 
                    @click="openFaq = openFaq === 4 ? null : 4" 
                    class="w-full flex items-center justify-between p-5 text-left font-display text-sm sm:text-base font-bold text-slate-900 hover:text-teal-700 transition"
                >
                    <span>Como funciona caso eu precise de suporte para instalação?</span>
                    <svg :class="openFaq === 4 ? 'rotate-180 text-teal-600' : 'text-slate-400'" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openFaq === 4" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Disponibilizamos suporte direto pelo WhatsApp oficial <strong>(41) 99860-8485</strong> e e-mail para orientar sobre os requisitos do servidor, banco de dados e resolução de eventuais dúvidas de configuração.
                </div>
            </div>
        </div>
    </section>
</x-storefront-layout>
