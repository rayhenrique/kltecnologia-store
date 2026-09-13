<x-app-layout>
    <x-slot:title>Meu Perfil &bull; KL Tecnologia</x-slot:title>

    {{-- Hero Profile Banner (Dark SaaS) --}}
    <div class="relative overflow-hidden border-b border-slate-800 bg-slate-950 text-white py-8 sm:py-10">
        {{-- Ambient glowing effects --}}
        <div class="pointer-events-none absolute -top-24 right-1/4 h-80 w-80 rounded-full bg-teal-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/4 h-80 w-80 rounded-full bg-blue-600/10 blur-3xl"></div>

        <div class="page-container relative z-10">
            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-slate-400 mb-6">
                <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition flex items-center gap-1">
                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Vitrine</span>
                </a>
                <span class="text-slate-600">/</span>
                <span class="text-slate-400">Minha Conta</span>
                <span class="text-slate-600">/</span>
                <span class="text-teal-400 font-semibold">Meu Perfil</span>
            </nav>

            @php
                $nameParts = array_filter(explode(' ', trim($user->name)));
                if (count($nameParts) >= 2) {
                    $initials = mb_substr($nameParts[0], 0, 1) . mb_substr(end($nameParts), 0, 1);
                } else {
                    $initials = mb_substr($user->name, 0, 2);
                }
                $initials = strtoupper($initials);
                $paidOrdersCount = $user->orders()->where('status', \App\Enums\OrderStatus::Paid)->count();
                $totalOrdersCount = $user->orders()->count();
            @endphp

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                {{-- User Avatar & Info --}}
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-2xl bg-gradient-to-tr from-teal-500 via-teal-600 to-blue-600 flex items-center justify-center font-display text-xl sm:text-2xl font-bold text-white shadow-lg shadow-teal-500/25 ring-4 ring-slate-800/80 shrink-0">
                        {{ $initials }}
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="font-display text-xl sm:text-2xl lg:text-3xl font-bold text-white tracking-tight">
                                {{ $user->name }}
                            </h1>
                            @if ($user->isAdmin())
                                <span class="rounded-full bg-amber-400/15 border border-amber-400/30 px-2.5 py-0.5 font-mono text-[10px] font-black uppercase tracking-wider text-amber-300">
                                    Admin Master
                                </span>
                            @else
                                <span class="rounded-full bg-teal-500/15 border border-teal-500/30 px-2.5 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-300">
                                    Cliente VIP
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-400 font-mono">
                            <span class="flex items-center gap-1 text-slate-300">
                                <svg class="h-3.5 w-3.5 text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $user->email }}
                            </span>

                            @if ($user->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-1 text-emerald-400 font-medium">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    E-mail Verificado
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-amber-400 font-medium">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Verificação Pendente
                                </span>
                            @endif

                            <span class="hidden sm:inline text-slate-600">•</span>
                            <span class="hidden sm:inline text-slate-500 font-sans">
                                Cadastrado em {{ $user->created_at?->format('d/m/Y') ?? 'Recente' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Action Shortcuts --}}
                <div class="flex flex-wrap items-center gap-2.5 pt-2 md:pt-0">
                    <a 
                        href="{{ route('customer.downloads') }}" 
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 border border-slate-700/80 px-4 py-2.5 text-xs font-bold text-slate-200 hover:bg-slate-800 hover:text-white hover:border-slate-600 transition shadow-xs"
                    >
                        <svg class="h-4 w-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Meus Downloads ({{ $paidOrdersCount }})</span>
                    </a>

                    @if ($user->isAdmin())
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2.5 text-xs font-bold text-white transition shadow-sm shadow-teal-600/20"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Painel Admin</span>
                        </a>
                    @else
                        <a 
                            href="{{ route('storefront.index') }}#produtos" 
                            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2.5 text-xs font-bold text-white transition shadow-sm shadow-teal-600/20"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>Explorar Catálogo</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Main Profile Content Grid --}}
    <div class="page-container py-8 sm:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Coluna Principal: Formulários de Dados e Senha --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- Card 1: Informações Pessoais & Faturamento --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-xs hover:border-slate-300 transition duration-150">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Card 2: Alteração de Senha --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-xs hover:border-slate-300 transition duration-150">
                    @include('profile.partials.update-password-form')
                </div>

            </div>

            {{-- Coluna Lateral: Resumo, Segurança e Zona de Risco --}}
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                {{-- Card Resumo da Conta --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <h3 class="font-display text-sm font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Status da Conta</span>
                        </h3>
                        <span class="inline-flex items-center gap-1 text-[11px] font-mono font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Ativa
                        </span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500">Total de Pedidos</span>
                            <span class="font-mono font-bold text-slate-900 text-sm">{{ $totalOrdersCount }}</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-teal-50/50 border border-teal-100">
                            <span class="text-teal-800 font-medium">Downloads Disponíveis</span>
                            <span class="font-mono font-bold text-teal-700 text-sm">{{ $paidOrdersCount }}</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500">Documento Fiscal (CPF)</span>
                            @if ($user->cpf)
                                <span class="font-mono text-emerald-600 font-semibold">Cadastrado</span>
                            @else
                                <span class="font-mono text-amber-600 font-semibold">Pendente</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500">WhatsApp de Suporte</span>
                            @if ($user->phone)
                                <span class="font-mono text-emerald-600 font-semibold">Vinculado</span>
                            @else
                                <span class="font-mono text-slate-400">Não informado</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <a 
                            href="{{ route('customer.downloads') }}" 
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 py-2.5 px-4 text-xs font-bold transition"
                        >
                            <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Ir para Minha Biblioteca</span>
                        </a>
                    </div>
                </div>

                {{-- Card Segurança & Garantias --}}
                <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 text-white rounded-2xl border border-slate-800 p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-2.5 mb-3 text-teal-400">
                        <span class="rounded-lg bg-teal-500/10 border border-teal-500/20 p-1.5 text-teal-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <h4 class="font-display text-xs font-bold uppercase tracking-wider text-slate-200">
                            Privacidade & Proteção
                        </h4>
                    </div>

                    <ul class="space-y-2.5 text-xs text-slate-300">
                        <li class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong>Criptografia SSL:</strong> Toda a sua navegação e dados trafegam de forma cifrada em 256 bits.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong>Conformidade LGPD:</strong> Seus dados pessoais e fiscais são utilizados estritamente para faturamento.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong>Mercado Pago:</strong> Processamento de pagamentos com segurança antifraude líder de mercado.</span>
                        </li>
                    </ul>
                </div>

                {{-- Card Zona de Risco: Exclusão de Conta --}}
                <div class="bg-white rounded-2xl border border-red-200/80 p-5 sm:p-6 shadow-xs">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
