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

    public function test_it_creates_default_admin_user_when_no_arguments_given(): void
    {
        $this->artisan('app:create-admin')
            ->assertSuccessful()
            ->expectsOutput('Administrador [admin@example.com] configurado com sucesso com perfil Admin!');

        $admin = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($admin);
        $this->assertSame(UserRole::Admin, $admin->role);
        $this->assertTrue(Hash::check('[REMOVED-ADMIN-PASSWORD]', $admin->password));
    }

    public function test_it_creates_custom_admin_user_with_arguments(): void
    {
        $this->artisan('app:create-admin custom@example.com custompass --name="Custom Admin"')
            ->assertSuccessful();

        $admin = User::where('email', 'custom@example.com')->first();
        $this->assertNotNull($admin);
        $this->assertSame('Custom Admin', $admin->name);
        $this->assertSame(UserRole::Admin, $admin->role);
        $this->assertTrue(Hash::check('custompass', $admin->password));
    }
}
