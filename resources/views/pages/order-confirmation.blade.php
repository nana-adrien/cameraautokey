@extends('layouts.site')
@section('title', 'Demande enregistrée')

@section('content')
<nav class="step-bar" aria-label="Étapes de la demande">
    <div class="step-tab done"><span class="sn">✓</span><span class="sl">Configuration</span></div>
    <div class="step-tab done"><span class="sn">✓</span><span class="sl">Localisation</span></div>
    <div class="step-tab done"><span class="sn">✓</span><span class="sl">Validation</span></div>
    <div class="step-tab active"><span class="sn">4</span><span class="sl">Confirmation</span></div>
</nav>
<div class="content-wrap" style="max-width:760px">
    <x-sc icon="fas fa-check" title="Demande enregistrée" sub="Merci pour votre confiance">
        <div style="text-align:center;padding:10px 0">
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h2 style="font-family:var(--fb);font-size:24px;font-weight:800;color:var(--navy)">Merci {{ \Illuminate\Support\Str::before($order->customer_name, ' ') }} !</h2>
            <p style="color:var(--muted);max-width:480px;margin:8px auto 0">Un conseiller vous appelle très rapidement pour confirmer le tarif et l’horaire d’intervention. Un e-mail de confirmation vous sera envoyé.</p>
            <div class="success-receipt">
                <div class="receipt-row"><span class="receipt-lbl">Référence</span><span class="receipt-val">{{ $order->reference }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Statut</span><span class="badge-status status-{{ $order->status }}">{{ $order->statusLabel() }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Véhicule</span><span class="receipt-val">{{ $order->brand_model }} ({{ $order->year }}) — {{ $order->plate }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Besoin</span><span class="receipt-val">{{ $order->label('need') }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Clé</span><span class="receipt-val">{{ $order->label('key_type') }} × {{ $order->quantity }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Adresse</span><span class="receipt-val">{{ $order->address }}, {{ $order->district }}, {{ $order->city }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Urgence</span><span class="receipt-val">{{ $order->emergency ? 'Oui' : 'Non' }}</span></div>
            </div>
            <p style="font-size:13px;color:var(--muted)">Conservez votre référence pour <a href="{{ route('orders.track') }}" style="color:var(--navy);font-weight:700">suivre votre demande</a>.</p>
            <a href="{{ route('home') }}" class="btn-red" style="margin-top:16px">Retour à l’accueil</a>
        </div>
    </x-sc>
</div>
@endsection
