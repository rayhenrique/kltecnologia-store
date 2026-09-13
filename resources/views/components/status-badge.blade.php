@props(['status'])
@php
    $value = $status instanceof \BackedEnum ? $status->value : $status;
    [$label, $classes] = match ($value) {
        'paid' => ['Pago', 'bg-emerald-100 text-emerald-800'],
        'failed' => ['Falhou', 'bg-red-100 text-red-800'],
        'canceled' => ['Cancelado', 'bg-slate-200 text-slate-700'],
        default => ['Pendente', 'bg-amber-100 text-amber-800'],
    };
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex rounded-full px-2.5 py-1 text-xs font-bold '.$classes]) }}>{{ $label }}</span>
