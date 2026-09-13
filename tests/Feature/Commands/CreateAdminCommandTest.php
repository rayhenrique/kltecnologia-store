<?php

namespace Tests\Feature\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_with_password_from_environment(): void
    {
        putenv('TEST_ADMIN_PASSWORD=SenhaForte123!');

        try {
            $this->artisan('app:create-admin', [
                'email' => 'admin@example.com',
                '--name' => 'Administrador de Teste',
                '--password-env' => 'TEST_ADMIN_PASSWORD',
            ])->assertSuccessful();
        } finally {
            putenv('TEST_ADMIN_PASSWORD');
        }

        $admin = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($admin);
        $this->assertSame('Administrador de Teste', $admin->name);
        $this->assertSame(UserRole::Admin, $admin->role);
        $this->assertTrue(Hash::check('SenhaForte123!', $admin->password));
    }

    public function test_it_rejects_a_weak_admin_password(): void
    {
        putenv('TEST_ADMIN_PASSWORD=fraca');

        try {
            $this->artisan('app:create-admin', [
                'email' => 'admin@example.com',
                '--password-env' => 'TEST_ADMIN_PASSWORD',
            ])->assertFailed();
        } finally {
            putenv('TEST_ADMIN_PASSWORD');
        }

        $this->assertDatabaseMissing('users', ['email' => 'admin@example.com']);
    }
}
