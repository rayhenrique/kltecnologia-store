<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Mail\OrderPaidMail;
use App\Mail\OrderPendingMail;
use App\Mail\WelcomeCustomerMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery\MockInterface;
use Tests\TestCase;

class OrderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_and_pending_emails_are_sent_on_new_user_checkout(): void
    {
        Mail::fake();

        $this->mock(MercadoPagoService::class, function (MockInterface $mock): void {
            $mock->shouldReceive('createPreferenceForOrders')
                ->once()
                ->andReturn([
                    'id' => 'PREF-TEST-123',
                    'init_point' => 'https://mercadopago.com/checkout/test',
                ]);
        });

        $product = Product::factory()->create([
            'price' => 49.90,
            'is_active' => true,
        ]);

        $response = $this->post(route('checkout.process'), [
            'name' => 'Novo Cliente Teste',
            'email' => 'novocliente@example.com',
            'cpf' => '123.456.789-00',
            'phone' => '(11) 99999-8888',
            'password' => 'SenhaForte123!',
            'password_confirmation' => 'SenhaForte123!',
            'terms' => '1',
            'product_id' => $product->id,
        ]);

        $response->assertRedirect('https://mercadopago.com/checkout/test');

        Mail::assertSent(WelcomeCustomerMail::class, function (WelcomeCustomerMail $mail) {
            return $mail->hasTo('novocliente@example.com')
                && $mail->user->name === 'Novo Cliente Teste';
        });

        Mail::assertSent(OrderPendingMail::class, function (OrderPendingMail $mail) use ($product) {
            return $mail->hasTo('novocliente@example.com')
                && $mail->orders->first()->product_id === $product->id;
        });

        Mail::assertNotSent(OrderPaidMail::class);
    }

    public function test_welcome_and_paid_emails_are_sent_on_new_user_free_checkout(): void
    {
        Mail::fake();

        $product = Product::factory()->create([
            'price' => 0.00,
            'is_active' => true,
        ]);

        $response = $this->post(route('checkout.process'), [
            'name' => 'Lead Gratuito',
            'email' => 'leadgratis@example.com',
            'password' => 'SenhaForte123!',
            'password_confirmation' => 'SenhaForte123!',
            'terms' => '1',
            'product_id' => $product->id,
        ]);

        $response->assertRedirect(route('customer.downloads'));

        Mail::assertSent(WelcomeCustomerMail::class, function (WelcomeCustomerMail $mail) {
            return $mail->hasTo('leadgratis@example.com');
        });

        Mail::assertSent(OrderPaidMail::class, function (OrderPaidMail $mail) {
            return $mail->hasTo('leadgratis@example.com');
        });

        Mail::assertNotSent(OrderPendingMail::class);
    }

    public function test_existing_customer_does_not_receive_welcome_email_again(): void
    {
        Mail::fake();

        $user = User::factory()->customer()->create([
            'email' => 'clienteantigo@example.com',
        ]);

        // Existing paid order
        Order::factory()->paid()->create([
            'user_id' => $user->id,
        ]);

        $this->mock(MercadoPagoService::class, function (MockInterface $mock): void {
            $mock->shouldReceive('createPreferenceForOrders')
                ->once()
                ->andReturn([
                    'id' => 'PREF-TEST-456',
                    'init_point' => 'https://mercadopago.com/checkout/test2',
                ]);
        });

        $product = Product::factory()->create([
            'price' => 99.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'terms' => '1',
            'product_id' => $product->id,
        ]);

        $response->assertRedirect('https://mercadopago.com/checkout/test2');

        Mail::assertNotSent(WelcomeCustomerMail::class);
        Mail::assertSent(OrderPendingMail::class, function (OrderPendingMail $mail) {
            return $mail->hasTo('clienteantigo@example.com');
        });
    }

    public function test_order_paid_email_is_sent_when_mercado_pago_webhook_approves_payment(): void
    {
        Mail::fake();
        config(['services.mercado_pago.access_token' => 'TEST-TOKEN', 'services.mercado_pago.webhook_secret' => 'webhook-secret']);

        $user = User::factory()->customer()->create(['email' => 'comprador@example.com']);
        $product = Product::factory()->create(['price' => 75.00]);
        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Pending,
            'amount' => 75.00,
        ]);

        $this->mock(MercadoPagoService::class, function (MockInterface $mock) use ($order): void {
            $mock->shouldReceive('getPayment')
                ->with('PAY12345')
                ->once()
                ->andReturn([
                    'id' => 'PAY12345',
                    'external_reference' => "order:{$order->id}",
                    'status' => 'approved',
                    'transaction_amount' => 75.00,
                    'payment_type_id' => 'pix',
                ]);
        });

        $headers = $this->signedHeaders('PAY12345');

        $response = $this->withHeaders($headers)->postJson('/webhooks/mercado-pago?data.id=PAY12345&type=payment', [
            'type' => 'payment',
            'data' => ['id' => 'PAY12345'],
        ]);

        $response->assertOk();

        $this->assertEquals(OrderStatus::Paid, $order->fresh()->status);

        Mail::assertSent(OrderPaidMail::class, function (OrderPaidMail $mail) use ($order) {
            return $mail->hasTo('comprador@example.com')
                && $mail->orders->first()->id === $order->id;
        });
    }

    public function test_duplicate_webhook_does_not_resend_order_paid_email(): void
    {
        Mail::fake();
        config(['services.mercado_pago.access_token' => 'TEST-TOKEN', 'services.mercado_pago.webhook_secret' => 'webhook-secret']);

        $user = User::factory()->customer()->create(['email' => 'comprador2@example.com']);
        $order = Order::factory()->paid()->create([
            'user_id' => $user->id,
            'amount' => 75.00,
            'gateway_reference' => 'PAYDUP',
            'payment_method' => 'pix',
        ]);

        $this->mock(MercadoPagoService::class, function (MockInterface $mock) use ($order): void {
            $mock->shouldReceive('getPayment')
                ->with('PAYDUP')
                ->once()
                ->andReturn([
                    'id' => 'PAYDUP',
                    'external_reference' => "order:{$order->id}",
                    'status' => 'approved',
                    'transaction_amount' => 75.00,
                    'payment_type_id' => 'pix',
                ]);
        });

        $headers = $this->signedHeaders('PAYDUP');

        $response = $this->withHeaders($headers)->postJson('/webhooks/mercado-pago?data.id=PAYDUP&type=payment', [
            'type' => 'payment',
            'data' => ['id' => 'PAYDUP'],
        ]);

        $response->assertOk();

        // Should not send again since order was already paid
        Mail::assertNotSent(OrderPaidMail::class);
    }

    public function test_admin_marking_order_as_paid_sends_order_paid_email(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create();
        $user = User::factory()->customer()->create(['email' => 'cliente_admin@example.com']);
        $product = Product::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Pending,
            'amount' => 50.00,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.orders.update', $order), [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => OrderStatus::Paid->value,
            'amount' => 50.00,
            'payment_method' => 'pix',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));

        Mail::assertSent(OrderPaidMail::class, function (OrderPaidMail $mail) {
            return $mail->hasTo('cliente_admin@example.com');
        });
    }

    public function test_email_templates_render_with_proper_content_and_links(): void
    {
        $user = User::factory()->create([
            'name' => 'Renata Oliveira',
            'email' => 'renata@example.com',
        ]);

        $product = Product::factory()->create([
            'title' => 'Script SaaS Laravel Pro',
            'price' => 197.00,
        ]);

        $order = Order::factory()->paid()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'amount' => 197.00,
            'payment_method' => 'pix',
        ]);

        // Welcome Mail
        $welcomeMail = new WelcomeCustomerMail($user);
        $welcomeHtml = $welcomeMail->render();
        $this->assertStringContainsString('Renata Oliveira', $welcomeHtml);
        $this->assertStringContainsString('renata@example.com', $welcomeHtml);
        $this->assertStringContainsString(route('customer.downloads'), $welcomeHtml);

        // Pending Mail
        $pendingMail = new OrderPendingMail($user, [$order]);
        $pendingHtml = $pendingMail->render();
        $this->assertStringContainsString('Script SaaS Laravel Pro', $pendingHtml);
        $this->assertStringContainsString('Aguardando Pagamento', $pendingHtml);
        $this->assertStringContainsString(number_format(197.00, 2, ',', '.'), $pendingHtml);

        // Paid Mail
        $paidMail = new OrderPaidMail($user, [$order]);
        $paidHtml = $paidMail->render();
        $this->assertStringContainsString('Script SaaS Laravel Pro', $paidHtml);
        $this->assertStringContainsString('Pagamento Aprovado', $paidHtml);
        $this->assertStringContainsString('PIX', $paidHtml);
        $this->assertStringContainsString(route('customer.downloads'), $paidHtml);
    }

    private function signedHeaders(string $dataId): array
    {
        $timestamp = '1742505638683';
        $requestId = 'request-123';
        $manifest = 'id:'.strtolower($dataId).';request-id:'.$requestId.';ts:'.$timestamp.';';

        return [
            'x-signature' => 'ts='.$timestamp.',v1='.hash_hmac('sha256', $manifest, 'webhook-secret'),
            'x-request-id' => $requestId,
        ];
    }
}
