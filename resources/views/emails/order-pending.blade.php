@php
    $orderIds = $orders->pluck('id')->implode(', #');
    $totalAmount = (float) $orders->sum('amount');
@endphp

@extends('emails.layouts.default', ['subject' => "Pedido #{$orderIds} Recebido - Aguardando Pagamento"])

@section('content')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    {{-- Badge de Pendência --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <span style="display: inline-block; background-color: #fefce8; border: 1px solid #fef08a; color: #854d0e; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 6px 14px; border-radius: 9999px;">
                ⏳ Aguardando Pagamento
            </span>
        </td>
    </tr>

    {{-- Título Principal --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                Recebemos o seu pedido, {{ $user->name }}!
            </h1>
            <p style="margin: 8px 0 0 0; font-size: 14px; color: #475569; line-height: 1.6;">
                Identificamos o seu pedido <strong>#{{ $orderIds }}</strong> no sistema e estamos aguardando a confirmação do pagamento pelo gateway.
            </p>
        </td>
    </tr>

    {{-- Resumo dos Produtos --}}
    <tr>
        <td style="padding: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                        <strong style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                            Itens do Pedido ({{ $orders->count() }})
                        </strong>
                    </td>
                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; text-align: right;">
                        <strong style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                            Valor
                        </strong>
                    </td>
                </tr>

                @foreach($orders as $order)
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px dashed #e2e8f0; vertical-align: middle;">
                            <span style="font-size: 14px; font-weight: 600; color: #1e293b; display: block;">
                                {{ $order->product?->title ?? 'Produto Digital KL' }}
                            </span>
                            <span style="font-size: 11px; color: #64748b; font-family: monospace;">
                                Pedido #{{ $order->id }}
                            </span>
                        </td>
                        <td style="padding: 12px 0; border-bottom: 1px dashed #e2e8f0; text-align: right; vertical-align: middle;">
                            <span style="font-size: 14px; font-weight: 700; color: #0f172a; font-family: monospace;">
                                R$ {{ number_format((float) $order->amount, 2, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td style="padding-top: 14px;">
                        <strong style="font-size: 14px; color: #0f172a;">Total a Pagar:</strong>
                    </td>
                    <td style="padding-top: 14px; text-align: right;">
                        <strong style="font-size: 18px; color: #0d9488; font-family: monospace;">
                            R$ {{ number_format($totalAmount, 2, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Orientações de Compensação --}}
    <tr>
        <td style="padding-top: 24px; padding-bottom: 24px;">
            <div style="background-color: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 12px; padding: 18px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td width="30" style="vertical-align: top; padding-right: 10px;">
                            <span style="font-size: 18px;">💡</span>
                        </td>
                        <td style="vertical-align: top;">
                            <strong style="display: block; font-size: 13px; color: #0f766e; margin-bottom: 4px;">
                                Como funciona a liberação:
                            </strong>
                            <ul style="margin: 0; padding-left: 16px; font-size: 12px; color: #334155; line-height: 1.6;">
                                <li><strong>PIX:</strong> A confirmação é instantânea (geralmente em menos de 1 minuto).</li>
                                <li><strong>Cartão de Crédito:</strong> Aprovado quase que imediatamente pela operadora.</li>
                                <li><strong>Boleto Bancário:</strong> Pode levar de 1 a 2 dias úteis para compensação bancária.</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #64748b;">
                Você pode acompanhar o status dos seus pedidos ou acessar downloads a qualquer momento:
            </p>
            <a href="{{ route('customer.downloads') }}" target="_blank" style="display: inline-block; background-color: #0f172a; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 12px; text-align: center;">
                Acompanhar Meus Pedidos →
            </a>
        </td>
    </tr>
</table>
@endsection
