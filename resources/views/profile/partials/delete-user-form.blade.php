<section class="space-y-4">
    <div class="border-b border-red-100 pb-3">
        <div class="flex items-center gap-2 mb-1 text-red-600">
            <span class="rounded-lg bg-red-100 p-1.5 text-red-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </span>
            <h2 class="font-display text-sm font-bold uppercase tracking-wider text-red-700">
                Zona de Risco: Excluir Conta
            </h2>
        </div>
        <p class="text-xs text-slate-500 leading-relaxed">
            Ao excluir sua conta, todos os acessos a produtos digitais, licenças adquiridas e histórico de compras serão permanentemente removidos.
        </p>
    </div>

    <div>
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50/80 px-4 py-2.5 text-xs font-bold text-red-700 hover:bg-red-100 hover:border-red-300 transition cursor-pointer"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Encerrar e Excluir Minha Conta</span>
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-7" novalidate>
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 text-red-600 mb-4">
                <span class="rounded-full bg-red-100 p-2.5 text-red-600 shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
                <div>
                    <h2 class="font-display text-lg font-bold text-slate-900">
                        Confirmar Exclusão Definitiva
                    </h2>
                    <p class="text-xs text-slate-500">Esta ação é irreversível e apagará seus acessos.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                Todos os seus dados, compras ativas e arquivos para download serão apagados imediatamente. Para prosseguir com a exclusão, por favor confirme sua senha atual abaixo:
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="Sua Senha de Confirmação *" class="text-xs font-bold uppercase text-slate-700 mb-1" />
                <div class="relative rounded-xl shadow-2xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 shadow-2xs transition font-mono"
                        placeholder="Digite sua senha para confirmar"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')"
                    class="btn-secondary !min-h-10 text-xs font-bold w-full sm:w-auto cursor-pointer"
                >
                    Cancelar e Manter Conta
                </button>

                <button 
                    type="submit" 
                    class="btn-danger !bg-red-600 hover:!bg-red-700 !min-h-10 text-xs font-bold w-full sm:w-auto flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Confirmar Exclusão</span>
                </button>
            </div>
        </form>
    </x-modal>
</section>
