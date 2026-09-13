<x-guest-layout>
    <x-slot:title>Criar Conta</x-slot:title>

    <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/85 p-5 sm:p-8 shadow-2xl backdrop-blur-xl">
        {{-- Linha de destaque em gradiente no topo --}}
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-teal-400 via-emerald-400 to-blue-500"></div>

        {{-- Cabeçalho do Card --}}
        <div class="mb-6">
            <div class="inline-flex items-center gap-1.5 rounded-full border border-teal-500/20 bg-teal-500/10 px-3 py-1 text-xs font-semibold text-teal-400 mb-3">
                <svg class="h-3.5 w-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span>Cadastro Rápido & Seguro</span>
            </div>
            
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-white">
                Crie sua conta gratuita
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-400 leading-relaxed">
                Acesse o catálogo completo, receba atualizações de scripts e faça downloads com segurança.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" novalidate class="space-y-4">
            @csrf

            {{-- Nome Completo --}}
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    Nome Completo
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name" 
                        placeholder="Seu nome"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                    />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-400" />
            </div>

            {{-- E-mail --}}
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    E-mail
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                        </svg>
                    </div>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="username" 
                        placeholder="seu.email@exemplo.com"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
            </div>

            {{-- Grid de CPF e Telefone (Exigências Mercado Pago) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- CPF --}}
                <div>
                    <label for="cpf" class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        <span>CPF</span>
                        <span class="text-[10px] font-normal text-teal-400 lowercase">Mercado Pago</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <input 
                            id="cpf" 
                            type="text" 
                            name="cpf" 
                            value="{{ old('cpf') }}" 
                            required 
                            inputmode="numeric"
                            maxlength="14"
                            placeholder="000.000.000-00"
                            class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('cpf')" class="mt-1.5 text-xs text-red-400" />
                </div>

                {{-- WhatsApp / Celular --}}
                <div>
                    <label for="phone" class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        <span>WhatsApp / Tel</span>
                        <span class="text-[10px] font-normal text-teal-400 lowercase">com DDD</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input 
                            id="phone" 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            required 
                            inputmode="tel"
                            maxlength="15"
                            placeholder="(11) 99999-9999"
                            class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-1.5 text-xs text-red-400" />
                </div>
            </div>

            {{-- Senha --}}
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    Senha
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password" 
                        placeholder="Mínimo de 8 caracteres"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-400" />
            </div>

            {{-- Confirmar Senha --}}
            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    Confirmar Senha
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password" 
                        placeholder="Repita sua senha"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                    />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-400" />
            </div>

            {{-- Botão de Cadastro --}}
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-600 hover:from-teal-400 hover:to-emerald-400 text-white font-display font-bold py-3 px-6 text-sm tracking-wide shadow-lg shadow-teal-500/25 hover:shadow-teal-500/35 transition duration-200 transform active:scale-[0.99]"
                >
                    <span>Finalizar Cadastro</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>

        {{-- Divisor e Link de Login --}}
        <div class="relative my-6 text-center">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-800"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-slate-900 px-3 text-slate-500 font-mono tracking-wider">Já tem cadastro?</span>
            </div>
        </div>

        <div class="text-center">
            <a 
                href="{{ route('login') }}" 
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-700 bg-slate-950/60 hover:bg-slate-800/80 py-2.5 px-4 text-xs font-bold text-slate-200 hover:text-white transition"
            >
                <span>Fazer login na sua conta</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

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
</x-guest-layout>
