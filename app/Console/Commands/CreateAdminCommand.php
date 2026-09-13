<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin 
                            {email=admin@example.com : O e-mail do administrador} 
                            {password=[REMOVED-ADMIN-PASSWORD] : A senha do administrador} 
                            {--name=Ray Henrique : O nome do administrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria ou atualiza um usuário com perfil de administrador';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = (string) $this->argument('password');
        $name = (string) ($this->option('name') ?: 'Ray Henrique');

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => $name,
            'password' => Hash::make($password),
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ])->save();

        $this->info("Administrador [{$email}] configurado com sucesso com perfil Admin!");

        return Command::SUCCESS;
    }
}
