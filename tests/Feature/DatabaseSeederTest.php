<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_the_expected_idempotent_demo_data(): void
    {
        Storage::fake('digital_products');
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('products', 5);
        $this->assertDatabaseCount('orders', 0);

        $admin = User::whereIsAdmin()->sole();
        $customer = User::query()->where('role', UserRole::Customer->value)->sole();

        $this->assertSame('admin@kltecnologia.test', $admin->email);
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($customer->isAdmin());
        $this->assertTrue(Hash::check('password', $admin->password));
        $this->assertTrue(Hash::check('password', $customer->password));
        Storage::disk('digital_products')->assertExists('products/demo-kit-de-templates-para-instagram.txt');
    }
}
