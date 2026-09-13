<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_routes(): void
    {
        $this->actingAs(User::factory()->customer()->create())->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get(route('admin.dashboard'))->assertOk()->assertSee('Painel administrativo');
    }

    public function test_customer_navigation_hides_admin_links(): void
    {
        $this->actingAs(User::factory()->customer()->create())->get(route('customer.downloads'))->assertOk()->assertDontSee(route('admin.dashboard'))->assertDontSee(route('admin.products.index'));
    }
}
