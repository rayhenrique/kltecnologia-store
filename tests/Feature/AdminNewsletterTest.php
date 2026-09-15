<?php

namespace Tests\Feature;

use App\Models\NewsletterSendLog;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNewsletterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->customer()->create();
    }

    public function test_guest_cannot_access_newsletter_admin_routes(): void
    {
        $subscriber = NewsletterSubscriber::create(['email' => 'lead@example.com']);

        $this->get(route('admin.newsletter.index'))->assertRedirect(route('login'));
        $this->get(route('admin.newsletter.create'))->assertRedirect(route('login'));
        $this->get(route('admin.newsletter.show', $subscriber))->assertRedirect(route('login'));
        $this->get(route('admin.newsletter.edit', $subscriber))->assertRedirect(route('login'));
        $this->post(route('admin.newsletter.store'), ['email' => 'novo@example.com'])->assertRedirect(route('login'));
        $this->put(route('admin.newsletter.update', $subscriber), ['email' => 'novo@example.com'])->assertRedirect(route('login'));
        $this->post(route('admin.newsletter.toggle-status', $subscriber))->assertRedirect(route('login'));
        $this->delete(route('admin.newsletter.destroy', $subscriber))->assertRedirect(route('login'));
        $this->get(route('admin.newsletter.export'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_newsletter_admin_routes(): void
    {
        $subscriber = NewsletterSubscriber::create(['email' => 'lead@example.com']);

        $this->actingAs($this->customer)->get(route('admin.newsletter.index'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.newsletter.create'))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.newsletter.show', $subscriber))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.newsletter.edit', $subscriber))->assertForbidden();
        $this->actingAs($this->customer)->post(route('admin.newsletter.store'), ['email' => 'novo@example.com'])->assertForbidden();
        $this->actingAs($this->customer)->put(route('admin.newsletter.update', $subscriber), ['email' => 'novo@example.com'])->assertForbidden();
        $this->actingAs($this->customer)->post(route('admin.newsletter.toggle-status', $subscriber))->assertForbidden();
        $this->actingAs($this->customer)->delete(route('admin.newsletter.destroy', $subscriber))->assertForbidden();
        $this->actingAs($this->customer)->get(route('admin.newsletter.export'))->assertForbidden();
    }

    public function test_admin_can_view_newsletter_index_with_search_and_filters(): void
    {
        NewsletterSubscriber::create(['email' => 'carlos@kltecnologia.com', 'is_active' => true]);
        NewsletterSubscriber::create(['email' => 'maria@gmail.com', 'is_active' => false]);

        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.index'));

        $response->assertOk()
            ->assertSee('Newsletter & Leads', false)
            ->assertSee('carlos@kltecnologia.com')
            ->assertSee('maria@gmail.com')
            ->assertSee('Novo Inscrito');

        // Busca textual
        $searchResponse = $this->actingAs($this->admin)->get(route('admin.newsletter.index', ['q' => 'carlos']));
        $searchResponse->assertOk()
            ->assertSee('carlos@kltecnologia.com')
            ->assertDontSee('maria@gmail.com');

        // Filtro por status ativo
        $activeResponse = $this->actingAs($this->admin)->get(route('admin.newsletter.index', ['status' => 'active']));
        $activeResponse->assertOk()
            ->assertSee('carlos@kltecnologia.com')
            ->assertDontSee('maria@gmail.com');
    }

    public function test_admin_can_view_create_subscriber_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.create'));

        $response->assertOk()
            ->assertSee('Cadastrar Novo Inscrito')
            ->assertSee('Endereço de E-mail');
    }

    public function test_admin_can_store_new_subscriber_with_validation(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.store'), [
            'email' => 'lead.novo@example.com',
            'is_active' => '1',
            'subscribed_at' => Carbon::now()->format('Y-m-d\TH:i'),
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'lead.novo@example.com')->first();
        $this->assertNotNull($subscriber);
        $this->assertTrue($subscriber->is_active);

        $response->assertRedirect(route('admin.newsletter.show', $subscriber))
            ->assertSessionHas('success');

        // Tentar cadastrar o mesmo e-mail novamente deve falhar na validação única
        $failResponse = $this->actingAs($this->admin)->post(route('admin.newsletter.store'), [
            'email' => 'lead.novo@example.com',
        ]);
        $failResponse->assertSessionHasErrors(['email']);
    }

    public function test_admin_storing_previously_soft_deleted_subscriber_restores_it(): void
    {
        $old = NewsletterSubscriber::create(['email' => 'antigo@example.com']);
        $old->delete();
        $this->assertSoftDeleted($old);

        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.store'), [
            'email' => 'antigo@example.com',
            'is_active' => '1',
        ]);

        $this->assertNotSoftDeleted('newsletter_subscribers', ['email' => 'antigo@example.com']);
        $subscriber = NewsletterSubscriber::where('email', 'antigo@example.com')->first();
        $response->assertRedirect(route('admin.newsletter.show', $subscriber));
    }

    public function test_admin_can_view_subscriber_details_and_send_logs(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'cliente.lead@example.com',
            'is_active' => true,
            'ip_address' => '127.0.0.1',
        ]);

        $product = Product::factory()->create(['title' => 'Sistema ERP em Laravel']);

        NewsletterSendLog::create([
            'email' => $subscriber->email,
            'notifiable_type' => Product::class,
            'notifiable_id' => $product->id,
            'sent_at' => Carbon::now(),
            'status' => 'sent',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.show', $subscriber));

        $response->assertOk()
            ->assertSee('cliente.lead@example.com')
            ->assertSee('127.0.0.1')
            ->assertSee('Sistema ERP em Laravel')
            ->assertSee('Novo Produto')
            ->assertSee('Enviado')
            ->assertSee($subscriber->unsubscribe_url);
    }

    public function test_admin_can_view_edit_subscriber_page(): void
    {
        $subscriber = NewsletterSubscriber::create(['email' => 'editar@example.com']);

        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.edit', $subscriber));

        $response->assertOk()
            ->assertSee('Editar Inscrito')
            ->assertSee('editar@example.com');
    }

    public function test_admin_can_update_subscriber(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'antigo.email@example.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.newsletter.update', $subscriber), [
            'email' => 'novo.email@example.com',
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.newsletter.show', $subscriber))
            ->assertSessionHas('success');

        $subscriber->refresh();
        $this->assertSame('novo.email@example.com', $subscriber->email);
        $this->assertFalse($subscriber->is_active);
        $this->assertNotNull($subscriber->unsubscribed_at);
    }

    public function test_admin_can_toggle_subscriber_status(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'toggle@example.com',
            'is_active' => true,
        ]);

        // Desativar
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.toggle-status', $subscriber));
        $response->assertRedirect();
        $this->assertFalse($subscriber->fresh()->is_active);
        $this->assertNotNull($subscriber->fresh()->unsubscribed_at);

        // Reativar
        $response2 = $this->actingAs($this->admin)->post(route('admin.newsletter.toggle-status', $subscriber));
        $response2->assertRedirect();
        $this->assertTrue($subscriber->fresh()->is_active);
        $this->assertNull($subscriber->fresh()->unsubscribed_at);
    }

    public function test_admin_can_delete_subscriber(): void
    {
        $subscriber = NewsletterSubscriber::create(['email' => 'remover@example.com']);

        $response = $this->actingAs($this->admin)->delete(route('admin.newsletter.destroy', $subscriber));

        $response->assertRedirect(route('admin.newsletter.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('newsletter_subscribers', ['id' => $subscriber->id]);
    }

    public function test_admin_can_export_subscribers_csv(): void
    {
        NewsletterSubscriber::create(['email' => 'export1@example.com']);
        NewsletterSubscriber::create(['email' => 'export2@example.com']);

        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.export'));

        $response->assertOk();
        $this->assertTrue($response->headers->contains('content-type', 'text/csv; charset=UTF-8'));
    }
}
