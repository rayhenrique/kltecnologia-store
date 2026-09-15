<x-admin-layout title="Inscrito: {{ $subscriber->email }}">
    <div class="space-y-6" x-data="{
        copyToClipboard(text, msg) {
            navigator.clipboard.writeText(text).then(() => {
                alert(msg || 'Copiado para a área de transferência!');
            });
        }
    }">
        {{-- Breadcrumb & Header --}}
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('admin.newsletter.index') }}" class="hover:text-teal-600 transition">Newsletter</a>
                <span>/</span>
                <span class="text-teal-600 font-mono">{{ $subscriber->email }}</span>
            </nav>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-cyan-100 text-cyan-700 text-xl font-bold shrink-0">
                        📬
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="font-display text-xl sm:text-2xl font-bold tracking-tight text-slate-900 font-mono">
                                {{ $subscriber->email }}
                            </h1>
                            @if($subscriber->is_active)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-600 border border-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Inativo / Descadastrado
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Inscrito em {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y \à\s H:i') : $subscriber->created_at->format('d/m/Y \à\s H:i') }}
                            ({{ ($subscriber->subscribed_at ?? $subscriber->created_at)->diffForHumans() }})
                        </p>
                    </div>
                </div>

                {{-- Ações Rápidas do Cabeçalho --}}
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Alternar Status --}}
                    <form action="{{ route('admin.newsletter.toggle-status', $subscriber) }}" method="POST" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-1.5 rounded-xl border {{ $subscriber->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} px-3.5 py-2 text-xs font-bold transition cursor-pointer shadow-2xs"
                            title="{{ $subscriber->is_active ? 'Desativar recebimento' : 'Ativar recebimento' }}"
                        >
                            <span>{{ $subscriber->is_active ? '⏸️ Pausar Inscrição' : '▶️ Reativar Inscrição' }}</span>
                        </button>
                    </form>

                    {{-- Enviar E-mail --}}
                    <a 
                        href="mailto:{{ $subscriber->email }}" 
                        class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                        title="Enviar e-mail para este contato"
                    >
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Escrever E-mail</span>
                    </a>

                    {{-- Editar --}}
                    <a 
                        href="{{ route('admin.newsletter.edit', $subscriber) }}" 
                        class="btn-secondary text-xs !min-h-10 !px-3.5 flex items-center gap-1.5 shadow-2xs"
                    >
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Editar</span>
                    </a>

                    {{-- Excluir --}}
                    <form 
                        action="{{ route('admin.newsletter.destroy', $subscriber) }}" 
                        method="POST" 
                        onsubmit="return confirm('Deseja realmente remover esta inscrição? Esta ação não pode ser desfeita.');"
                        class="inline"
                    >
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 px-3.5 py-2 text-xs font-bold transition cursor-pointer shadow-2xs"
                            title="Remover definitivamente"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Excluir</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Grid Principal: Detalhes & Histórico --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            {{-- Coluna da Esquerda: Metadados & LGPD (1 Col) --}}
            <div class="space-y-6">
                {{-- Card de Dados Técnicos --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Dados da Inscrição</span>
                        <span class="font-mono text-[11px] text-slate-400">ID #{{ $subscriber->id }}</span>
                    </h3>

                    <dl class="mt-4 space-y-3.5 text-xs">
                        <div>
                            <dt class="font-medium text-slate-400">Endereço de E-mail</dt>
                            <dd class="mt-0.5 font-mono font-semibold text-slate-900 break-all">{{ $subscriber->email }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-slate-400">Status de Envio</dt>
                            <dd class="mt-0.5">
                                @if($subscriber->is_active)
                                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700">
                                        🟢 Habilitado para receber e-mails
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-semibold text-slate-600">
                                        ⚪ Desativado / Bloqueado
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-slate-400">Data de Inscrição</dt>
                            <dd class="mt-0.5 text-slate-700">
                                {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y H:i:s') : 'N/A' }}
                            </dd>
                        </div>
                        @if($subscriber->unsubscribed_at)
                            <div>
                                <dt class="font-medium text-rose-500">Data de Descadastro (Opt-out)</dt>
                                <dd class="mt-0.5 font-semibold text-rose-700">
                                    {{ $subscriber->unsubscribed_at->format('d/m/Y H:i:s') }}
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="font-medium text-slate-400">IP de Origem</dt>
                            <dd class="mt-0.5 font-mono text-slate-700">{{ $subscriber->ip_address ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-slate-400">User Agent / Origem</dt>
                            <dd class="mt-0.5 text-slate-600 text-[11px] leading-relaxed break-words bg-slate-50 p-2 rounded-lg border border-slate-100">
                                {{ $subscriber->user_agent ?? 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Card de Vínculo com Cliente da Loja --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Vínculo com Cliente</span>
                        @if($subscriber->user)
                            <span class="rounded bg-teal-50 text-teal-700 font-bold px-2 py-0.5 text-[10px]">Cadastrado</span>
                        @else
                            <span class="rounded bg-slate-100 text-slate-600 font-medium px-2 py-0.5 text-[10px]">Apenas Lead</span>
                        @endif
                    </h3>

                    @if($subscriber->user)
                        <div class="mt-4 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-teal-500 text-white font-bold grid place-items-center text-sm shadow-xs shrink-0">
                                {{ strtoupper(substr($subscriber->user->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $subscriber->user->name }}</p>
                                <p class="text-xs text-slate-500">Cliente na KL Tecnologia</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <a 
                                href="{{ route('admin.customers.show', $subscriber->user) }}" 
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-600 hover:text-teal-700 transition"
                            >
                                <span>Ver Histórico Completo do Cliente</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    @else
                        <div class="mt-4 text-xs text-slate-500 leading-relaxed">
                            <p>Este endereço de e-mail ainda não possui cadastro ou compras de usuário associadas na loja.</p>
                        </div>
                    @endif
                </div>

                {{-- Card de Link de Cancelamento (Opt-out) --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 pb-3 border-b border-slate-100">
                        Link de Cancelamento (Opt-out)
                    </h3>
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                        URL assinada exclusiva gerada para este e-mail para testes ou atendimento direto de suporte:
                    </p>
                    <div class="mt-3 flex items-center gap-1.5">
                        <input 
                            type="text" 
                            readonly 
                            value="{{ $subscriber->unsubscribe_url }}" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] font-mono text-slate-600 truncate focus:outline-none"
                        />
                        <button 
                            type="button" 
                            @click="copyToClipboard('{{ $subscriber->unsubscribe_url }}', 'Link de cancelamento copiado!')"
                            class="shrink-0 rounded-xl bg-slate-100 hover:bg-slate-200 p-2 text-slate-600 transition cursor-pointer"
                            title="Copiar Link"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita: Histórico de Disparos Recebidos (2 Cols) --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-xs overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-display text-base font-bold text-slate-900">
                                Histórico de Notificações Recebidas
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Disparos automáticos de novos lançamentos de produtos e artigos enviados a este inscrito.
                            </p>
                        </div>
                        <span class="rounded-lg bg-cyan-50 border border-cyan-200/80 px-2.5 py-1 text-xs font-mono font-bold text-cyan-700">
                            {{ $subscriber->sendLogs->count() }} {{ $subscriber->sendLogs->count() === 1 ? 'envio' : 'envios' }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3 px-4 sm:px-6">Campanha / Notificação</th>
                                    <th class="py-3 px-4">Tipo</th>
                                    <th class="py-3 px-4">Data do Envio</th>
                                    <th class="py-3 px-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($subscriber->sendLogs as $log)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        {{-- Campanha --}}
                                        <td class="py-3.5 px-4 sm:px-6 font-medium text-slate-900">
                                            @if($log->notifiable)
                                                <div class="flex items-center gap-2">
                                                    @if($log->notifiable instanceof \App\Models\Product)
                                                        <span class="text-teal-600">📦</span>
                                                        <span class="font-semibold text-slate-800">{{ $log->notifiable->title }}</span>
                                                    @elseif($log->notifiable instanceof \App\Models\Post)
                                                        <span class="text-indigo-600">📝</span>
                                                        <span class="font-semibold text-slate-800">{{ $log->notifiable->title }}</span>
                                                    @else
                                                        <span>{{ $log->notifiable->title ?? 'Item' }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-slate-400 italic">Item removido (#{{ $log->notifiable_id }})</span>
                                            @endif
                                        </td>

                                        {{-- Tipo --}}
                                        <td class="py-3.5 px-4">
                                            @if($log->notifiable_type === \App\Models\Product::class)
                                                <span class="rounded bg-teal-50 border border-teal-200 px-2 py-0.5 text-[10px] font-bold font-mono text-teal-700">
                                                    Novo Produto
                                                </span>
                                            @elseif($log->notifiable_type === \App\Models\Post::class)
                                                <span class="rounded bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold font-mono text-indigo-700">
                                                    Novo Artigo
                                                </span>
                                            @else
                                                <span class="text-slate-400">{{ class_basename($log->notifiable_type) }}</span>
                                            @endif
                                        </td>

                                        {{-- Data --}}
                                        <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                                            {{ $log->sent_at ? $log->sent_at->format('d/m/Y H:i') : $log->created_at->format('d/m/Y H:i') }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="py-3.5 px-4 text-right">
                                            @if($log->status === 'sent')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Enviado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-200" title="{{ $log->error_message }}">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                    {{ ucfirst($log->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-slate-400">
                                            <div class="mx-auto max-w-sm">
                                                <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400 mb-3 text-xl">
                                                    📭
                                                </div>
                                                <p class="font-bold text-slate-700">Nenhum envio registrado</p>
                                                <p class="text-xs text-slate-400 mt-1">
                                                    Este inscrito ainda não recebeu notificações de novos produtos ou artigos.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
