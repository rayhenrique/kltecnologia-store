@csrf

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        {{-- E-mail do Inscrito --}}
        <div class="sm:col-span-2">
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Endereço de E-mail <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-2xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', $subscriber->email ?? '') }}" 
                    required 
                    autofocus
                    maxlength="255"
                    placeholder="cliente@exemplo.com"
                    class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
                />
            </div>
            @error('email')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
            <p class="mt-1.5 text-[11px] text-slate-400">
                O e-mail receberá notificações automáticas em segundo plano quando novos produtos ou artigos forem publicados.
            </p>
        </div>

        {{-- Status da Inscrição --}}
        <div>
            <label for="is_active" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Status do Lead / Inscrição <span class="text-rose-500">*</span>
            </label>
            <select 
                name="is_active" 
                id="is_active" 
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition @error('is_active') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            >
                <option value="1" {{ old('is_active', isset($subscriber) ? ($subscriber->is_active ? '1' : '0') : '1') === '1' ? 'selected' : '' }}>
                    🟢 Ativo (Apto a receber newsletters)
                </option>
                <option value="0" {{ old('is_active', isset($subscriber) ? ($subscriber->is_active ? '1' : '0') : '1') === '0' ? 'selected' : '' }}>
                    ⚪ Inativo / Descadastrado (Bloqueia envios)
                </option>
            </select>
            @error('is_active')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Data de Inscrição --}}
        <div>
            <label for="subscribed_at" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Data e Horário de Inscrição
            </label>
            <input 
                type="datetime-local" 
                name="subscribed_at" 
                id="subscribed_at" 
                value="{{ old('subscribed_at', isset($subscriber) && $subscriber->subscribed_at ? $subscriber->subscribed_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" 
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition @error('subscribed_at') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            />
            @error('subscribed_at')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
            <p class="mt-1.5 text-[11px] text-slate-400">
                Se deixado em branco ou inalterado, registrará o momento atual.
            </p>
        </div>
    </div>

    {{-- Box Informativo sobre Regras e LGPD --}}
    <div class="rounded-2xl border border-cyan-100 bg-cyan-50/50 p-4 text-xs text-cyan-900">
        <div class="flex items-start gap-3">
            <span class="text-base text-cyan-600 shrink-0">🛡️</span>
            <div>
                <p class="font-bold text-cyan-950">Conformidade com LGPD & Política Anti-Spam</p>
                <p class="mt-0.5 text-cyan-800 leading-relaxed">
                    Todo e-mail cadastrado na newsletter recebe em cada mensagem enviada um link individual e assinado digitalmente para cancelamento imediato de inscrição (opt-out em 1 clique). O sistema gerencia a fila automaticamente respeitando o limite seguro diário configurado.
                </p>
            </div>
        </div>
    </div>

    {{-- Ações do Formulário --}}
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
        <a 
            href="{{ isset($subscriber) ? route('admin.newsletter.show', $subscriber) : route('admin.newsletter.index') }}" 
            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs cursor-pointer"
        >
            Cancelar
        </a>
        <button 
            type="submit" 
            class="rounded-xl bg-teal-600 hover:bg-teal-500 px-5 py-2.5 text-xs font-bold text-white shadow-sm shadow-teal-600/30 transition transform active:scale-95 cursor-pointer flex items-center gap-1.5"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ isset($subscriber) ? 'Salvar Alterações' : 'Cadastrar Inscrito' }}</span>
        </button>
    </div>
</div>
