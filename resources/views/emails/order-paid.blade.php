@php
    $orderIds = $orders->pluck('id')->implode(', #');
    $totalAmount = (float) $orders->sum('amount');
    $firstOrder = $orders->first();
    $paymentMethod = $firstOrder?->payment_method ?? 'Mercado Pago';
    if ($paymentMethod === 'free') {
        $paymentMethodLabel = 'Download Gratuito (100% OFF)';
    } elseif ($paymentMethod === 'pix' || $paymentMethod === 'bank_transfer') {
        $paymentMethodLabel = 'PIX';
    } elseif ($paymentMethod === 'credit_card') {
        $paymentMethodLabel = 'Cartão de Crédito';
    } elseif ($paymentMethod === 'ticket') {
        $paymentMethodLabel = 'Boleto Bancário';
    } else {
        $paymentMethodLabel = ucfirst($paymentMethod);
    }
@endphp

@extends('emails.layouts.default', ['subject' => "Pagamento Confirmado! Seus downloads já estão liberados - Pedido #{$orderIds}"])

@section('content')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    {{-- Badge de Sucesso --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <span style="display: inline-block; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 6px 14px; border-radius: 9999px;">
                ✅ Pagamento Aprovado
            </span>
        </td>
    </tr>

    {{-- Título Principal --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                Tudo pronto, {{ $user->name }}!
            </h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #475569; line-height: 1.6;">
                O seu pagamento para o pedido <strong>#{{ $orderIds }}</strong> foi confirmado e seus arquivos digitais já estão liberados para download imediato.
            </p>
        </td>
    </tr>

    {{-- Botão CTA Principal de Download --}}
    <tr>
        <td align="center" style="padding-bottom: 28px;">
            <a href="{{ route('customer.downloads') }}" target="_blank" style="display: inline-block; background-color: #0d9488; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 16px 36px; border-radius: 12px; text-align: center; box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35);">
                Baixar Meus Produtos Agora →
            </a>
        </td>
    </tr>

    {{-- Resumo dos Itens Adquiridos --}}
    <tr>
        <td style="padding: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                        <strong style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                            Produto Adquirido ({{ $orders->count() }})
                        </strong>
                    </td>
                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; text-align: right;">
                        <strong style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                            Status
                        </strong>
                    </td>
                </tr>

                @foreach($orders as $order)
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px dashed #e2e8f0; vertical-align: middle;">
                            <span style="font-size: 14px; font-weight: 700; color: #0f172a; display: block;">
                                {{ $order->product?->title ?? 'Produto Digital KL' }}
                            </span>
                            <span style="font-size: 11px; color: #64748b; font-family: monospace;">
                                Pedido #{{ $order->id }} • R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px 0; border-bottom: 1px dashed #e2e8f0; text-align: right; vertical-align: middle;">
                            <span style="display: inline-block; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 3px 8px; border-radius: 6px;">
                                Liberado
                            </span>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td style="padding-top: 14px;">
                        <span style="font-size: 12px; color: #64748b; display: block;">Método de Pagamento: <strong>{{ $paymentMethodLabel }}</strong></span>
                    </td>
                    <td style="padding-top: 14px; text-align: right;">
                        <span style="font-size: 12px; color: #64748b;">Total Pago:</span>
                        <strong style="font-size: 16px; color: #0d9488; font-family: monospace; display: block;">
                            R$ {{ number_format($totalAmount, 2, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Dicas de Acesso & Suporte --}}
    <tr>
        <td style="padding-top: 24px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="30" style="vertical-align: top; padding-right: 10px;">
                        <span style="font-size: 18px;">📦</span>
                    </td>
                    <td style="vertical-align: top;">
                        <strong style="display: block; font-size: 13px; color: #0f172a; margin-bottom: 4px;">
                            Instruções de Download:
                        </strong>
                        <p style="margin: 0 0 8px 0; font-size: 12px; color: #475569; line-height: 1.5;">
                            Clique no botão acima para abrir a aba <strong>Meus Downloads & Pedidos</strong>. Cada produto possui um link seguro exclusivo para baixar o arquivo .ZIP/.RAR com documentação completa.
                        </p>
                        <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.5;">
                            Seus downloads ficam permanentemente salvos na sua conta, permitindo que você baixe novamente sempre que precisar.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
