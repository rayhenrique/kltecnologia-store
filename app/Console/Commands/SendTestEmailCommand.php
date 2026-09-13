<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendTestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : O endereço de e-mail de destino para o teste}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia um e-mail transacional de teste para verificar a conexão SMTP (Gmail, etc)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipient = (string) ($this->argument('email') ?: config('mail.from.address'));
        if ($recipient === '') {
            $this->error('Informe o destinatário ou configure MAIL_FROM_ADDRESS.');

            return self::FAILURE;
        }

        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $username = config('mail.mailers.smtp.username');

        $this->info('--- Teste de Envio de E-mail KL Tecnologia ---');
        $this->line("Mailer: <comment>{$mailer}</comment>");
        $this->line("Servidor SMTP: <comment>{$host}:{$port}</comment>");
        $this->line("Usuário SMTP: <comment>{$username}</comment>");
        $this->line("Destinatário: <comment>{$recipient}</comment>");
        $this->newLine();

        $this->info("Enviando e-mail de teste para {$recipient}...");

        try {
            Mail::raw("Olá!\n\nEste é um e-mail de teste disparado com sucesso pela loja KL Tecnologia em ".now()->format('d/m/Y H:i:s').".\n\nSua configuração de envio (SMTP/Gmail) está funcionando perfeitamente!", function ($message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Teste de Conexão de E-mail - KL Tecnologia');
            });

            $this->info("✔ Sucesso! O e-mail foi enviado para {$recipient}.");
            $this->line('Verifique sua caixa de entrada (e pasta de spam) para confirmar o recebimento.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('✖ Falha ao enviar o e-mail de teste:');
            $this->error($exception->getMessage());
            $this->newLine();

            if (str_contains($exception->getMessage(), '535') || str_contains(strtolower($exception->getMessage()), 'authentication failed') || str_contains(strtolower($exception->getMessage()), 'badcredentials')) {
                $this->warn('--- DICA PARA CONFIGURAÇÃO DO GMAIL ---');
                $this->line('O Google não permite o uso da sua senha normal de login em conexões SMTP.');
                $this->line('Você deve gerar uma <comment>Senha de App (App Password)</comment> de 16 caracteres:');
                $this->line('1. Acesse: https://myaccount.google.com/security');
                $this->line('2. Certifique-se de que a "Verificação em duas etapas" está ATIVADA.');
                $this->line('3. Acesse: https://myaccount.google.com/apppasswords');
                $this->line('4. Crie um app chamado "KL Tecnologia" e copie a senha de 16 letras gerada.');
                $this->line('5. Cole essa senha no campo MAIL_PASSWORD do seu arquivo .env (sem espaços).');
            }

            return self::FAILURE;
        }
    }
}
