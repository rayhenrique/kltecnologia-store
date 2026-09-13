<x-storefront-layout>
    <x-slot:title>{{ $product->title }}</x-slot:title>
    <section class="page-container py-12 lg:py-20">
        <a href="{{ route('storefront.index') }}#produtos" class="text-sm font-bold text-blue-700">← Voltar ao catálogo</a>
        <div class="mt-6 grid gap-10 lg:grid-cols-2 lg:items-start">
            <div class="product-cut panel overflow-hidden">@if($product->cover_path)<img src="{{ asset($product->cover_path) }}" alt="Capa de {{ $product->title }}" class="aspect-[4/3] w-full object-cover">@else<div class="grid aspect-[4/3] place-items-center bg-gradient-to-br from-slate-100 to-blue-100 font-display text-6xl font-bold text-blue-700">KL</div>@endif</div>
            <div class="lg:py-4"><p class="eyebrow">Produto digital</p><h1 class="mt-3 page-title">{{ $product->title }}</h1><div class="mt-6 whitespace-pre-line text-base leading-7 text-slate-600">{{ $product->description }}</div><div class="mt-8 border-y border-slate-200 py-6"><p class="text-sm font-semibold text-slate-500">Pagamento único</p><p class="mt-1 font-display text-4xl font-bold">R$ {{ number_format((float) $product->price, 2, ',', '.') }}</p></div>
                <div class="mt-8">@auth<form method="POST" action="{{ route('checkout.store', $product) }}" novalidate x-data="{ submitting: false }" x-on:submit="submitting = true">@csrf<button class="btn-primary w-full sm:w-auto" type="submit" :disabled="submitting"><span x-show="!submitting">Comprar com Mercado Pago</span><span x-show="submitting" x-cloak>Abrindo pagamento…</span></button></form>@else<a href="{{ route('login', ['redirect' => route('storefront.show', $product, false)]) }}" class="btn-primary w-full sm:w-auto">Entrar para comprar</a>@endauth<p class="mt-4 text-sm text-slate-500">Após a confirmação, o arquivo fica disponível em Meus downloads.</p></div>
            </div>
        </div>
    </section>
</x-storefront-layout>
