@extends('emails.layouts.default', ['subject' => 'Bem-vindo(a) à KL Tecnologia!'])

@section('content')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    {{-- Badge de Boas-Vindas --}}
    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <span style="display: inline-block; background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 6px 14px; border-radius: 9999px;">
                🎉 Seja Muito Bem-vindo(a)
            </span>
        </td>
    </tr>

    {{-- Título Principal --}}
    <tr>
        <td style="text-align: center; padding-bottom: 16px;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                Olá, {{ $user->name }}!
            </h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #475569; line-height: 1.6;">
                Sua conta na <strong>KL Tecnologia</strong> foi ativada com sucesso com a sua compra!
            </p>
        </td>
    </tr>

    {{-- Caixa de Informações da Conta --}}
    <tr>
        <td style="padding: 24px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Dados de Acesso:</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 12px;">
                        <p style="margin: 0 0 6px 0; font-size: 14px; color: #334155;">
                            <strong>E-mail cadastrado:</strong> <span style="font-family: monospace; color: #0d9488;">{{ $user->email }}</span>
                        </p>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">
                            A sua senha foi definida por você no momento da finalização do pedido.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Destaques & Benefícios --}}
    <tr>
        <td style="padding-top: 24px; padding-bottom: 28px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding-bottom: 16px;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="36" style="vertical-align: top; padding-right: 12px;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background-color: #ecfeff; border: 1px solid #a5f3fc; border-radius: 8px; text-align: center; line-height: 32px; font-size: 15px;">
                                        ⚡
                                    </span>
                                </td>
                                <td style="vertical-align: top;">
                                    <strong style="display: block; font-size: 14px; color: #0f172a; margin-bottom: 2px;">Downloads Imediatos e Vitalícios</strong>
                                    <span style="font-size: 13px; color: #64748b; line-height: 1.4;">Seus produtos e códigos-fonte ficam permanentemente salvos na sua conta, disponíveis a qualquer momento.</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 16px;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="36" style="vertical-align: top; padding-right: 12px;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; text-align: center; line-height: 32px; font-size: 15px;">
                                        🔒
                                    </span>
                                </td>
                                <td style="vertical-align: top;">
                                    <strong style="display: block; font-size: 14px; color: #0f172a; margin-bottom: 2px;">Links Seguros & Protegidos</strong>
                                    <span style="font-size: 13px; color: #64748b; line-height: 1.4;">Arquivos armazenados com criptografia e downloads via links temporários exclusivos para sua proteção.</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="36" style="vertical-align: top; padding-right: 12px;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background-color: #fefce8; border: 1px solid #fef08a; border-radius: 8px; text-align: center; line-height: 32px; font-size: 15px;">
                                        💎
                                    </span>
                                </td>
                                <td style="vertical-align: top;">
                                    <strong style="display: block; font-size: 14px; color: #0f172a; margin-bottom: 2px;">Suporte & Lançamentos</strong>
                                    <span style="font-size: 13px; color: #64748b; line-height: 1.4;">Você terá acesso a atualizações de produtos adquiridos e novidades exclusivas da nossa equipe.</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Botão CTA Principal --}}
    <tr>
        <td align="center" style="padding-bottom: 20px;">
            <a href="{{ route('customer.downloads') }}" target="_blank" style="display: inline-block; background-color: #0d9488; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 14px 32px; border-radius: 12px; text-align: center; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);">
                Acessar Minha Conta & Downloads →
            </a>
        </td>
    </tr>

    <tr>
        <td style="text-align: center; padding-top: 10px;">
            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                Ou copie e cole no seu navegador: <br>
                <a href="{{ route('customer.downloads') }}" style="color: #0d9488; word-break: break-all;">{{ route('customer.downloads') }}</a>
            </p>
        </td>
    </tr>
</table>
@endsection
