<x-admin-layout title="Inscritos da Newsletter">
    <div class="space-y-6" x-data="{
        copyAllEmails(emails) {
            if (!emails || emails.length === 0) return;
            navigator.clipboard.writeText(emails.join('\n')).then(() => {
                alert('Lista com ' + emails.length + ' e-mail(s) copiada para a área de transferência!');
            });
        }
    }">
        {{-- Header da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="rounded bg-cyan-50 border border-cyan-200/80 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-cyan-700">
                        Marketing & Leads
                    </span>
                    <span class="font-mono text-xs text-slate-500 font-semibold">
                        {{ $metrics['total'] }} {{ $metrics['total'] === 1 ? 'e-mail capturado' : 'e-mails capturados' }}
                    </span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Newsletter & Leads
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Lista de e-mails inscritos na loja para avisos de lançamentos, ofertas e materiais exclusivos.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Botão Copiar E-mails da Página --}}
                @php($pageEmails = $subscribers->pluck('email')->toJson())
                <button 
                    type="button" 
                    @click="copyAllEmails({{ $pageEmails }})" 
                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 px-3.5 py-2 text-xs font-bold text-slate-700 shadow-2xs transition cursor-pointer"
                    title="Copiar e-mails visíveis nesta página"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>Copiar E-mails da Página</span>
                </button>

                {{-- Botão Exportar CSV --}}
                <a 
                    href="{{ route('admin.newsletter.export', ['status' => request('status')]) }}" 
                    class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-teal-600/20 transition transform active:scale-95 cursor-pointer"
                    title="Exportar todos os e-mails para arquivo CSV (Excel / Google Sheets)"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Exportar Lista (CSV)</span>
                </a>
            </div>
        </div>

        {{-- Cards de Métricas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total de Inscritos</span>
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-slate-100 text-slate-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-display text-2xl font-bold text-slate-900 font-mono">{{ $metrics['total'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Capturados desde o início</p>
            </div>

            {{-- Card 2: Ativos --}}
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/30 p-4 sm:p-5 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-700">Leads Ativos</span>
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-emerald-100 text-emerald-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-display text-2xl font-bold text-emerald-800 font-mono">{{ $metrics['active'] }}</p>
                <p class="text-[11px] text-emerald-600/80 mt-0.5">Prontos para campanhas</p>
            </div>

            {{-- Card 3: Este Mês --}}
            <div class="rounded-2xl border border-teal-100 bg-teal-50/30 p-4 sm:p-5 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-teal-700">Inscritos este Mês</span>
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-teal-100 text-teal-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-display text-2xl font-bold text-teal-800 font-mono">{{ $metrics['this_month'] }}</p>
                <p class="text-[11px] text-teal-600/80 mt-0.5">Crescimento mensal</p>
            </div>

            {{-- Card 4: Hoje --}}
            <div class="rounded-2xl border border-cyan-100 bg-cyan-50/30 p-4 sm:p-5 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-cyan-700">Novos Hoje</span>
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-cyan-100 text-cyan-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-display text-2xl font-bold text-cyan-800 font-mono">{{ $metrics['today'] }}</p>
                <p class="text-[11px] text-cyan-600/80 mt-0.5">Últimas 24 horas</p>
            </div>
        </div>

        {{-- Filtros & Busca --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
            <form action="{{ route('admin.newsletter.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="relative flex-1 w-full">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $search }}" 
                        placeholder="Buscar por endereço de e-mail..." 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-9 pr-4 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:ring-1 focus:ring-teal-500"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select 
                        name="status" 
                        onchange="this.form.submit()" 
                        class="rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-semibold text-slate-700 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 w-full sm:w-auto"
                    >
                        <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Todos os Status</option>
                        <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Somente Ativos</option>
                        <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inativos / Descadastrados</option>
                    </select>

                    @if($search || $currentStatus !== 'all')
                        <a 
                            href="{{ route('admin.newsletter.index') }}" 
                            class="rounded-xl border border-slate-200 p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition shrink-0"
                            title="Limpar Filtros"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabela de Inscritos --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">E-mail Cadastrado</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Data & Horário</th>
                            <th class="py-3.5 px-4">IP de Origem</th>
                            <th class="py-3.5 px-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($subscribers as $subscriber)
                            <tr class="hover:bg-slate-50/70 transition group">
                                {{-- Email --}}
                                <td class="py-3.5 px-4 sm:px-6 font-medium text-slate-900">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs sm:text-sm font-semibold text-slate-800">
                                            {{ $subscriber->email }}
                                        </span>
                                        <button 
                                            type="button" 
                                            onclick="navigator.clipboard.writeText('{{ $subscriber->email }}'); alert('E-mail copiado!');"
                                            class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-teal-600 p-1 rounded hover:bg-slate-100 transition cursor-pointer"
                                            title="Copiar e-mail"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="py-3.5 px-4">
                                    @if($subscriber->is_active)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 font-semibold text-emerald-700 border border-emerald-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 font-semibold text-slate-600 border border-slate-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            Inativo
                                        </span>
                                    @endif
                                </td>

                                {{-- Data & Horário --}}
                                <td class="py-3.5 px-4 text-slate-600">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-800">
                                            {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y') : $subscriber->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">
                                            {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('H:i:s') : $subscriber->created_at->format('H:i:s') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- IP --}}
                                <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                    {{ $subscriber->ip_address ?? '—' }}
                                </td>

                                {{-- Ações --}}
                                <td class="py-3.5 px-4 text-right">
                                    <form 
                                        action="{{ route('admin.newsletter.destroy', $subscriber) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Deseja realmente remover a inscrição de {{ $subscriber->email }}?');"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="rounded-lg p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Remover Inscrição"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500">
                                    <div class="mx-auto max-w-sm">
                                        <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400 mb-3">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="font-bold text-slate-700">Nenhum e-mail encontrado</p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            @if($search || $currentStatus !== 'all')
                                                Nenhum inscrito corresponde aos filtros aplicados.
                                            @else
                                                Ainda não há e-mails inscritos na newsletter da loja.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if($subscribers->hasPages())
                <div class="border-t border-slate-200 bg-slate-50/50 p-4">
                    {{ $subscribers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
