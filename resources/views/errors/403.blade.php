<x-storefront-layout>
    <x-slot:title>Acesso negado</x-slot:title>
    <section class="page-container py-24 text-center">
        <p class="eyebrow">Erro 403</p>
        <h1 class="mt-4 page-title">Você não tem acesso a esta área.</h1>
        <p class="mx-auto mt-4 max-w-xl text-slate-600">Entre com uma conta autorizada ou volte para a vitrine.</p>
        <a href="{{ route('storefront.index') }}" class="btn-primary mt-8">Voltar para a vitrine</a>
    </section>
</x-storefront-layout>
