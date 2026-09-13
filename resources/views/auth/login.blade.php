<x-guest-layout>
    <x-slot:title>Entrar na Conta</x-slot:title>

    <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/85 p-5 sm:p-8 shadow-2xl backdrop-blur-xl">
        {{-- Linha de destaque em gradiente no topo --}}
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-teal-400 via-emerald-400 to-blue-500"></div>

        {{-- Cabeçalho do Card --}}
        <div class="mb-6">
            <div class="inline-flex items-center gap-1.5 rounded-full border border-teal-500/20 bg-teal-500/10 px-3 py-1 text-xs font-semibold text-teal-400 mb-3">
                <svg class="h-3.5 w-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Área Segura do Cliente</span>
            </div>
            
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-white">
                Entrar na sua conta
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-400 leading-relaxed">
                Acesse seus downloads, histórico de pedidos e licenças digitais adquiridas.
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

        <form method="POST" action="{{ route('login') }}" novalidate class="space-y-4">
            @csrf

            {{-- Campo E-mail --}}
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
                        autofocus 
                        autocomplete="username" 
                        placeholder="seu.email@exemplo.com"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
            </div>

            {{-- Campo Senha --}}
            <div x-data="{ showPassword: false }">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Senha
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-teal-400 hover:text-teal-300 transition hover:underline" href="{{ route('password.request') }}">
                            Esqueceu sua senha?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input 
                        id="password" 
                        :type="showPassword ? 'text' : 'password'" 
                        name="password" 
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••"
                        class="block w-full rounded-xl border border-slate-700 bg-slate-950/70 pl-10 pr-10 py-2.5 text-sm text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 transition shadow-inner"
                    />
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword" 
                        aria-label="Alternar visualização da senha"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-white transition"
                    >
                        <svg x-show="!showPassword" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-400" />
            </div>

            {{-- Lembrar-me --}}
            <div class="pt-1">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        name="remember"
                        class="h-4 w-4 rounded border-slate-700 bg-slate-950 text-teal-500 focus:ring-teal-500/20 focus:ring-offset-slate-900 transition" 
                    >
                    <span class="text-xs text-slate-300">Lembrar desta sessão</span>
                </label>
            </div>

            {{-- Botão de Ação --}}
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-600 hover:from-teal-400 hover:to-emerald-400 text-white font-display font-bold py-3 px-6 text-sm tracking-wide shadow-lg shadow-teal-500/25 hover:shadow-teal-500/35 transition duration-200 transform active:scale-[0.99]"
                >
                    <span>Entrar na Conta</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>

        {{-- Divisor e Link de Cadastro --}}
        <div class="relative my-6 text-center">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-800"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-slate-900 px-3 text-slate-500 font-mono tracking-wider">Novo na plataforma?</span>
            </div>
        </div>

        <div class="text-center">
            <a 
                href="{{ route('register') }}" 
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-700 bg-slate-950/60 hover:bg-slate-800/80 py-2.5 px-4 text-xs font-bold text-slate-200 hover:text-white transition"
            >
                <span>Criar uma conta gratuita</span>
                <span>&rarr;</span>
            </a>
        </div>

        {{-- Atalhos de Demonstração (Local / Testing) --}}
        @if(app()->environment('local', 'testing'))
            <div class="mt-6 pt-5 border-t border-slate-800/80 text-xs text-slate-400" x-data="{
                fillAdmin() {
                    document.getElementById('email').value = 'admin@kltecnologia.test';
                    document.getElementById('password').value = 'password';
                },
                fillCustomer() {
                    document.getElementById('email').value = 'cliente@kltecnologia.test';
                    document.getElementById('password').value = 'password';
                }
            }">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-[10px] uppercase tracking-wider text-amber-400 font-bold">Ambiente Local / Testes</span>
                    <span class="text-[10px] text-slate-500">Autopreencher:</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button 
                        type="button" 
                        @click="fillAdmin()" 
                        class="rounded-lg border border-slate-800 bg-slate-950/80 px-2.5 py-1.5 text-[11px] font-semibold text-slate-300 hover:text-white hover:border-slate-700 transition text-center"
                    >
                        ⚡ Admin Demo
                    </button>
                    <button 
                        type="button" 
                        @click="fillCustomer()" 
                        class="rounded-lg border border-slate-800 bg-slate-950/80 px-2.5 py-1.5 text-[11px] font-semibold text-slate-300 hover:text-white hover:border-slate-700 transition text-center"
                    >
                        ⚡ Cliente Demo
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-guest-layout>
