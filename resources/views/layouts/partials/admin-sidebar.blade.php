@php
    $isMobile = $isMobile ?? false;
@endphp

{{-- Sidebar Brand / Logo --}}
<div 
    class="flex h-16 shrink-0 items-center border-b border-slate-800/80 bg-slate-950 transition-all duration-300"
    :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center px-2' : 'justify-between px-6'"
>
    <a 
        href="{{ route('admin.dashboard') }}" 
        class="flex items-center gap-3 group overflow-hidden"
        :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'KL Tecnologia - Painel Admin' : ''"
    >
        <img 
            src="{{ asset('images/logo-kltecnologia.png') }}" 
            alt="KL" 
            class="h-9 w-9 rounded-xl object-cover shadow-md shadow-teal-500/20 group-hover:scale-105 transition duration-200 shrink-0" 
        />
        <div 
            class="flex flex-col transition-opacity duration-200" 
            x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}"
            x-cloak
        >
            <span class="font-display text-sm font-bold tracking-tight text-white group-hover:text-teal-400 transition leading-tight whitespace-nowrap">
                KL<span class="text-teal-400">Tecnologia</span>
            </span>
            <span class="font-mono text-[10px] text-slate-500 uppercase tracking-widest font-semibold whitespace-nowrap">Painel Admin</span>
        </div>
    </a>

    @if(!$isMobile)
        <div class="flex items-center gap-1.5" x-show="!sidebarCollapsed" x-cloak>
            <span class="rounded bg-teal-500/10 border border-teal-500/30 px-1.5 py-0.5 font-mono text-[9px] font-bold text-teal-400 uppercase">
                v2.0
            </span>
            <button 
                type="button" 
                @click="toggleSidebar()"
                class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-900 transition cursor-pointer"
                title="Recolher menu lateral"
                aria-label="Recolher menu lateral"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>
    @else
        <button 
            type="button" 
            @click="sidebarOpen = false"
            class="rounded-lg p-1.5 text-slate-400 hover:text-white hover:bg-slate-900 transition cursor-pointer"
            title="Fechar menu lateral"
            aria-label="Fechar menu lateral"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>

{{-- Navigation Links with Active Scroll --}}
<div 
    class="flex flex-1 flex-col overflow-y-auto admin-sidebar-scroll px-3 py-4 space-y-5 transition-all duration-300"
    :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'items-center' : ''"
>
    {{-- Grupo 1: Painel & Métricas --}}
    <div class="w-full">
        <span 
            x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}"
            x-cloak
            class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block whitespace-nowrap"
        >
            Painel & Métricas
        </span>
        <div x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="w-8 mx-auto my-1 border-t border-slate-800/80"></div>

        <nav class="mt-2 space-y-1">
            <a 
                href="{{ route('admin.dashboard') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Dashboard' : ''"
                class="flex items-center rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'gap-3 px-3 py-2 w-full'"
            >
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Dashboard</span>
            </a>
        </nav>
    </div>

    {{-- Grupo 2: E-commerce & Catálogo --}}
    <div class="w-full">
        <span 
            x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}"
            x-cloak
            class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block whitespace-nowrap"
        >
            E-commerce & Catálogo
        </span>
        <div x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="w-8 mx-auto my-1 border-t border-slate-800/80"></div>

        <nav class="mt-2 space-y-1">
            {{-- Produtos --}}
            <a 
                href="{{ route('admin.products.index') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Produtos ({{ \App\Models\Product::count() }})' : ''"
                class="relative flex items-center rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.products.index') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'justify-between px-3 py-2 w-full'"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.products.index') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Produtos</span>
                </div>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="rounded-md bg-slate-900 px-1.5 py-0.5 text-[10px] font-mono text-slate-400 border border-slate-800 shrink-0">
                    {{ \App\Models\Product::count() }}
                </span>
                <span x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-teal-400"></span>
            </a>

            {{-- Categorias --}}
            <a 
                href="{{ route('admin.categories.index') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Categorias ({{ \App\Models\Category::count() }})' : ''"
                class="relative flex items-center rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.categories.*') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'justify-between px-3 py-2 w-full'"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Categorias</span>
                </div>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="rounded-md bg-slate-900 px-1.5 py-0.5 text-[10px] font-mono text-slate-400 border border-slate-800 shrink-0">
                    {{ \App\Models\Category::count() }}
                </span>
                <span x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-teal-400"></span>
            </a>

            {{-- Novo Produto --}}
            <a 
                href="{{ route('admin.products.create') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Novo Produto' : ''"
                class="flex items-center rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.products.create') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'gap-3 px-3 py-2 w-full'"
            >
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.products.create') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Novo Produto</span>
            </a>

            {{-- Pedidos --}}
            <a 
                href="{{ route('admin.orders.index') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Pedidos Pagos ({{ \App\Models\Order::where('status', 'paid')->count() }})' : ''"
                class="relative flex items-center rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.orders.index') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'justify-between px-3 py-2 w-full'"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.orders.index') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Pedidos</span>
                </div>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="rounded-md bg-slate-900 px-1.5 py-0.5 text-[10px] font-mono text-emerald-400 border border-slate-800 shrink-0">
                    {{ \App\Models\Order::where('status', 'paid')->count() }}
                </span>
                <span x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-emerald-400"></span>
            </a>
        </nav>
    </div>

    {{-- Grupo 3: Conteúdo & Blog --}}
    <div class="w-full">
        <span 
            x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}"
            x-cloak
            class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block whitespace-nowrap"
        >
            Conteúdo & Blog
        </span>
        <div x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="w-8 mx-auto my-1 border-t border-slate-800/80"></div>

        <nav class="mt-2 space-y-1">
            {{-- Artigos do Blog --}}
            <a 
                href="{{ route('admin.posts.index') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Artigos do Blog ({{ \App\Models\Post::count() }})' : ''"
                class="relative flex items-center rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.posts.index') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'justify-between px-3 py-2 w-full'"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.posts.index') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Artigos do Blog</span>
                </div>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="rounded-md bg-slate-900 px-1.5 py-0.5 text-[10px] font-mono text-slate-400 border border-slate-800 shrink-0">
                    {{ \App\Models\Post::count() }}
                </span>
                <span x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-teal-400"></span>
            </a>

            {{-- Escrever Artigo --}}
            <a 
                href="{{ route('admin.posts.create') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Escrever Artigo' : ''"
                class="flex items-center rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.posts.create') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'gap-3 px-3 py-2 w-full'"
            >
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.posts.create') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Escrever Artigo</span>
            </a>
        </nav>
    </div>

    {{-- Grupo 4: Navegação Pública & Perfil --}}
    <div class="w-full">
        <span 
            x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}"
            x-cloak
            class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block whitespace-nowrap"
        >
            Navegação & Perfil
        </span>
        <div x-show="!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed" class="w-8 mx-auto my-1 border-t border-slate-800/80"></div>

        <nav class="mt-2 space-y-1">
            <a 
                href="{{ route('storefront.index') }}" 
                target="_blank"
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Ver Loja Principal' : ''"
                class="flex items-center rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition group"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'justify-between px-3 py-2 w-full'"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="h-5 w-5 text-slate-400 group-hover:text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Ver Loja Principal</span>
                </div>
                <svg x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            <a 
                href="{{ route('blog.index') }}" 
                target="_blank"
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Ver Blog Público' : ''"
                class="flex items-center rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition group"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'justify-between px-3 py-2 w-full'"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="h-5 w-5 text-slate-400 group-hover:text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Ver Blog Público</span>
                </div>
                <svg x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            <a 
                href="{{ route('profile.edit') }}" 
                :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'Meu Perfil' : ''"
                class="flex items-center rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition group"
                :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center h-11 w-11 mx-auto p-0' : 'gap-3 px-3 py-2 w-full'"
            >
                <svg class="h-5 w-5 text-slate-400 group-hover:text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}" x-cloak class="truncate whitespace-nowrap">Meu Perfil</span>
            </a>
        </nav>
    </div>
</div>

{{-- Sidebar Footer / User Profile & Logout --}}
<div 
    class="shrink-0 border-t border-slate-800/80 bg-slate-950 p-3 transition-all duration-300"
    :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'flex flex-col items-center gap-2 p-2' : 'p-4'"
>
    <div 
        class="flex items-center transition-all duration-300"
        :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'flex-col gap-2' : 'justify-between w-full'"
    >
        <div 
            class="flex items-center gap-2.5 min-w-0"
            :class="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? 'justify-center' : ''"
            :title="(!{{ $isMobile ? 'true' : 'false' }} && sidebarCollapsed) ? '{{ auth()->user()->name ?? 'Administrador' }} ({{ auth()->user()->email ?? 'admin@kltecnologia.com' }})' : ''"
        >
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-slate-800 border border-slate-700 font-display text-xs font-bold text-teal-400 shadow-xs">
                {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
            </span>
            <div 
                class="min-w-0 flex-1 transition-opacity duration-200" 
                x-show="{{ $isMobile ? 'true' : '!sidebarCollapsed' }}"
                x-cloak
            >
                <p class="truncate text-xs font-bold text-white leading-snug">{{ auth()->user()->name ?? 'Administrador' }}</p>
                <p class="truncate text-[10px] text-slate-500 leading-snug">{{ auth()->user()->email ?? 'admin@kltecnologia.com' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    type="submit" 
                    class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-900 rounded-xl transition cursor-pointer"
                    title="Encerrar sessão"
                    aria-label="Encerrar sessão"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
