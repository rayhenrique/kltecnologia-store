<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_subscribe_to_newsletter_via_form(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'cliente@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'cliente@example.com',
            'is_active' => true,
        ]);
    }

    public function test_user_can_subscribe_via_json_request(): void
    {
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'LEAD@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        // Email should be stored lowercase
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'lead@example.com',
            'is_active' => true,
        ]);
    }

    public function test_duplicate_subscription_is_handled_gracefully(): void
    {
        NewsletterSubscriber::create([
            'email' => 'frequente@example.com',
            'is_active' => true,
            'subscribed_at' => Carbon::now()->subDays(5),
        ]);

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'frequente@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Inscrição confirmada! Você já está em nossa lista de novidades.',
        ]);

        $this->assertEquals(1, NewsletterSubscriber::where('email', 'frequente@example.com')->count());
    }

    public function test_resubscribing_an_inactive_subscriber_reactivates_them(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'inativo@example.com',
            'is_active' => false,
            'subscribed_at' => Carbon::now()->subMonth(),
            'unsubscribed_at' => Carbon::now()->subDays(10),
        ]);

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'inativo@example.com',
        ]);

        $response->assertOk();
        $this->assertTrue($subscriber->fresh()->is_active);
        $this->assertNull($subscriber->fresh()->unsubscribed_at);
    }

    public function test_resubscribing_a_soft_deleted_subscriber_restores_them(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'deletado@example.com',
            'is_active' => true,
            'subscribed_at' => Carbon::now()->subMonth(),
        ]);
        $subscriber->delete();

        $this->assertSoftDeleted('newsletter_subscribers', [
            'email' => 'deletado@example.com',
        ]);

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'deletado@example.com',
        ]);

        $response->assertOk();
        $this->assertNotSoftDeleted('newsletter_subscribers', [
            'email' => 'deletado@example.com',
        ]);
    }

    public function test_invalid_email_fails_validation(): void
    {
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_empty_email_fails_validation(): void
    {
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
