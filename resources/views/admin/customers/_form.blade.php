@csrf

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        {{-- Nome Completo --}}
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Nome Completo <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                value="{{ old('name', $customer->name ?? '') }}" 
                required 
                maxlength="255"
                placeholder="Ex: Ray Henrique"
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition @error('name') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            />
            @error('name')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- E-mail --}}
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Endereço de E-mail <span class="text-rose-500">*</span>
            </label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                value="{{ old('email', $customer->email ?? '') }}" 
                required 
                maxlength="255"
                placeholder="cliente@exemplo.com"
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            />
            @error('email')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- CPF --}}
        <div>
            <label for="cpf" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                CPF <span class="text-slate-400 font-normal">(Opcional)</span>
            </label>
            <input 
                type="text" 
                name="cpf" 
                id="cpf" 
                value="{{ old('cpf', $customer->cpf ?? '') }}" 
                maxlength="14"
                placeholder="000.000.000-00"
                x-data
                x-on:input="
                    let v = $el.value.replace(/\D/g, '');
                    if (v.length > 11) v = v.slice(0, 11);
                    if (v.length > 9) v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
                    else if (v.length > 6) v = v.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
                    else if (v.length > 3) v = v.replace(/(\d{3})(\d{1,3})/, '$1.$2');
                    $el.value = v;
                "
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition font-mono @error('cpf') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            />
            @error('cpf')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Telefone / WhatsApp --}}
        <div>
            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Telefone / WhatsApp <span class="text-slate-400 font-normal">(Opcional)</span>
            </label>
            <input 
                type="text" 
                name="phone" 
                id="phone" 
                value="{{ old('phone', $customer->phone ?? '') }}" 
                maxlength="15"
                placeholder="(00) 00000-0000"
                x-data
                x-on:input="
                    let v = $el.value.replace(/\D/g, '');
                    if (v.length > 11) v = v.slice(0, 11);
                    if (v.length > 10) v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    else if (v.length > 6) v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                    else if (v.length > 2) v = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                    $el.value = v;
                "
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition font-mono @error('phone') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            />
            @error('phone')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Perfil / Tipo de Usuário --}}
        <div>
            <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Perfil de Acesso <span class="text-rose-500">*</span>
            </label>
            <select 
                name="role" 
                id="role" 
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition @error('role') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            >
                @foreach ($roles as $role)
                    <option value="{{ $role->value }}" @selected(old('role', $customer->role?->value ?? 'customer') === $role->value)>
                        {{ $role->value === 'admin' ? 'Administrador (Painel Admin)' : 'Cliente (Loja / Downloads)' }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Senha --}}
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Senha de Acesso {{ isset($customer->id) ? '(Preencha apenas para alterar)' : '*' }}
            </label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                {{ !isset($customer->id) ? 'required' : '' }}
                minlength="8"
                placeholder="{{ isset($customer->id) ? 'Deixe em branco para manter a atual' : 'Mínimo de 8 caracteres' }}"
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-2xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition @error('password') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @enderror"
            />
            @error('password')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Newsletter Checkbox --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4">
        <label class="flex items-start gap-3 cursor-pointer">
            <input 
                type="checkbox" 
                name="subscribe_newsletter" 
                value="1" 
                @checked(old('subscribe_newsletter', $isNewsletterSubscribed ?? false))
                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500"
            />
            <div>
                <span class="text-xs font-bold text-slate-900 block">
                    Inscrição Ativa na Newsletter
                </span>
                <span class="text-xs text-slate-500 leading-relaxed block mt-0.5">
                    O cliente receberá disparos automáticos sobre novos produtos e artigos do blog (respeitando a cota diária de 100 envios).
                </span>
            </div>
        </label>
    </div>

    {{-- Botões de Ação --}}
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
        <a 
            href="{{ isset($customer->id) ? route('admin.customers.show', $customer) : route('admin.customers.index') }}" 
            class="btn-secondary text-xs !min-h-10 !px-4"
        >
            Cancelar
        </a>
        <button 
            type="submit" 
            class="btn-primary !bg-teal-600 hover:!bg-teal-500 text-xs !min-h-10 !px-5 shadow-sm shadow-teal-600/20"
        >
            {{ isset($customer->id) ? 'Salvar Alterações' : 'Cadastrar Cliente' }}
        </button>
    </div>
</div>
