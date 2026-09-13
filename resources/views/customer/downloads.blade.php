<x-app-layout>
    <x-slot:title>Meus downloads</x-slot:title>
    <x-slot name="header"><div><p class="eyebrow">Biblioteca</p><h1 class="mt-1 font-display text-2xl font-bold">Meus downloads</h1></div></x-slot>
    <div class="page-container py-8"><div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($downloads as $item) @php($order = $item['order'])<article class="product-cut panel flex flex-col overflow-hidden">@if($order->product->cover_path)<img src="{{ asset($order->product->cover_path) }}" alt="" class="aspect-[3/2] w-full object-cover">@else<div class="grid aspect-[3/2] place-items-center bg-blue-50 font-display text-3xl font-bold text-blue-700">KL</div>@endif<div class="flex flex-1 flex-col p-5"><p class="font-mono text-xs text-slate-500">PEDIDO #{{ $order->id }}</p><h2 class="mt-2 font-display text-lg font-bold">{{ $order->product->title }}</h2><p class="mt-2 text-sm text-slate-500">Compra em {{ $order->created_at->format('d/m/Y') }}</p><a href="{{ $item['download_url'] }}" class="btn-primary mt-6 w-full">Baixar arquivo</a><p class="mt-2 text-center text-xs text-slate-500">Link válido por 10 minutos.</p></div></article>
        @empty<div class="panel col-span-full px-6 py-14 text-center"><p class="font-display text-xl font-bold">Sua biblioteca está vazia.</p><p class="mt-2 text-slate-600">Produtos com pagamento confirmado aparecem aqui.</p><a href="{{ route('storefront.index') }}#produtos" class="btn-primary mt-6">Conhecer produtos</a></div>@endforelse
    </div><div class="mt-8">{{ $downloads->links() }}</div></div>
</x-app-layout>
