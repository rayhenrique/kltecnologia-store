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
        $this->postJson(route('admin.changelog.dismiss'))
            ->assertUnauthorized();

        $this->getJson(route('admin.changelog.history'))
            ->assertUnauthorized();

        $this->get(route('admin.changelog.index'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_or_dismiss_changelog(): void
    {
        $customer = User::factory()->create([
            'role' => UserRole::Customer,
        ]);

        $this->actingAs($customer)
            ->postJson(route('admin.changelog.dismiss'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->getJson(route('admin.changelog.history'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('admin.changelog.index'))
            ->assertForbidden();
    }

    public function test_customer_receives_no_releases_or_unseen_notifications_from_service(): void
    {
        $customer = User::factory()->create([
            'role' => UserRole::Customer,
            'last_seen_version' => null,
        ]);

        /** @var ChangelogService $service */
        $service = app(ChangelogService::class);

        $this->assertEmpty($service->getAllReleasesForUser($customer));
        $this->assertNull($service->getUnseenReleaseForUser($customer));
        $this->assertNull($service->getLatestVersionForUser($customer));
    }

    public function test_customer_pages_never_render_changelog_modal(): void
    {
        $customer = User::factory()->create([
            'role' => UserRole::Customer,
            'last_seen_version' => null,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.downloads'))
            ->assertOk()
            ->assertDontSee('O que há de novo na KL Tecnologia');

        $this->actingAs($customer)
            ->get(route('storefront.index'))
            ->assertOk()
            ->assertDontSee('O que há de novo na KL Tecnologia');
    }

    public function test_admin_receives_release_notes(): void
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
        $this->assertNotEmpty($service->getAllReleasesForUser($admin));
    }

    public function test_admin_with_same_or_higher_version_does_not_receive_unseen_release(): void
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

    public function test_admin_can_dismiss_changelog(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'last_seen_version' => '1.0.0',
        ]);

        $currentVersion = config('changelog.current_version');

        $response = $this->actingAs($admin)->postJson(route('admin.changelog.dismiss'), [
            'version' => $currentVersion,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'last_seen_version' => $currentVersion,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'last_seen_version' => $currentVersion,
        ]);
    }

    public function test_admin_can_view_history_json(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->getJson(route('admin.changelog.history'));

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
