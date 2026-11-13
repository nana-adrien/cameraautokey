<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render_with_site_branding(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $order = $this->makeOrder();
        $message = ContactMessage::create([
            'type' => 'contact',
            'name' => 'Marie Test',
            'email' => 'marie@example.com',
            'message' => 'Bonjour, je souhaite un devis.',
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Statistiques visiteurs');

        $this->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee($order->reference);

        $this->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('Télécharger le PDF');

        $this->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('Tous');

        $this->get(route('admin.messages.show', $message))
            ->assertOk()
            ->assertSee('Télécharger le PDF');

        $this->get(route('admin.analytics'))
            ->assertOk()
            ->assertSee('Statistiques visiteurs');
    }

    public function test_login_page_uses_site_logo_and_favicon(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('CAMER AUTO KEY')
            ->assertSee('images/favicon-64.png')
            ->assertSee('admin.css?v=4');
    }

    public function test_confirmation_page_offers_pdf_download(): void
    {
        $order = $this->makeOrder();

        $this->get(URL::signedRoute('orders.confirmation', $order))
            ->assertOk()
            ->assertSee('Télécharger ma demande (PDF)');
    }

    public function test_admin_can_download_message_pdf(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $message = ContactMessage::create([
            'type' => 'contact',
            'name' => 'Marie Test',
            'email' => 'marie@example.com',
            'message' => 'Bonjour, je souhaite un devis.',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.messages.pdf', $message));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    private function makeOrder(): Order
    {
        return Order::create([
            'vehicle_type' => 'auto',
            'need' => 'double',
            'key_type' => 'transponder',
            'desired_key' => 'generic',
            'quantity' => 1,
            'warranty_extension' => false,
            'emergency' => false,
            'plate' => 'LT 123 AB',
            'brand_model' => 'Toyota Corolla',
            'year' => 2015,
            'customer_name' => 'Jean Test',
            'customer_phone' => '+237 600 00 00 00',
            'customer_email' => 'jean@example.com',
            'address' => 'Rue 1',
            'district' => 'Akwa',
            'city' => 'Douala',
            'notes' => 'Clé perdue.',
            'estimated_price' => 40000,
        ]);
    }
}
