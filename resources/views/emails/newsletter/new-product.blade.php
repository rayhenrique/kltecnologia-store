@extends('emails.layouts.default', ['subject' => 'Novo Produto: ' . $product->name, 'unsubscribeUrl' => $unsubscribeUrl])

@section('content')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    {{-- Badge de Lançamento --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <span style="display: inline-block; background-color: #ecfeff; border: 1px solid #a5f3fc; color: #0e7490; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 6px 14px; border-radius: 9999px;">
                🚀 Novo Produto no Catálogo
            </span>
        </td>
    </tr>

    {{-- Título Principal --}}
    <tr>
        <td style="text-align: center; padding-bottom: 16px;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                {{ $product->name }}
            </h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #475569; line-height: 1.6;">
                Temos um novo código-fonte e sistema pronto disponível para impulsionar seus projetos!
            </p>
        </td>
    </tr>

    {{-- Imagem do Produto (se houver) --}}
    @if ($product->thumbnail)
    <tr>
        <td style="text-align: center; padding-bottom: 24px;">
            <a href="{{ route('storefront.show', $product->slug) }}" target="_blank" style="display: block; text-decoration: none;">
                <img src="{{ url('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" style="max-width: 100%; border-radius: 12px; border: 1px solid #e2e8f0; display: block; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            </a>
        </td>
    </tr>
    @endif

    {{-- Card com Detalhes e Preço --}}
    <tr>
        <td style="padding: 24px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Resumo do Produto</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 14px;">
                        <p style="margin: 0 0 12px 0; font-size: 14px; color: #334155; line-height: 1.6;">
                            {{ $product->short_description ?? Str::limit(strip_tags($product->description), 160) }}
                        </p>
                        
                        <div style="margin-top: 10px;">
                            @if ((float) $product->price <= 0)
                                <span style="display: inline-block; background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 13px; font-weight: 800; padding: 4px 12px; border-radius: 8px;">
                                    GRATUITO
                                </span>
                            @else
                                <span style="font-size: 12px; color: #64748b;">Valor:</span>
                                <span style="font-size: 18px; font-weight: 800; color: #0d9488; margin-left: 6px;">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Botão CTA Principal --}}
    <tr>
        <td align="center" style="padding-top: 24px; padding-bottom: 20px;">
            <a href="{{ route('storefront.show', $product->slug) }}" target="_blank" style="display: inline-block; background-color: #0d9488; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 14px 32px; border-radius: 12px; text-align: center; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);">
                Ver Produto Completo & Baixar →
            </a>
        </td>
    </tr>

    <tr>
        <td style="text-align: center; padding-top: 8px;">
            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                Ou acesse diretamente pelo link: <br>
                <a href="{{ route('storefront.show', $product->slug) }}" style="color: #0d9488; word-break: break-all;">{{ route('storefront.show', $product->slug) }}</a>
            </p>
        </td>
    </tr>
</table>
@endsection
