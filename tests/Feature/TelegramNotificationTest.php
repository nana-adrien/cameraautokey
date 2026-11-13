<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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

    public function test_admin_button_is_added_only_for_public_urls(): void
    {
        Http::fake();
        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);

        URL::forceRootUrl('http://localhost:8000');
        $this->post(route('orders.store'), $this->orderPayload())->assertRedirect();

        URL::forceRootUrl('https://cameraautokey.cm');
        $this->post(route('orders.store'), $this->orderPayload())->assertRedirect();

        $markups = Http::recorded()->map(fn ($pair) => collect($pair[0]->data())->firstWhere('name', 'reply_markup')['contents'] ?? null)->values();

        $this->assertNull($markups[0]);
        $this->assertSame('cameraautokey.cm', parse_url(json_decode($markups[1], true)['inline_keyboard'][0][0]['url'], PHP_URL_HOST));
    }

    public function test_bot_token_is_not_written_to_logs(): void
    {
        Http::fake(['*' => Http::response(['ok' => false, 'description' => 'Bad Request'], 400)]);
        Log::spy();
        config([
            'services.telegram.bot_token' => 'secret-token',
            'services.telegram.chat_id' => '123456',
        ]);

        $this->post(route('orders.store'), $this->orderPayload())->assertRedirect();

        Http::fake(fn () => throw new ConnectionException('cURL error 28 for https://api.telegram.org/botsecret-token/sendMessage'));
        $this->post(route('orders.store'), $this->orderPayload())->assertRedirect();

        Log::shouldHaveReceived('warning')->withArgs(fn ($message, $context) => str_contains($context['error'], '[TOKEN]'));
        Log::shouldNotHaveReceived('warning', fn ($message, $context) => str_contains($context['error'], 'secret-token'));
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
