<x-admin-layout title="Novidades & Versões">
    <div class="space-y-6">
        {{-- Header & Ações --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-teal-50 border border-teal-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-teal-700">
                        Controle de Versões & Changelog
                    </span>
                    <span class="font-mono text-xs text-slate-400 font-semibold">
                        {{ $totalReleases }} releases registradas
                    </span>
                </div>
                <h1 class="mt-1.5 font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Novidades & Histórico de Lançamentos
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Acompanhe o registro oficial de novas funcionalidades, melhorias de UX, segurança e correções implementadas na KL Tecnologia.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Botão para disparar o modal interativo --}}
                <button 
                    type="button" 
                    @click="$dispatch('open-changelog')"
                    class="btn-primary !bg-teal-700 hover:!bg-teal-800 text-xs !min-h-10 !px-4 flex items-center gap-2 shadow-sm shadow-teal-700/20 cursor-pointer"
                    title="Visualizar o modal interativo exibido aos usuários"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Pré-visualizar Modal</span>
                </button>

                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar ao Dashboard</span>
                </a>
            </div>
        </div>

        {{-- 4 Cards de Métricas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Versão Atual --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Versão em Produção</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-50 text-teal-600 border border-teal-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="font-mono text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        v{{ $currentVersion }}
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 text-[11px] font-bold text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Estável
                    </span>
                </div>
            </div>

            {{-- Card 2: Total de Releases --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Releases Publicadas</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-50 text-sky-600 border border-sky-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        {{ $totalReleases }}
                    </span>
                    <span class="text-xs text-slate-500 ml-1">versões no total</span>
                </div>
            </div>

            {{-- Card 3: Total de Atualizações Registradas --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mudanças Registradas</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        {{ $totalChanges }}
                    </span>
                    <span class="text-xs text-slate-500 ml-1">itens detalhados</span>
                </div>
            </div>

            {{-- Card 4: Último Lançamento --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Último Lançamento</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="font-display text-base sm:text-lg font-bold tracking-tight text-slate-900">
                        @if($latestReleaseDate)
                            {{ \Carbon\Carbon::parse($latestReleaseDate)->translatedFormat('d \d\e M, Y') }}
                        @else
                            Recente
                        @endif
                    </span>
                    <p class="text-[11px] text-slate-500 mt-0.5">Versão {{ $currentVersion }}</p>
                </div>
            </div>
        </div>

        {{-- Linha do Tempo de Releases (Timeline) --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-lg font-bold tracking-tight text-slate-900 flex items-center gap-2">
                    <span>Linha do Tempo de Lançamentos</span>
                    <span class="text-xs font-mono font-normal text-slate-500">({{ count($releases) }} releases)</span>
                </h2>
                <span class="text-xs text-slate-400 font-mono">Ordenado por SemVer Decrescente</span>
            </div>

            <div class="space-y-6 relative before:absolute before:inset-0 before:left-5 sm:before:left-6 before:w-0.5 before:bg-slate-200 before:z-0">
                @forelse($releases as $version => $release)
                    <article class="relative z-10 pl-12 sm:pl-14">
                        {{-- Marcador Circular na Linha do Tempo --}}
                        <div class="absolute left-2.5 sm:left-3.5 top-5 -translate-x-1/2 flex h-6 w-6 items-center justify-center rounded-full bg-white border-2 {{ $loop->first ? 'border-teal-500 shadow-sm shadow-teal-500/40' : 'border-slate-400' }}">
                            <div class="h-2 w-2 rounded-full {{ $loop->first ? 'bg-teal-500 animate-pulse' : 'bg-slate-400' }}"></div>
                        </div>

                        {{-- Card da Release --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:border-teal-300/80 hover:shadow-md transition">
                            {{-- Topo do Card --}}
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-4">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="rounded-xl font-mono text-sm sm:text-base font-extrabold px-3 py-1 shadow-2xs {{ $loop->first ? 'bg-teal-700 text-white shadow-teal-700/20' : 'bg-slate-900 text-white' }}">
                                        v{{ $version }}
                                    </span>
                                    @if($loop->first)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-teal-50 border border-teal-200/80 px-2 py-0.5 text-[11px] font-bold text-teal-700 uppercase tracking-wider">
                                            <span>★</span> Versão Atual
                                        </span>
                                    @endif

                                    {{-- Badge de Público --}}
                                    @php($aud = $release['audience'] ?? 'all')
                                    @if($aud === 'admin')
                                        <span class="rounded-md bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[11px] font-bold text-indigo-700">
                                            Admin
                                        </span>
                                    @elseif($aud === 'customer')
                                        <span class="rounded-md bg-amber-50 border border-amber-200 px-2 py-0.5 text-[11px] font-bold text-amber-700">
                                            Clientes
                                        </span>
                                    @else
                                        <span class="rounded-md bg-slate-100 border border-slate-200 px-2 py-0.5 text-[11px] font-bold text-slate-700">
                                            Geral (Todos)
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs font-mono text-slate-500 flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ !empty($release['date']) ? \Carbon\Carbon::parse($release['date'])->translatedFormat('d \d\e F \d\e Y') : 'Data não informada' }}</span>
                                </div>
                            </div>

                            {{-- Título & Resumo --}}
                            <div class="mt-4">
                                <h3 class="font-display text-lg font-bold text-slate-900">
                                    {{ $release['title'] ?? 'Release '.$version }}
                                </h3>
                                @if(!empty($release['summary']))
                                    <p class="mt-1.5 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                        {{ $release['summary'] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Lista de Mudanças --}}
                            <div class="mt-5 space-y-3">
                                <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                    <span>Alterações Desta Versão</span>
                                    <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] text-slate-600 font-mono">
                                        {{ count($release['changes'] ?? []) }}
                                    </span>
                                </h4>

                                <div class="grid gap-2.5 sm:grid-cols-2">
                                    @forelse($release['changes'] ?? [] as $change)
                                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3.5 flex items-start gap-3 hover:bg-slate-50 transition">
                                            {{-- Badge de Categoria --}}
                                            <div class="shrink-0 mt-0.5">
                                                @if(($change['type'] ?? '') === 'feature')
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 text-[10px] font-bold text-teal-800 uppercase tracking-wider">
                                                        <span>✨</span> NOVO
                                                    </span>
                                                @elseif(($change['type'] ?? '') === 'improvement')
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-sky-500/10 border border-sky-500/30 px-2 py-0.5 text-[10px] font-bold text-sky-800 uppercase tracking-wider">
                                                        <span>⚡</span> MELHORIA
                                                    </span>
                                                @elseif(($change['type'] ?? '') === 'security')
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/10 border border-emerald-500/30 px-2 py-0.5 text-[10px] font-bold text-emerald-800 uppercase tracking-wider">
                                                        <span>🛡️</span> SEGURANÇA
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-amber-500/10 border border-amber-500/30 px-2 py-0.5 text-[10px] font-bold text-amber-800 uppercase tracking-wider">
                                                        <span>🐛</span> CORREÇÃO
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-1">
                                                    <h5 class="text-xs font-bold text-slate-800">
                                                        {{ $change['title'] ?? '' }}
                                                    </h5>
                                                    @if(!empty($change['audience']) && $change['audience'] !== 'all')
                                                        <span class="text-[9px] font-mono font-semibold px-1 rounded bg-slate-200 text-slate-600">
                                                            {{ strtoupper($change['audience']) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                                    {{ $change['description'] ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-500 py-3">Nenhuma alteração detalhada nesta versão.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center">
                        <p class="text-sm text-slate-500">Nenhuma versão cadastrada no changelog.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
