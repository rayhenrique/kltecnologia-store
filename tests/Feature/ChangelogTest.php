<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\ChangelogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangelogTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_dismiss_or_view_changelog_history(): void
    {
        $this->postJson(route('changelog.dismiss'))
            ->assertUnauthorized();

        $this->getJson(route('changelog.history'))
            ->assertUnauthorized();
    }

    public function test_admin_receives_admin_and_general_release_notes(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'last_seen_version' => null,
        ]);

        /** @var ChangelogService $service */
        $service = app(ChangelogService::class);

        $unseen = $service->getUnseenReleaseForUser($admin);

        $this->assertNotNull($unseen);
        $this->assertEquals(config('changelog.current_version'), $unseen['version']);

        // Verifica se há pelo menos uma alteração de admin
        $hasAdminChange = collect($unseen['changes'])->contains(function ($change) {
            return in_array($change['audience'] ?? 'all', ['admin', 'all'], true);
        });

        $this->assertTrue($hasAdminChange);
    }

    public function test_customer_does_not_receive_admin_only_release_notes(): void
    {
        $customer = User::factory()->create([
            'role' => UserRole::Customer,
            'last_seen_version' => null,
        ]);

        /** @var ChangelogService $service */
        $service = app(ChangelogService::class);

        $unseen = $service->getUnseenReleaseForUser($customer);

        $this->assertNotNull($unseen);

        // Nenhuma alteração exibida para o cliente pode ter audience === 'admin'
        foreach ($unseen['changes'] as $change) {
            $this->assertNotEquals('admin', $change['audience'] ?? null, 'Cliente não deve ver notas de alteração exclusivas do admin.');
        }
    }

    public function test_user_with_same_or_higher_version_does_not_receive_unseen_release(): void
    {
        $currentVersion = config('changelog.current_version');

        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'last_seen_version' => $currentVersion,
        ]);

        /** @var ChangelogService $service */
        $service = app(ChangelogService::class);

        $unseen = $service->getUnseenReleaseForUser($admin);

        $this->assertNull($unseen);
    }

    public function test_authenticated_user_can_dismiss_changelog(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Customer,
            'last_seen_version' => '1.0.0',
        ]);

        $currentVersion = config('changelog.current_version');

        $response = $this->actingAs($user)->postJson(route('changelog.dismiss'), [
            'version' => $currentVersion,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'last_seen_version' => $currentVersion,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'last_seen_version' => $currentVersion,
        ]);
    }

    public function test_authenticated_user_can_view_history_json(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Customer,
        ]);

        $response = $this->actingAs($user)->getJson(route('changelog.history'));

        $response->assertOk()
            ->assertJsonStructure([
                'current_version',
                'releases',
            ]);
    }

    public function test_admin_dashboard_renders_dynamic_version_and_modal(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'last_seen_version' => null,
        ]);

        $currentVersion = config('changelog.current_version');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('v'.$currentVersion);
        $response->assertSee('O que há de novo na KL Tecnologia');
    }

    public function test_customer_downloads_page_renders_modal_for_unseen_release(): void
    {
        $customer = User::factory()->create([
            'role' => UserRole::Customer,
            'last_seen_version' => null,
        ]);

        $response = $this->actingAs($customer)->get(route('customer.downloads'));

        $response->assertOk();
        $response->assertSee('O que há de novo na KL Tecnologia');
        $response->assertSee('isOpen: true', false);
    }

    public function test_customer_downloads_page_keeps_modal_closed_when_already_seen(): void
    {
        $currentVersion = config('changelog.current_version');

        $customer = User::factory()->create([
            'role' => UserRole::Customer,
            'last_seen_version' => $currentVersion,
        ]);

        $response = $this->actingAs($customer)->get(route('customer.downloads'));

        $response->assertOk();
        $response->assertSee('isOpen: false', false);
    }

    public function test_guest_and_customer_cannot_access_admin_novidades_page(): void
    {
        $this->get(route('admin.changelog.index'))
            ->assertRedirect(route('login'));

        $customer = User::factory()->create([
            'role' => UserRole::Customer,
        ]);

        $this->actingAs($customer)
            ->get(route('admin.changelog.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_novidades_page_with_full_release_timeline(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.changelog.index'));

        $response->assertOk();
        $response->assertSee('Novidades');
        $response->assertSee('Histórico de Lançamentos');
        $response->assertSee('v2.1.0');
        $response->assertSee('v2.0.0');
        $response->assertSee('v1.5.0');
        $response->assertSee('v1.3.0');
        $response->assertSee('v1.1.0');
        $response->assertSee('v1.0.0');
        $response->assertSee('Pré-visualizar Modal');
    }

    public function test_admin_sidebar_renders_novidades_menu_item(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('admin.changelog.index'));
        $response->assertSee('Novidades');
    }
}
