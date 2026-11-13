<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderPdf;
use App\Services\PriceCalculator;
use App\Services\TelegramNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function store(Request $request, PriceCalculator $calculator): RedirectResponse
    {
        $pricing = config('pricing');

        $data = $request->validate([
            'vehicle_type' => ['required', Rule::in(array_keys($pricing['vehicle_types']))],
            'need' => ['required', Rule::in(array_keys($pricing['needs']))],
            'key_type' => ['required', Rule::in(array_keys($pricing['key_types']))],
            'desired_key' => ['required', Rule::in(array_keys($pricing['desired_keys']))],
            'quantity' => ['required', 'integer', 'in:1,2'],
            'warranty_extension' => ['boolean'],
            'emergency' => ['boolean'],
            'plate' => ['required', 'string', 'max:20'],
            'brand_model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1970', 'max:'.(now()->year + 1)],
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['required', 'email', 'max:150'],
            'address' => ['required', 'string', 'max:200'],
            'district' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'accept_terms' => ['accepted'],
        ], [], [
            'plate' => 'immatriculation', 'brand_model' => 'marque / modèle', 'year' => 'année',
            'customer_name' => 'nom', 'customer_phone' => 'téléphone', 'customer_email' => 'e-mail',
            'address' => 'adresse', 'district' => 'quartier', 'city' => 'ville',
            'accept_terms' => 'conditions générales',
        ]);

        $data['warranty_extension'] = $request->boolean('warranty_extension');
        $data['emergency'] = $request->boolean('emergency');
        $data['plate'] = strtoupper($data['plate']);
        unset($data['accept_terms']);

        // Le prix est toujours recalculé côté serveur : on ne fait pas confiance au navigateur.
        $data['estimated_price'] = $calculator->estimate($data);

        $order = Order::create($data);

        // Notification interne : la demande part vers Telegram avec son PDF.
        // Le visiteur ne voit jamais ce canal, et un échec ne bloque pas sa demande.
        app(TelegramNotifier::class)->sendOrder($order);

        return redirect(URL::signedRoute('orders.confirmation', $order));
    }

    public function confirmation(Order $order): View
    {
        return view('pages.order-confirmation', compact('order'));
    }

    public function pdf(Order $order, OrderPdf $pdf): Response
    {
        return response($pdf->render($order), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="demande-'.$order->reference.'.pdf"',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function trackForm(): View
    {
        return view('pages.track');
    }

    public function track(Request $request): View
    {
        $data = $request->validate([
            'reference' => ['required', 'string', 'max:30'],
            'customer_email' => ['required', 'email'],
        ]);

        $order = Order::with('technician')
            ->where('reference', strtoupper(trim($data['reference'])))
            ->where('customer_email', $data['customer_email'])
            ->first();

        return view('pages.track', [
            'order' => $order,
            'notFound' => $order === null,
        ]);
    }
}
