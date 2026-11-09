<div class="table-wrap" style="border:0">
    <table>
        <thead><tr><th>Référence</th><th>Client</th><th>Véhicule</th><th>Besoin</th><th>Ville</th><th>Prix</th><th>Statut</th><th>Date</th><th></th></tr></thead>
        <tbody>
        @forelse ($orders as $order)
            <tr>
                <td><b>{{ $order->reference }}</b>@if ($order->emergency) <span title="Urgence">⚡</span>@endif</td>
                <td>{{ $order->customer_name }}<div class="muted" style="font-size:.8rem">{{ $order->customer_phone }}</div></td>
                <td>{{ $order->brand_model }}<div class="muted" style="font-size:.8rem">{{ $order->plate }}</div></td>
                <td>{{ $order->label('need') }}</td>
                <td>{{ $order->city }}</td>
                <td><b>{{ $order->estimated_price }} €</b></td>
                <td><span class="status status-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                <td class="muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="actions"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-light btn-sm">Ouvrir</a></td>
            </tr>
        @empty
            <tr><td colspan="9" class="muted" style="text-align:center;padding:30px">Aucune demande.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
