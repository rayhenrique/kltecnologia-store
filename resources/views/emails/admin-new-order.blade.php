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
    $cleanPhone = $customer->clean_phone ?? preg_replace('/\D/', '', (string) ($customer->phone ?? ''));
    $whatsAppUrl = !empty($cleanPhone) ? 'https://wa.me/55' . ltrim($cleanPhone, '0') : null;
    $adminOrderUrl = $firstOrder ? route('admin.orders.show', $firstOrder) : route('admin.orders.index');
@endphp

@extends('emails.layouts.default', ['subject' => "🎉 [Nova Venda] Pedido #{$orderIds} - R$ " . number_format($totalAmount, 2, ',', '.') . " - {$customer->name}"])

@section('content')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    {{-- Badge de Nova Venda --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <span style="display: inline-block; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 6px 14px; border-radius: 9999px;">
                🎉 Nova Compra Realizada!
            </span>
        </td>
    </tr>

    {{-- Título Principal --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                Você fez uma nova venda, Ray!
            </h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #475569; line-height: 1.6;">
                O cliente <strong>{{ $customer->name }}</strong> acabou de realizar uma compra na loja <strong>KL Tecnologia</strong>.
            </p>
        </td>
    </tr>

    {{-- Botão CTA Principal para o Painel Admin --}}
    <tr>
        <td align="center" style="padding-bottom: 28px;">
            <a href="{{ $adminOrderUrl }}" target="_blank" style="display: inline-block; background-color: #0d9488; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 16px 36px; border-radius: 12px; text-align: center; box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35);">
                Ver Pedido no Painel Admin →
            </a>
        </td>
    </tr>

    {{-- Card de Dados do Cliente --}}
    <tr>
        <td style="padding: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 20px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td colspan="2" style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                        <strong style="font-size: 12px; color: #0d9488; text-transform: uppercase; letter-spacing: 0.8px;">
                            👤 Dados do Cliente / Comprador
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0 6px 0; font-size: 13px; color: #64748b; width: 35%;">Nome:</td>
                    <td style="padding: 10px 0 6px 0; font-size: 13px; color: #0f172a; font-weight: 600;">
                        {{ $customer->name }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">E-mail:</td>
                    <td style="padding: 6px 0; font-size: 13px; color: #0f172a; font-weight: 600;">
                        <a href="mailto:{{ $customer->email }}" style="color: #0d9488; text-decoration: underline;">
                            {{ $customer->email }}
                        </a>
                    </td>
                </tr>
                @if(!empty($customer->phone))
                    <tr>
                        <td style="padding: 6px 0; font-size: 13px; color: #64748b;">WhatsApp / Tel:</td>
                        <td style="padding: 6px 0; font-size: 13px; color: #0f172a; font-weight: 600;">
                            <span>{{ $customer->phone }}</span>
                            @if($whatsAppUrl)
                                <a href="{{ $whatsAppUrl }}" target="_blank" style="display: inline-block; margin-left: 8px; font-size: 11px; font-weight: 700; color: #047857; background-color: #d1fae5; border-radius: 4px; padding: 2px 6px; text-decoration: none;">
                                    Abrir WhatsApp 💬
                                </a>
                            @endif
                        </td>
                    </tr>
                @endif
                @if(!empty($customer->cpf))
                    <tr>
                        <td style="padding: 6px 0; font-size: 13px; color: #64748b;">CPF:</td>
                        <td style="padding: 6px 0; font-size: 13px; color: #0f172a; font-weight: 600; font-family: monospace;">
                            {{ $customer->cpf }}
                        </td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>

    <tr>
        <td style="height: 16px;"></td>
    </tr>

    {{-- Resumo dos Itens Adquiridos --}}
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
                                Aprovado
                            </span>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td style="padding-top: 14px;">
                        <span style="font-size: 12px; color: #64748b; display: block;">Método de Pagamento: <strong>{{ $paymentMethodLabel }}</strong></span>
                        @if($firstOrder?->gateway_reference)
                            <span style="font-size: 11px; color: #94a3b8; font-family: monospace;">Ref: {{ $firstOrder->gateway_reference }}</span>
                        @endif
                    </td>
                    <td style="padding-top: 14px; text-align: right;">
                        <span style="font-size: 12px; color: #64748b;">Valor Total:</span>
                        <strong style="font-size: 18px; color: #0d9488; font-family: monospace; display: block;">
                            R$ {{ number_format($totalAmount, 2, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Rodapé Interno com Informações de Gestão --}}
    <tr>
        <td style="padding-top: 24px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="30" style="vertical-align: top; padding-right: 10px;">
                        <span style="font-size: 18px;">⚡</span>
                    </td>
                    <td style="vertical-align: top;">
                        <strong style="display: block; font-size: 13px; color: #0f172a; margin-bottom: 4px;">
                            Acesso Imediato Liberado ao Cliente:
                        </strong>
                        <p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
                            Os downloads já foram disponibilizados na conta do comprador e o e-mail de confirmação foi encaminhado com sucesso. Você pode gerenciar ou editar este pedido a qualquer momento através do seu 
                            <a href="{{ route('admin.orders.index') }}" target="_blank" style="color: #0d9488; font-weight: 600; text-decoration: underline;">Painel Administrativo</a>.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
