{{-- Sidebar Brand / Logo --}}
<div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-teal-400 to-blue-600 font-display font-bold text-white shadow-md shadow-teal-500/20 group-hover:scale-105 transition">
            KL
        </span>
        <div class="flex flex-col">
            <span class="font-display text-sm font-bold tracking-tight text-white group-hover:text-teal-400 transition leading-tight">
                KL<span class="text-teal-400">Tecnologia</span>
            </span>
            <span class="font-mono text-[10px] text-slate-500 uppercase tracking-widest font-semibold">Painel Admin</span>
        </div>
    </a>
    <span class="rounded bg-teal-500/10 border border-teal-500/30 px-1.5 py-0.5 font-mono text-[9px] font-bold text-teal-400 uppercase">
        v2.0
    </span>
</div>

{{-- Navigation Links --}}
<div class="flex flex-1 flex-col overflow-y-auto px-4 py-5 space-y-6">
    {{-- Grupo 1: Visão Geral --}}
    <div>
        <span class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
            Painel & Métricas
        </span>
        <nav class="mt-2 space-y-1">
            <a 
                href="{{ route('admin.dashboard') }}" 
                class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-semibold transition group {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>
        </nav>
    </div>

    {{-- Grupo 2: E-commerce --}}
    <div>
        <span class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
            E-commerce & Catálogo
        </span>
        <nav class="mt-2 space-y-1">
            <a 
                href="{{ route('admin.products.index') }}" 
                class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold transition group {{ request()->routeIs('admin.products.index') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.products.index') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Produtos</span>
                </div>
                <span class="rounded-md bg-slate-900 px-1.5 py-0.5 text-[10px] font-mono text-slate-400 border border-slate-800">
                    {{ \App\Models\Product::count() }}
                </span>
            </a>

            <a 
                href="{{ route('admin.products.create') }}" 
                class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-semibold transition group {{ request()->routeIs('admin.products.create') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.products.create') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Novo Produto</span>
            </a>

            <a 
                href="{{ route('admin.orders.index') }}" 
                class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold transition group {{ request()->routeIs('admin.orders.index') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.orders.index') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Pedidos</span>
                </div>
                <span class="rounded-md bg-slate-900 px-1.5 py-0.5 text-[10px] font-mono text-emerald-400 border border-slate-800">
                    {{ \App\Models\Order::where('status', 'paid')->count() }}
                </span>
            </a>
        </nav>
    </div>

    {{-- Grupo 3: Conteúdo / Blog --}}
    <div>
        <span class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
            Conteúdo & Blog
        </span>
        <nav class="mt-2 space-y-1">
            <a 
                href="{{ route('admin.posts.index') }}" 
                class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold transition group {{ request()->routeIs('admin.posts.index') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.posts.index') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span>Artigos do Blog</span>
                </div>
                <span class="rounded-md bg-slate-900 px-1.5 py-0.5 text-[10px] font-mono text-slate-400 border border-slate-800">
                    {{ \App\Models\Post::count() }}
                </span>
            </a>

            <a 
                href="{{ route('admin.posts.create') }}" 
                class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-semibold transition group {{ request()->routeIs('admin.posts.create') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30 font-bold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.posts.create') ? 'text-white' : 'text-slate-400 group-hover:text-teal-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Escrever Artigo</span>
            </a>
        </nav>
    </div>

    {{-- Grupo 4: Atalhos Externos --}}
    <div>
        <span class="px-3 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
            Navegação Pública
        </span>
        <nav class="mt-2 space-y-1">
            <a 
                href="{{ route('storefront.index') }}" 
                target="_blank"
                class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition group"
            >
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 text-slate-400 group-hover:text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <span>Ver Loja Principal</span>
                </div>
                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            <a 
                href="{{ route('blog.index') }}" 
                target="_blank"
                class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition group"
            >
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 text-slate-400 group-hover:text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Ver Blog Público</span>
                </div>
                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            <a 
                href="{{ route('profile.edit') }}" 
                class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition group"
            >
                <svg class="h-4 w-4 text-slate-400 group-hover:text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Meu Perfil</span>
            </a>
        </nav>
    </div>
</div>

{{-- Sidebar Footer / User Profile & Logout --}}
<div class="shrink-0 border-t border-slate-800/80 bg-slate-950 p-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-slate-800 border border-slate-700 font-display text-xs font-bold text-teal-400">
                {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-bold text-white">{{ auth()->user()->name ?? 'Administrador' }}</p>
                <p class="truncate text-[10px] text-slate-500">{{ auth()->user()->email ?? 'admin@kltecnologia.com' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-slate-900 rounded-lg transition"
                title="Encerrar sessão"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</div>
