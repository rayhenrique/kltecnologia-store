<!DOCTYPE html>
<html lang="pt-BR" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? config('app.name', 'KL Tecnologia') }}</title>
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #0b0f19; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; }
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; margin: auto !important; }
            .fluid { max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important; }
            .stack-column { display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important; }
            .content-padding { padding: 24px 16px !important; }
            .header-padding { padding: 24px 16px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0b0f19; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <div style="background-color: #0b0f19; padding: 30px 10px;">
        <!-- Container Principal -->
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" class="email-container" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);">
            <!-- Header Dark -->
            <tr>
                <td style="background-color: #040812; padding: 32px 40px; text-align: center; border-bottom: 2px solid #14b8a6;" class="header-padding">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td align="center">
                                <a href="{{ config('app.url') }}" target="_blank" style="text-decoration: none; display: inline-block;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td style="vertical-align: middle;">
                                                <span style="display: inline-block; width: 38px; height: 38px; background-color: #0f172a; border: 1px solid #1e293b; border-radius: 10px; line-height: 38px; text-align: center; font-weight: bold; font-size: 16px; color: #14b8a6; margin-right: 12px;">
                                                    KL
                                                </span>
                                            </td>
                                            <td style="vertical-align: middle; text-align: left;">
                                                <span style="font-size: 20px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                                                    KL<span style="color: #14b8a6;">Tecnologia</span>
                                                </span>
                                                <div style="font-size: 10px; font-weight: 600; color: #64748b; letter-spacing: 1px; text-transform: uppercase; font-family: monospace;">
                                                    E-commerce de Produtos Digitais
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Conteúdo Dinâmico -->
            <tr>
                <td style="padding: 40px; background-color: #ffffff;" class="content-padding">
                    @yield('content')
                </td>
            </tr>

            <!-- Rodapé -->
            <tr>
                <td style="background-color: #f8fafc; padding: 30px 40px; border-top: 1px solid #e2e8f0; text-align: center;">
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #64748b; line-height: 1.5;">
                        Você recebeu este e-mail porque realizou uma ação ou possui conta na <strong>KL Tecnologia</strong>.
                    </p>
                    <p style="margin: 0 0 15px 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                        Precisa de ajuda ou suporte técnico? Responda a este e-mail ou acesse nossa central em 
                        <a href="{{ config('app.url') }}" target="_blank" style="color: #0d9488; text-decoration: underline;">{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'kltecnologia.com' }}</a>.
                    </p>
                    @hasSection('unsubscribe_url')
                        <p style="margin: 12px 0 0 0; font-size: 11px; color: #94a3b8;">
                            Não deseja mais receber novidades e atualizações? <a href="@yield('unsubscribe_url')" target="_blank" style="color: #64748b; text-decoration: underline;">Cancelar inscrição da newsletter</a>.
                        </p>
                    @elseif(isset($unsubscribeUrl) && !empty($unsubscribeUrl))
                        <p style="margin: 12px 0 0 0; font-size: 11px; color: #94a3b8;">
                            Não deseja mais receber novidades e atualizações? <a href="{{ $unsubscribeUrl }}" target="_blank" style="color: #64748b; text-decoration: underline;">Cancelar inscrição da newsletter</a>.
                        </p>
                    @endif
                    <div style="font-size: 11px; color: #94a3b8; border-top: 1px dashed #cbd5e1; padding-top: 15px; margin-top: 15px;">
                        © {{ date('Y') }} KL Tecnologia. Todos os direitos reservados.
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
