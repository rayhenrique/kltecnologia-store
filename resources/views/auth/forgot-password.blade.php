<x-guest-layout>
    <x-slot:title>Recuperar Senha</x-slot:title>

    <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/85 p-8 sm:p-9 shadow-2xl backdrop-blur-xl">
        {{-- Linha de destaque em gradiente no topo --}}
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-teal-400 via-emerald-400 to-blue-500"></div>

        {{-- Cabeçalho do Card --}}
        <div class="mb-6">
            <div class="inline-flex items-center gap-1.5 rounded-full border border-teal-500/20 bg-teal-500/10 px-3 py-1 text-xs font-semibold text-teal-400 mb-3">
                <svg class="h-3.5 w-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <span>Recuperação de Acesso</span>
            </div>
            
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-white">
                Esqueceu sua senha?
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-400 leading-relaxed">
                Informe o seu e-mail cadastrado e enviaremos um link de redefinição seguro para você criar uma nova senha.
            </p>
        </div>

        {{-- Alerta de Status de Sessão --}}
        @if (session('status'))
            <div class="mb-5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5 text-xs text-emerald-300 flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" novalidate class="space-y-4">
            @csrf

            {{-- E-mail --}}
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    E-mail Cadastrado
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
                        autofocus 
                        placeholder="seu.email@exemplo.com"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
            </div>

            {{-- Botão Enviar Link --}}
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-600 hover:from-teal-400 hover:to-emerald-400 text-white font-display font-bold py-3 px-6 text-sm tracking-wide shadow-lg shadow-teal-500/25 hover:shadow-teal-500/35 transition duration-200 transform active:scale-[0.99]"
                >
                    <span>Enviar Link de Redefinição</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>

        {{-- Retornar ao Login --}}
        <div class="mt-6 pt-5 border-t border-slate-800 text-center">
            <a 
                href="{{ route('login') }}" 
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-400 hover:text-teal-300 transition"
            >
                &larr; Voltar para a tela de login
            </a>
        </div>
    </div>
</x-guest-layout>
