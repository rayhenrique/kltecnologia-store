<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_newsletter_admin_routes(): void
    {
        $this->get(route('admin.newsletter.index'))->assertRedirect(route('login'));
        $this->get(route('admin.newsletter.export'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_newsletter_admin_routes(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.newsletter.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.newsletter.export'))->assertForbidden();
    }

    public function test_admin_can_view_subscribers_list_and_metrics(): void
    {
        $admin = User::factory()->admin()->create();

        NewsletterSubscriber::create([
            'email' => 'joao@example.com',
            'is_active' => true,
            'subscribed_at' => Carbon::now(),
        ]);

        NewsletterSubscriber::create([
            'email' => 'maria@example.com',
            'is_active' => false,
            'subscribed_at' => Carbon::now()->subMonth(),
            'unsubscribed_at' => Carbon::now()->subDays(2),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.newsletter.index'));

        $response->assertOk();
        $response->assertSee('Newsletter');
        $response->assertSee('Leads');
        $response->assertSee('joao@example.com');
        $response->assertSee('maria@example.com');
        $response->assertSee('Exportar Lista (CSV)');
    }

    public function test_admin_can_filter_subscribers_by_search(): void
    {
        $admin = User::factory()->admin()->create();

        NewsletterSubscriber::create(['email' => 'alfa@empresa.com', 'is_active' => true]);
        NewsletterSubscriber::create(['email' => 'beta@outra.com', 'is_active' => true]);

        $response = $this->actingAs($admin)->get(route('admin.newsletter.index', ['search' => 'alfa']));

        $response->assertOk();
        $response->assertSee('alfa@empresa.com');
        $response->assertDontSee('beta@outra.com');
    }

    public function test_admin_can_filter_subscribers_by_status(): void
    {
        $admin = User::factory()->admin()->create();

        NewsletterSubscriber::create(['email' => 'lead-ativo@empresa.com', 'is_active' => true]);
        NewsletterSubscriber::create(['email' => 'lead-inativo@empresa.com', 'is_active' => false]);

        $response = $this->actingAs($admin)->get(route('admin.newsletter.index', ['status' => 'inactive']));

        $response->assertOk();
        $response->assertSee('lead-inativo@empresa.com');
        $response->assertDontSee('lead-ativo@empresa.com');
    }

    public function test_admin_can_export_subscribers_to_csv(): void
    {
        $admin = User::factory()->admin()->create();

        NewsletterSubscriber::create([
            'email' => 'export1@test.com',
            'ip_address' => '127.0.0.1',
            'is_active' => true,
            'subscribed_at' => Carbon::parse('2026-09-10 10:00:00'),
        ]);

        NewsletterSubscriber::create([
            'email' => 'export2@test.com',
            'ip_address' => '192.168.1.1',
            'is_active' => false,
            'subscribed_at' => Carbon::parse('2026-09-11 12:00:00'),
            'unsubscribed_at' => Carbon::parse('2026-09-12 14:00:00'),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.newsletter.export'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment; filename=newsletter-inscritos-', (string) $response->headers->get('content-disposition'));

        $content = $response->streamedContent();

        // Check UTF-8 BOM
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
        $this->assertStringContainsString('export1@test.com', $content);
        $this->assertStringContainsString('export2@test.com', $content);
        $this->assertStringContainsString('Ativo', $content);
        $this->assertStringContainsString('Inativo', $content);
    }

    public function test_admin_can_delete_subscriber(): void
    {
        $admin = User::factory()->admin()->create();

        $subscriber = NewsletterSubscriber::create([
            'email' => 'remover@test.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.newsletter.destroy', $subscriber));

        $response->assertRedirect(route('admin.newsletter.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('newsletter_subscribers', [
            'id' => $subscriber->id,
            'email' => 'remover@test.com',
        ]);
    }
}
