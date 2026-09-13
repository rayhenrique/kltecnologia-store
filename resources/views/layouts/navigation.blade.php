<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white" aria-label="Navegação da conta">
    <a href="#conteudo" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2">Ir para o conteúdo</a>
    <div class="page-container">
        <div class="flex min-h-16 justify-between gap-4">
            <div class="flex items-center gap-8">
                <a href="{{ route('storefront.index') }}" class="flex items-center gap-2 font-display font-bold text-slate-900">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-600 text-sm text-white">KL</span>
                    <span class="hidden lg:block">KL Tecnologia</span>
                </a>
                <div class="hidden items-stretch gap-6 sm:flex">
                    <x-nav-link :href="route('storefront.index')" :active="request()->routeIs('storefront.*')">Vitrine</x-nav-link>
                    @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Painel</x-nav-link>
                        <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">Produtos</x-nav-link>
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">Pedidos</x-nav-link>
                    @else
                        <x-nav-link :href="route('customer.downloads')" :active="request()->routeIs('customer.*')">Meus downloads</x-nav-link>
                    @endif
                </div>
            </div>
            <div class="hidden items-center sm:flex">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger"><button type="button" class="inline-flex min-h-11 items-center gap-2 rounded-xl px-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900"><span>{{ Auth::user()->name }}</span><span aria-hidden="true">⌄</span></button></x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}" novalidate>@csrf<button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-slate-700 hover:bg-slate-100">Sair</button></form>
                    </x-slot>
                </x-dropdown>
            </div>
            <button type="button" @click="open = !open" class="my-auto grid h-11 w-11 place-items-center rounded-xl text-slate-600 hover:bg-slate-100 sm:hidden" :aria-expanded="open" aria-controls="mobile-menu" aria-label="Abrir menu"><span aria-hidden="true" class="text-xl">☰</span></button>
        </div>
    </div>
    <div id="mobile-menu" x-show="open" x-transition class="border-t border-slate-200 sm:hidden">
        <div class="space-y-1 px-4 py-3">
            <x-responsive-nav-link :href="route('storefront.index')">Vitrine</x-responsive-nav-link>
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')">Painel</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.products.index')">Produtos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')">Pedidos</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('customer.downloads')">Meus downloads</x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.edit')">Perfil</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}" novalidate>@csrf<button type="submit" class="block w-full rounded-lg px-3 py-2 text-start text-base font-medium text-slate-600 hover:bg-slate-50">Sair</button></form>
        </div>
    </div>
</nav>
