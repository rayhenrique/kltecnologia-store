<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:create-admin
                            {email : O e-mail do administrador}
                            {--name=Administrador : O nome do administrador}
                            {--password-env=ADMIN_PASSWORD : Variável de ambiente que contém a senha}';

    /**
     * @var string
     */
    protected $description = 'Cria ou atualiza um administrador sem expor a senha na linha de comando';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $name = trim((string) $this->option('name'));
        $passwordEnvironmentVariable = trim((string) $this->option('password-env'));
        $passwordFromEnvironment = $passwordEnvironmentVariable !== ''
            ? getenv($passwordEnvironmentVariable)
            : false;

        $password = is_string($passwordFromEnvironment) && $passwordFromEnvironment !== ''
            ? $passwordFromEnvironment
            : (string) $this->secret('Senha do administrador');

        $validator = Validator::make(
            compact('email', 'name', 'password'),
            [
                'email' => ['required', 'email:rfc', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()],
            ],
            [
                'password.min' => 'A senha deve ter pelo menos 12 caracteres.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => $name,
            'password' => Hash::make($password),
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ])->save();

        $this->info("Administrador [{$email}] configurado com sucesso com perfil Admin!");

        return self::SUCCESS;
    }
}
