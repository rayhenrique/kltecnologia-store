<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-800 bg-slate-950/95 backdrop-blur-md text-slate-100" aria-label="Navegação da conta">
    <a href="#conteudo" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:text-slate-900 focus:px-4 focus:py-2">Ir para o conteúdo</a>
    
    <div class="page-container">
        <div class="flex min-h-16 items-center justify-between gap-4">
            
            {{-- Logo e Links Principais --}}
            <div class="flex items-center gap-6 lg:gap-8">
                <a href="{{ route('storefront.index') }}" class="flex items-center gap-2.5 group shrink-0">
                    <img src="{{ asset('images/logo-kltecnologia.png') }}" alt="KL" class="h-9 w-9 rounded-xl object-cover shadow-md shadow-teal-500/20 group-hover:scale-105 transition duration-200" />
                    <div class="flex items-center gap-2">
                        <span class="font-display text-base font-bold tracking-tight text-white group-hover:text-teal-400 transition">
                            KL<span class="text-teal-400">Tecnologia</span>
                        </span>
                        @if(Auth::user()->isAdmin())
                            <span class="rounded bg-amber-400/10 border border-amber-400/20 px-1.5 py-0.5 font-mono text-[10px] font-black uppercase tracking-wider text-amber-400">
                                Admin
                            </span>
                        @else
                            <span class="rounded bg-teal-500/10 border border-teal-500/20 px-1.5 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-400">
                                Cliente
                            </span>
                        @endif
                    </div>
                </a>

                {{-- Navegação Desktop --}}
                <div class="hidden items-center gap-1 sm:flex">
                    <a 
                        href="{{ route('storefront.index') }}" 
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition"
                    >
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Vitrine</span>
                    </a>

                    @if (Auth::user()->isAdmin())
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 border border-slate-800 text-teal-400 font-bold shadow-xs' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                        >
                            <svg class="h-4 w-4 {{ request()->routeIs('admin.dashboard') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Painel</span>
                        </a>
                        <a 
                            href="{{ route('admin.products.index') }}" 
                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 border border-slate-800 text-teal-400 font-bold shadow-xs' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                        >
                            <svg class="h-4 w-4 {{ request()->routeIs('admin.products.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Produtos</span>
                        </a>
                        <a 
                            href="{{ route('admin.orders.index') }}" 
                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 border border-slate-800 text-teal-400 font-bold shadow-xs' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                        >
                            <svg class="h-4 w-4 {{ request()->routeIs('admin.orders.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>Pedidos</span>
                        </a>
                    @else
                        <a 
                            href="{{ route('customer.downloads') }}" 
                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ request()->routeIs('customer.*') ? 'bg-slate-900 border border-slate-800 text-teal-400 font-bold shadow-xs' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                        >
                            <svg class="h-4 w-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Meus downloads</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Lado Direito: Ações & Perfil --}}
            <div class="hidden items-center gap-3 sm:flex">
                @if(Auth::user()->isAdmin())
                    <a 
                        href="{{ route('admin.products.create') }}" 
                        class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 px-3.5 py-2 text-xs font-bold text-white shadow-md shadow-teal-500/20 transition transform active:scale-95"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Novo Produto</span>
                    </a>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-800 bg-slate-900/90 px-3 py-1.5 text-xs font-semibold text-slate-200 hover:border-slate-700 hover:text-white transition">
                            <span class="grid h-6 w-6 place-items-center rounded-lg bg-gradient-to-br from-teal-400 to-blue-600 font-display text-[11px] font-bold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="max-w-[130px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2.5 border-b border-slate-100 text-xs text-slate-500">
                            Conectado como <strong class="text-slate-800 block truncate">{{ Auth::user()->email }}</strong>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="text-xs">
                            Configurações de Perfil
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('catalog.index')" class="text-xs">
                            Ver Catálogo de Produtos
                        </x-dropdown-link>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" novalidate>
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-start text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                Encerrar Sessão
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Botão Menu Mobile --}}
            <button 
                type="button" 
                @click="open = !open" 
                class="grid h-10 w-10 place-items-center rounded-xl border border-slate-800 bg-slate-900 text-slate-300 hover:text-white sm:hidden" 
                :aria-expanded="open" 
                aria-controls="mobile-menu" 
                aria-label="Abrir menu"
            >
                <span aria-hidden="true" class="text-xl">☰</span>
            </button>
        </div>
    </div>

    {{-- Menu Mobile Expandido --}}
    <div id="mobile-menu" x-show="open" x-transition class="border-t border-slate-800 bg-slate-950 px-4 py-3 sm:hidden">
        <div class="space-y-1">
            <x-responsive-nav-link :href="route('storefront.index')" class="text-slate-200">Vitrine</x-responsive-nav-link>
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-slate-200">Painel</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="text-slate-200">Produtos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="text-slate-200">Pedidos</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('customer.downloads')" :active="request()->routeIs('customer.*')" class="text-slate-200">Meus downloads</x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.edit')" class="text-slate-200">Perfil</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}" novalidate>
                @csrf
                <button type="submit" class="block w-full rounded-lg px-3 py-2 text-start text-sm font-semibold text-red-400 hover:bg-slate-900">
                    Sair
                </button>
            </form>
        </div>
    </div>
</nav>
