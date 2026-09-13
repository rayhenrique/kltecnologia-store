<x-guest-layout>
    <x-slot:title>Criar Conta</x-slot:title>

    <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/85 p-8 sm:p-9 shadow-2xl backdrop-blur-xl">
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
</x-guest-layout>
