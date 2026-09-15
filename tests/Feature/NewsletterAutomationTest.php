<?php

namespace Tests\Feature;

use App\Jobs\SendNewPostNewsletterJob;
use App\Jobs\SendNewProductNewsletterJob;
use App\Mail\NewProductNewsletterMail;
use App\Models\NewsletterSendLog;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Services\NewsletterBroadcastService;
use App\Services\NewsletterService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewsletterAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchasing_customer_is_automatically_subscribed_to_newsletter_on_free_checkout(): void
    {
        $user = User::factory()->customer()->create(['email' => 'comprador-gratis@example.com']);
        $product = Product::factory()->create(['price' => '0.00', 'is_active' => true]);

        $this->actingAs($user)->post(route('checkout.store', $product));

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'comprador-gratis@example.com',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_automatically_subscribed_to_newsletter_on_paid_checkout_submission(): void
    {
        config(['services.mercado_pago.access_token' => 'TEST-TOKEN']);
        Http::fake(['api.mercadopago.com/*' => Http::response(['id' => 'PREF-NEWS-1', 'init_point' => 'https://mercadopago.test/pay/news'], 201)]);

        $product = Product::factory()->create(['price' => '29.90', 'is_active' => true]);

        $this->post(route('checkout.process'), [
            'name' => 'Maria Oliveira',
            'email' => 'maria.newsletter@example.com',
            'cpf' => '123.456.789-11',
            'phone' => '(11) 97777-6666',
            'password' => 'SenhaSegura123!',
            'password_confirmation' => 'SenhaSegura123!',
            'product_id' => $product->id,
            'terms' => '1',
        ])->assertRedirect('https://mercadopago.test/pay/news');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'maria.newsletter@example.com',
            'is_active' => true,
        ]);
    }

    public function test_terms_of_use_displays_newsletter_subscription_clause_and_opt_out(): void
    {
        $response = $this->get(route('terms.index'));

        $response->assertOk()
            ->assertSee('Comunicações, Atualizações de Produtos e Inscrição na Newsletter')
            ->assertSee('Direito de Opt-out (Descadastro em 1 Clique)');
    }

    public function test_valid_unsubscribe_link_deactivates_subscriber_and_shows_confirmation(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'cancelar@example.com',
            'is_active' => true,
            'subscribed_at' => Carbon::now(),
        ]);

        $service = app(NewsletterService::class);
        $url = $service->generateUnsubscribeUrl('cancelar@example.com');

        $response = $this->get($url);

        $response->assertOk()
            ->assertSee('Inscrição cancelada')
            ->assertSee('cancelar@example.com');

        $this->assertFalse($subscriber->fresh()->is_active);
        $this->assertNotNull($subscriber->fresh()->unsubscribed_at);
    }

    public function test_invalid_unsubscribe_token_aborts_with_403(): void
    {
        $response = $this->get(route('newsletter.unsubscribe', [
            'email' => 'teste@example.com',
            'token' => 'token-invalido',
        ]));

        $response->assertForbidden();
    }

    public function test_broadcasting_new_product_enqueues_jobs_for_all_active_subscribers(): void
    {
        Queue::fake();

        NewsletterSubscriber::create(['email' => 'user1@example.com', 'is_active' => true]);
        NewsletterSubscriber::create(['email' => 'user2@example.com', 'is_active' => true]);
        NewsletterSubscriber::create(['email' => 'inativo@example.com', 'is_active' => false]);

        $product = Product::factory()->create(['is_active' => true]);

        $broadcastService = app(NewsletterBroadcastService::class);
        $dispatched = $broadcastService->broadcastNewProduct($product);

        $this->assertEquals(2, $dispatched);
        Queue::assertPushed(SendNewProductNewsletterJob::class, 2);
    }

    public function test_broadcasting_new_post_enqueues_jobs_for_all_active_subscribers(): void
    {
        Queue::fake();

        NewsletterSubscriber::create(['email' => 'sub1@example.com', 'is_active' => true]);
        NewsletterSubscriber::create(['email' => 'sub2@example.com', 'is_active' => true]);

        $post = Post::create([
            'title' => 'Tutorial Laravel 12',
            'category' => 'Tutorial',
            'excerpt' => 'Aprenda Laravel rápido',
            'content' => '<p>Conteúdo do artigo</p>',
            'is_published' => true,
        ]);

        $broadcastService = app(NewsletterBroadcastService::class);
        $dispatched = $broadcastService->broadcastNewPost($post);

        $this->assertEquals(2, $dispatched);
        Queue::assertPushed(SendNewPostNewsletterJob::class, 2);
    }

    public function test_send_new_product_job_sends_email_and_records_send_log(): void
    {
        Mail::fake();

        $product = Product::factory()->create(['is_active' => true]);
        $subscriber = NewsletterSubscriber::create(['email' => 'leitor@example.com', 'is_active' => true]);

        $job = new SendNewProductNewsletterJob($product, $subscriber);
        $job->handle(app(NewsletterService::class));

        Mail::assertSent(NewProductNewsletterMail::class, function ($mail) {
            return $mail->hasTo('leitor@example.com');
        });

        $this->assertDatabaseHas('newsletter_send_logs', [
            'email' => 'leitor@example.com',
            'notifiable_type' => Product::class,
            'notifiable_id' => $product->id,
            'status' => 'sent',
        ]);
    }

    public function test_job_releases_itself_when_daily_limit_is_reached(): void
    {
        config(['newsletter.daily_limit' => 2]);

        // Simula 2 envios hoje
        NewsletterSendLog::create(['email' => 'prev1@example.com', 'sent_at' => Carbon::now(), 'status' => 'sent']);
        NewsletterSendLog::create(['email' => 'prev2@example.com', 'sent_at' => Carbon::now(), 'status' => 'sent']);

        $product = Product::factory()->create(['is_active' => true]);
        $subscriber = NewsletterSubscriber::create(['email' => 'terceiro@example.com', 'is_active' => true]);

        Mail::fake();

        $service = app(NewsletterService::class);
        $this->assertTrue($service->hasReachedDailyLimit());

        // Cria o job com mock parcial para capturar release
        $job = $this->getMockBuilder(SendNewProductNewsletterJob::class)
            ->setConstructorArgs([$product, $subscriber])
            ->onlyMethods(['release'])
            ->getMock();

        $job->expects($this->once())
            ->method('release')
            ->with($this->greaterThan(0));

        $job->handle($service);

        Mail::assertNothingSent();
    }

    public function test_job_does_not_resend_to_same_subscriber_if_already_sent(): void
    {
        Mail::fake();

        $product = Product::factory()->create(['is_active' => true]);
        $subscriber = NewsletterSubscriber::create(['email' => 'ja_enviado@example.com', 'is_active' => true]);

        // Simula log prévio de envio com sucesso
        NewsletterSendLog::create([
            'email' => 'ja_enviado@example.com',
            'notifiable_type' => Product::class,
            'notifiable_id' => $product->id,
            'sent_at' => Carbon::now(),
            'status' => 'sent',
        ]);

        $job = new SendNewProductNewsletterJob($product, $subscriber);
        $job->handle(app(NewsletterService::class));

        Mail::assertNothingSent();
    }
}
