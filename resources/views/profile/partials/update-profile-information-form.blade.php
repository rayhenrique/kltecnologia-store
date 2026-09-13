<section>
    <div class="border-b border-slate-100 pb-4 mb-6">
        <div class="flex items-center gap-2 mb-1">
            <span class="rounded-lg bg-teal-50 border border-teal-200/80 p-1.5 text-teal-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </span>
            <h2 class="font-display text-base sm:text-lg font-bold text-slate-900">
                Dados Pessoais & Faturamento
            </h2>
        </div>
        <p class="text-xs text-slate-500">
            Atualize suas informações cadastrais, e-mail e dados necessários para emissão de pedidos e checkout sem atrito.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" novalidate>
        @csrf
    </form>

    <form 
        method="post" 
        action="{{ route('profile.update') }}" 
        class="space-y-5" 
        novalidate
        x-data="{ submitting: false }"
        x-on:submit="submitting = true"
    >
        @csrf
        @method('patch')

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- Nome Completo --}}
            <div>
                <x-input-label for="name" value="Nome Completo *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus 
                        autocomplete="name" 
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs transition"
                        placeholder="Seu nome completo"
                    />
                </div>
                <x-input-error class="mt-1.5 text-xs text-red-500" :messages="$errors->get('name')" />
            </div>

            {{-- E-mail --}}
            <div>
                <x-input-label for="email" value="Endereço de E-mail *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        autocomplete="username" 
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs transition font-mono"
                        placeholder="seu.email@exemplo.com"
                    />
                </div>
                <x-input-error class="mt-1.5 text-xs text-red-500" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 rounded-lg bg-amber-50 border border-amber-200 p-2.5 text-xs text-amber-800">
                        <p class="font-medium">Seu endereço de e-mail ainda não foi verificado.</p>
                        <button 
                            form="send-verification" 
                            class="mt-1 text-teal-700 font-bold underline hover:text-teal-900 cursor-pointer"
                        >
                            Clique aqui para reenviar o e-mail de verificação.
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 font-bold text-emerald-700">
                                Um novo link de verificação foi enviado para o seu e-mail.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 pt-1">
            {{-- CPF --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <x-input-label for="cpf" value="CPF (Documento Fiscal)" class="text-xs font-bold uppercase text-slate-700" />
                    <span class="text-[10px] font-mono text-teal-700 font-semibold">Mercado Pago</span>
                </div>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </div>
                    <input 
                        id="cpf" 
                        name="cpf" 
                        type="text" 
                        value="{{ old('cpf', $user->cpf) }}" 
                        inputmode="numeric"
                        maxlength="14"
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs transition font-mono" 
                        placeholder="000.000.000-00" 
                        autocomplete="off" 
                    />
                </div>
                <p class="mt-1 text-[11px] text-slate-400">Utilizado para aprovação Pix e antifraude do gateway.</p>
                <x-input-error class="mt-1.5 text-xs text-red-500" :messages="$errors->get('cpf')" />
            </div>

            {{-- WhatsApp / Telefone --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <x-input-label for="phone" value="WhatsApp / Celular com DDD" class="text-xs font-bold uppercase text-slate-700" />
                    <span class="text-[10px] font-mono text-emerald-700 font-semibold">Suporte Direto</span>
                </div>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input 
                        id="phone" 
                        name="phone" 
                        type="tel" 
                        value="{{ old('phone', $user->phone) }}" 
                        inputmode="tel"
                        maxlength="15"
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs transition font-mono" 
                        placeholder="(00) 00000-0000" 
                        autocomplete="tel" 
                    />
                </div>
                <p class="mt-1 text-[11px] text-slate-400">Para confirmação de pedidos e avisos de atualizações.</p>
                <x-input-error class="mt-1.5 text-xs text-red-500" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
            <div>
                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3500)"
                        class="text-xs font-bold text-emerald-600 flex items-center gap-1.5"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Alterações salvas com sucesso!</span>
                    </p>
                @endif
            </div>

            <button 
                type="submit" 
                :disabled="submitting"
                class="btn-primary !bg-teal-600 hover:!bg-teal-500 !min-h-10 !px-6 text-xs font-bold shadow-sm shadow-teal-600/20 flex items-center gap-2 cursor-pointer"
            >
                <svg x-show="submitting" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Salvar Alterações</span>
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cpfInput = document.getElementById('cpf');
            const phoneInput = document.getElementById('phone');

            if (cpfInput) {
                const formatCpf = (v) => {
                    v = v.replace(/\D/g, '').slice(0, 11);
                    if (v.length > 9) {
                        return v.replace(/^(\d{3})(\d{3})(\d{3})(\d{1,2})$/, '$1.$2.$3-$4');
                    } else if (v.length > 6) {
                        return v.replace(/^(\d{3})(\d{3})(\d{1,3})$/, '$1.$2.$3');
                    } else if (v.length > 3) {
                        return v.replace(/^(\d{3})(\d{1,3})$/, '$1.$2');
                    }
                    return v;
                };

                cpfInput.addEventListener('input', function (e) {
                    e.target.value = formatCpf(e.target.value);
                });

                if (cpfInput.value) {
                    cpfInput.value = formatCpf(cpfInput.value);
                }
            }

            if (phoneInput) {
                const formatPhone = (v) => {
                    v = v.replace(/\D/g, '').slice(0, 11);
                    if (v.length > 10) {
                        return v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
                    } else if (v.length > 6) {
                        return v.replace(/^(\d{2})(\d{4,5})(\d{0,4})$/, '($1) $2-$3');
                    } else if (v.length > 2) {
                        return v.replace(/^(\d{2})(\d{1,5})$/, '($1) $2');
                    }
                    return v;
                };

                phoneInput.addEventListener('input', function (e) {
                    e.target.value = formatPhone(e.target.value);
                });

                if (phoneInput.value) {
                    phoneInput.value = formatPhone(phoneInput.value);
                }
            }
        });
    </script>
</section>
