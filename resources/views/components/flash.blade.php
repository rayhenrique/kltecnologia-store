@if (session('success') || session('error'))
    @php($kind = session('error') ? 'error' : 'success')
    <div class="page-container pt-4" x-data="{ show: true }" x-show="show" x-transition>
        <div role="{{ $kind === 'error' ? 'alert' : 'status' }}" aria-live="polite" class="flex items-start justify-between gap-4 rounded-xl border px-4 py-3 text-sm font-semibold {{ $kind === 'error' ? 'border-red-200 bg-red-50 text-red-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }}">
            <p>{{ session($kind) }}</p>
            <button type="button" @click="show = false" class="rounded p-1" aria-label="Fechar mensagem">×</button>
        </div>
    </div>
@endif
