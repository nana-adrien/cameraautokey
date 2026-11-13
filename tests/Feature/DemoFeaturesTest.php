<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DemoFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_locked_public_feature_shows_development_page(): void
    {
        config(['demo.locked' => ['tracking']]);

        $this->get(route('orders.track'))
            ->assertOk()
            ->assertSee('En cours de')
            ->assertSee('Suivi de demande en ligne')
            ->assertDontSee('Retrouver ma demande');
    }

    public function test_unlocked_feature_works_normally(): void
    {
        config(['demo.locked' => []]);

        $this->get(route('orders.track'))
            ->assertOk()
            ->assertSee('Retrouver ma demande');
    }

    public function test_locked_admin_module_shows_development_page_and_badge(): void
    {
        config(['demo.locked' => ['analytics', 'faqs']]);
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->get(route('admin.analytics'))
            ->assertOk()
            ->assertSee('En cours de développement')
            ->assertSee('Bientôt');

        $this->post(route('admin.faqs.store'), ['question' => 'Q ?', 'answer' => 'R.'])
            ->assertOk()
            ->assertSee('En cours de développement');
        $this->assertDatabaseCount('faqs', 0);
    }

    public function test_presentation_locks_keep_home_contact_and_order_creation(): void
    {
        config(['demo.locked' => ['brands', 'garage_services', 'info_pages', 'tracking', 'orders_admin']]);
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->get(route('home'))->assertOk()->assertDontSee('En cours de développement');
        $this->get(route('contact'))->assertOk()->assertDontSee('En cours de développement');
        $this->get(route('faq'))->assertOk()->assertSee('Pages d’information');
        $this->get(route('garage.service', 'freinage'))->assertOk()->assertSee('Pages des prestations du garage');
        $this->get(route('admin.orders.index'))->assertOk()->assertSee('Gestion des demandes');

        $this->post(route('orders.store'), [
            'vehicle_type' => 'auto', 'need' => 'double', 'key_type' => 'transponder', 'desired_key' => 'generic',
            'quantity' => 1, 'warranty_extension' => 0, 'emergency' => 0,
            'plate' => 'lt 123 ab', 'brand_model' => 'Toyota Corolla', 'year' => 2015,
            'customer_name' => 'Jean Test', 'customer_phone' => '+237 600 00 00 00', 'customer_email' => 'jean@example.com',
            'address' => 'Rue 1', 'district' => 'Akwa', 'city' => 'Douala', 'accept_terms' => 1,
        ])->assertRedirect();
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_locked_telegram_sends_nothing(): void
    {
        Http::fake();
        config([
            'demo.locked' => ['telegram'],
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);

        $this->post(route('contact.send'), [
            'type' => 'contact',
            'name' => 'Marie Test',
            'email' => 'marie@example.com',
            'phone' => '+237 600 00 00 01',
            'subject' => 'Information',
            'message' => 'Bonjour, je souhaite un devis pour une clé de Toyota.',
        ])->assertRedirect();

        $this->assertDatabaseCount('contact_messages', 1);
        Http::assertNothingSent();
    }
}
