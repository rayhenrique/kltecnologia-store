@props(['status'])

@php
    $value = $status instanceof \BackedEnum ? $status->value : $status;
    [$label, $badgeClass, $dotClass] = match ($value) {
        'paid' => ['Pago', 'bg-emerald-50 text-emerald-700 border-emerald-200/80', 'bg-emerald-500 shadow-xs shadow-emerald-500/50'],
        'failed' => ['Falhou', 'bg-red-50 text-red-700 border-red-200/80', 'bg-red-500'],
        'canceled' => ['Cancelado', 'bg-slate-100 text-slate-700 border-slate-200', 'bg-slate-400'],
        default => ['Pendente', 'bg-amber-50 text-amber-700 border-amber-200/80', 'bg-amber-500 animate-pulse'],
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold font-mono tracking-tight shadow-2xs '.$badgeClass]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
    <span>{{ $label }}</span>
</span>
