<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderPdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class OrderPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_pdf_is_generated_with_pdf_header(): void
    {
        $order = $this->makeOrder();

        $pdf = app(OrderPdf::class)->render($order);

        $this->assertStringStartsWith('%PDF-', $pdf);
        $this->assertGreaterThan(1000, strlen($pdf));
    }

    public function test_signed_pdf_route_returns_pdf(): void
    {
        $order = $this->makeOrder();

        $response = $this->get(URL::signedRoute('orders.pdf', $order));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_admin_can_download_order_pdf(): void
    {
        $order = $this->makeOrder();
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('admin.orders.pdf', $order));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
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
            'status' => 'pending',
        ]);
    }
}
