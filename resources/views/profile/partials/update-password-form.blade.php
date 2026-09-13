<section>
    <div class="border-b border-slate-100 pb-4 mb-6">
        <div class="flex items-center gap-2 mb-1">
            <span class="rounded-lg bg-blue-50 border border-blue-200/80 p-1.5 text-blue-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </span>
            <h2 class="font-display text-base sm:text-lg font-bold text-slate-900">
                Segurança & Senha de Acesso
            </h2>
        </div>
        <p class="text-xs text-slate-500">
            Recomendamos utilizar uma senha longa e exclusiva com letras, números e caracteres especiais para garantir a proteção máxima da sua conta.
        </p>
    </div>

    <form 
        method="post" 
        action="{{ route('password.update') }}" 
        class="space-y-5" 
        novalidate
        x-data="{ submitting: false, showCurrent: false, showNew: false, showConfirm: false }"
        x-on:submit="submitting = true"
    >
        @csrf
        @method('put')

        {{-- Senha Atual --}}
        <div>
            <x-input-label for="update_password_current_password" value="Senha Atual *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
            <div class="relative rounded-xl shadow-2xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <input 
                    id="update_password_current_password" 
                    name="current_password" 
                    :type="showCurrent ? 'text' : 'password'" 
                    required 
                    autocomplete="current-password" 
                    class="w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs transition font-mono"
                    placeholder="Sua senha atual"
                />
                <button 
                    type="button" 
                    @click="showCurrent = !showCurrent" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none"
                    tabindex="-1"
                    title="Alternar visualização da senha"
                >
                    <svg x-show="!showCurrent" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showCurrent" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-red-500" />
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- Nova Senha --}}
            <div>
                <x-input-label for="update_password_password" value="Nova Senha *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input 
                        id="update_password_password" 
                        name="password" 
                        :type="showNew ? 'text' : 'password'" 
                        required 
                        autocomplete="new-password" 
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs transition font-mono"
                        placeholder="Mínimo 8 caracteres"
                    />
                    <button 
                        type="button" 
                        @click="showNew = !showNew" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none"
                        tabindex="-1"
                        title="Alternar visualização da senha"
                    >
                        <svg x-show="!showNew" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showNew" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-red-500" />
            </div>

            {{-- Confirmar Nova Senha --}}
            <div>
                <x-input-label for="update_password_password_confirmation" value="Confirmar Nova Senha *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input 
                        id="update_password_password_confirmation" 
                        name="password_confirmation" 
                        :type="showConfirm ? 'text' : 'password'" 
                        required 
                        autocomplete="new-password" 
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 shadow-2xs transition font-mono"
                        placeholder="Repita a nova senha"
                    />
                    <button 
                        type="button" 
                        @click="showConfirm = !showConfirm" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none"
                        tabindex="-1"
                        title="Alternar visualização da senha"
                    >
                        <svg x-show="!showConfirm" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showConfirm" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-red-500" />
            </div>
        </div>

        {{-- Checklist de Boas Práticas --}}
        <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3 text-[11px] text-slate-500 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <span class="font-medium text-slate-700 flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Requisitos recomendados:
            </span>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1 text-slate-600">• Mínimo de 8 caracteres</span>
                <span class="inline-flex items-center gap-1 text-slate-600">• Misturar letras e números</span>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
            <div>
                @if (session('status') === 'password-updated')
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
                        <span>Senha alterada com sucesso!</span>
                    </p>
                @endif
            </div>

            <button 
                type="submit" 
                :disabled="submitting"
                class="btn-primary !bg-slate-900 hover:!bg-slate-800 !min-h-10 !px-6 text-xs font-bold shadow-sm flex items-center gap-2"
            >
                <svg x-show="submitting" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Atualizar Senha</span>
            </button>
        </div>
    </form>
</section>
