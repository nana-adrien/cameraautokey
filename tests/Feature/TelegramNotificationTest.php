<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_submission_sends_pdf_to_telegram(): void
    {
        Http::fake();
        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);

        $response = $this->post(route('orders.store'), $this->orderPayload());

        $response->assertRedirect();
        $this->assertDatabaseCount('orders', 1);

        Http::assertSent(fn ($request) => str_contains($request->url(), '/bottest-token/sendDocument'));
    }

    public function test_contact_message_sends_telegram_notification(): void
    {
        Http::fake();
        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);

        $response = $this->post(route('contact.send'), [
            'type' => 'contact',
            'name' => 'Marie Test',
            'email' => 'marie@example.com',
            'phone' => '+237 600 00 00 01',
            'subject' => 'Demande d’information',
            'message' => 'Bonjour, je souhaite un devis pour une clé de Toyota.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('contact_messages', 1);

        Http::assertSent(fn ($request) => str_contains($request->url(), '/bottest-token/sendDocument'));
    }

    public function test_newsletter_is_not_sent_to_telegram(): void
    {
        Http::fake();
        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);

        $this->post(route('newsletter'), ['email' => 'newsletter@example.com'])
            ->assertRedirect();

        Http::assertNothingSent();
    }

    private function orderPayload(): array
    {
        return [
            'vehicle_type' => 'auto',
            'need' => 'double',
            'key_type' => 'transponder',
            'desired_key' => 'generic',
            'quantity' => 1,
            'warranty_extension' => 0,
            'emergency' => 0,
            'plate' => 'lt 123 ab',
            'brand_model' => 'Toyota Corolla',
            'year' => 2015,
            'customer_name' => 'Jean Test',
            'customer_phone' => '+237 600 00 00 00',
            'customer_email' => 'jean@example.com',
            'address' => 'Rue 1',
            'district' => 'Akwa',
            'city' => 'Douala',
            'notes' => 'Clé perdue.',
            'accept_terms' => 1,
        ];
    }
}
