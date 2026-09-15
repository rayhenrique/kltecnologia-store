@if(auth()->check() && auth()->user()->isAdmin())
    @inject('changelogService', 'App\Services\ChangelogService')
    @php
        $user = auth()->user();
        $unseenRelease = $changelogService->getUnseenReleaseForUser($user);
        $allReleases = $changelogService->getAllReleasesForUser($user);
        $latestRelease = !empty($allReleases) ? reset($allReleases) : null;
        $currentVersion = $changelogService->getCurrentVersion();
        $displayRelease = $unseenRelease ?: $latestRelease;
    @endphp

    <div 
        x-data="{
            isOpen: {{ $unseenRelease ? 'true' : 'false' }},
            activeTab: 'release', // 'release' ou 'history'
            hasUnseen: {{ $unseenRelease ? 'true' : 'false' }},
            isDismissing: false,
            openModal(tab = 'release') {
                this.activeTab = tab;
                this.isOpen = true;
            },
            closeModal() {
                if (this.hasUnseen) {
                    this.dismiss();
                } else {
                    this.isOpen = false;
                }
            },
            dismiss() {
                if (this.isDismissing) return;
                this.isDismissing = true;

                fetch('{{ route('admin.changelog.dismiss') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        version: '{{ $displayRelease['version'] ?? $currentVersion }}'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.hasUnseen = false;
                    this.isOpen = false;
                })
                .catch(() => {
                    this.isOpen = false;
                })
                .finally(() => {
                    this.isDismissing = false;
                });
            }
        }"
        @open-changelog.window="openModal($event.detail?.tab || 'release')"
        x-cloak
    >
        {{-- Backdrop com Blur Suave --}}
        <div 
            x-show="isOpen"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm"
            @click="closeModal()"
            aria-hidden="true"
        ></div>

        {{-- Container Modal Centralizado --}}
        <div 
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            role="dialog"
            aria-modal="true"
        >
            <div 
                class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl bg-slate-900 border border-slate-800/90 shadow-2xl shadow-teal-950/40 text-slate-100 overflow-hidden"
                @click.stop
            >
                {{-- Glow Decorativo no Topo --}}
                <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-32 bg-teal-500/20 blur-3xl pointer-events-none rounded-full"></div>

                {{-- Header do Modal --}}
                <div class="relative flex items-center justify-between border-b border-slate-800/80 px-6 py-5 bg-slate-950/60">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 shrink-0">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-display text-lg font-bold tracking-tight text-white">
                                    O que há de novo na KL Tecnologia
                                </h2>
                                <span class="rounded-full bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 font-mono text-[11px] font-bold text-teal-400">
                                    v{{ $displayRelease['version'] ?? $currentVersion }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">
                                @if($displayRelease && !empty($displayRelease['date']))
                                    Lançado em {{ \Carbon\Carbon::parse($displayRelease['date'])->translatedFormat('d \d\e F \d\e Y') }}
                                @else
                                    Atualizações e novidades do sistema
                                @endif
                            </p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="closeModal()"
                        class="rounded-lg p-2 text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer"
                        title="Fechar janela"
                        aria-label="Fechar janela"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Navegação por Abas (Novidades vs Histórico) --}}
                <div class="flex border-b border-slate-800 bg-slate-950/40 px-6">
                    <button 
                        type="button"
                        @click="activeTab = 'release'"
                        class="py-3 px-3 text-xs font-semibold border-b-2 transition flex items-center gap-2 cursor-pointer"
                        :class="activeTab === 'release' ? 'border-teal-500 text-teal-400' : 'border-transparent text-slate-400 hover:text-slate-200'"
                    >
                        <span>Novidades da Versão</span>
                        <span class="rounded bg-teal-500/20 px-1.5 py-0.5 text-[10px] font-mono text-teal-300">
                            {{ count($displayRelease['changes'] ?? []) }}
                        </span>
                    </button>

                    <button 
                        type="button"
                        @click="activeTab = 'history'"
                        class="py-3 px-3 text-xs font-semibold border-b-2 transition flex items-center gap-2 cursor-pointer"
                        :class="activeTab === 'history' ? 'border-teal-500 text-teal-400' : 'border-transparent text-slate-400 hover:text-slate-200'"
                    >
                        <span>Histórico de Releases</span>
                        <span class="rounded bg-slate-800 px-1.5 py-0.5 text-[10px] font-mono text-slate-400">
                            {{ count($allReleases) }}
                        </span>
                    </button>
                </div>

                {{-- Corpo do Modal (Conteúdo Rolável) --}}
                <div class="flex-1 overflow-y-auto px-6 py-5 max-h-[55vh] space-y-4 text-sm scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">
                    {{-- Aba 1: Novidades da Release Atual --}}
                    <div x-show="activeTab === 'release'" class="space-y-4">
                        @if($displayRelease)
                            @if(!empty($displayRelease['title']))
                                <div class="p-3.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                                    <h3 class="font-display font-semibold text-white text-sm">
                                        {{ $displayRelease['title'] }}
                                    </h3>
                                    @if(!empty($displayRelease['summary']))
                                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                            {{ $displayRelease['summary'] }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <div class="space-y-3">
                                @forelse($displayRelease['changes'] ?? [] as $change)
                                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-950/40 border border-slate-800/60 hover:border-slate-700 transition">
                                        {{-- Badge de Categoria --}}
                                        <div class="shrink-0 mt-0.5">
                                            @if(($change['type'] ?? '') === 'feature')
                                                <span class="inline-flex items-center gap-1 rounded-md bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 text-[10px] font-bold text-teal-300 uppercase tracking-wider">
                                                    <span>✨</span> NOVO
                                                </span>
                                            @elseif(($change['type'] ?? '') === 'improvement')
                                                <span class="inline-flex items-center gap-1 rounded-md bg-sky-500/10 border border-sky-500/30 px-2 py-0.5 text-[10px] font-bold text-sky-300 uppercase tracking-wider">
                                                    <span>⚡</span> MELHORIA
                                                </span>
                                            @elseif(($change['type'] ?? '') === 'security')
                                                <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/10 border border-emerald-500/30 px-2 py-0.5 text-[10px] font-bold text-emerald-300 uppercase tracking-wider">
                                                    <span>🛡️</span> SEGURANÇA
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-md bg-amber-500/10 border border-amber-500/30 px-2 py-0.5 text-[10px] font-bold text-amber-300 uppercase tracking-wider">
                                                    <span>🐛</span> CORREÇÃO
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Conteúdo do Item --}}
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-slate-200 text-xs sm:text-sm">
                                                {{ $change['title'] ?? '' }}
                                            </h4>
                                            <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">
                                                {{ $change['description'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-xs text-slate-500 py-6">
                                        Nenhuma alteração registrada para esta versão.
                                    </p>
                                @endforelse
                            </div>
                        @else
                            <p class="text-center text-xs text-slate-500 py-8">
                                Você já está utilizando a versão mais recente da plataforma.
                            </p>
                        @endif
                    </div>

                    {{-- Aba 2: Histórico Completo de Releases --}}
                    <div x-show="activeTab === 'history'" class="space-y-4">
                        @forelse($allReleases as $releaseVersion => $releaseItem)
                            <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-bold text-teal-400 bg-teal-500/10 border border-teal-500/30 px-2 py-0.5 rounded">
                                            v{{ $releaseVersion }}
                                        </span>
                                        <h4 class="text-sm font-semibold text-white">
                                            {{ $releaseItem['title'] ?? 'Release '.$releaseVersion }}
                                        </h4>
                                    </div>
                                    <span class="text-[11px] text-slate-500 font-mono">
                                        {{ $releaseItem['date'] ?? '' }}
                                    </span>
                                </div>

                                @if(!empty($releaseItem['summary']))
                                    <p class="text-xs text-slate-400">
                                        {{ $releaseItem['summary'] }}
                                    </p>
                                @endif

                                <ul class="space-y-1.5 pt-2 border-t border-slate-800/60">
                                    @foreach($releaseItem['changes'] ?? [] as $histChange)
                                        <li class="flex items-start gap-2 text-xs text-slate-300">
                                            <span class="text-teal-400 font-bold shrink-0">•</span>
                                            <span>
                                                <strong class="text-slate-200">{{ $histChange['title'] ?? '' }}:</strong>
                                                <span class="text-slate-400">{{ $histChange['description'] ?? '' }}</span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-500 py-6">
                                Nenhum histórico de versão disponível no momento.
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Footer do Modal --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-800/80 bg-slate-950/80 px-6 py-4">
                    <div class="text-[11px] text-slate-500 flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                        <span>KL Tecnologia Store • v{{ $currentVersion }}</span>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <button 
                            type="button" 
                            @click="closeModal()"
                            :disabled="isDismissing"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-teal-700 hover:bg-teal-800 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-teal-900/30 transition cursor-pointer disabled:opacity-50"
                        >
                            <span x-show="!isDismissing">
                                <span x-show="hasUnseen">Entendi, vamos lá! →</span>
                                <span x-show="!hasUnseen">Fechar</span>
                            </span>
                            <span x-show="isDismissing" class="inline-flex items-center gap-1.5">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Salvando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
