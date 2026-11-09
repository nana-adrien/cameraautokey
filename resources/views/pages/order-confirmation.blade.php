@extends('layouts.site')
@section('title', 'Demande enregistrée')

@section('content')
@include('partials.page-hero', ['title' => 'Merci, votre demande est enregistrée !', 'subtitle' => 'Un conseiller vous contacte très rapidement pour confirmer le prix et l’horaire d’intervention.'])
<section class="section">
    <div class="container" style="max-width:760px">
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                <div><span class="muted">Référence</span><h2 style="margin:0">{{ $order->reference }}</h2></div>
                <span class="status status-{{ $order->status }}">{{ $order->statusLabel() }}</span>
            </div>
            <p class="muted" style="margin-top:10px">Conservez cette référence : elle vous permet de <a href="{{ route('orders.track') }}" style="color:var(--red);font-weight:700">suivre votre demande</a>.</p>
            <div class="summary-row"><span>Véhicule</span><span>{{ $order->brand_model }} ({{ $order->year }}) — {{ $order->plate }}</span></div>
            <div class="summary-row"><span>Besoin</span><span>{{ $order->label('need') }}</span></div>
            <div class="summary-row"><span>Clé</span><span>{{ $order->label('key_type') }} · {{ $order->label('desired_key') }} × {{ $order->quantity }}</span></div>
            <div class="summary-row"><span>Adresse</span><span>{{ $order->address }}, {{ $order->postal_code }} {{ $order->city }}</span></div>
            <div class="summary-row"><span>Urgence</span><span>{{ $order->emergency ? 'Oui' : 'Non' }}</span></div>
            <div class="summary-total"><span class="muted">Prix estimé TTC</span><strong>{{ $order->estimated_price }} €</strong></div>
        </div>
        <p class="center" style="margin-top:24px"><a class="btn btn-outline" href="{{ route('home') }}">Retour à l’accueil</a></p>
    </div>
</section>
@endsection
