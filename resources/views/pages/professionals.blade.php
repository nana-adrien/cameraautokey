@extends('layouts.site')
@section('title', 'Professionnels & flottes')

@section('content')
@include('partials.page-hero', ['title' => 'Espace professionnels', 'subtitle' => 'Garages, loueurs, concessions et gestionnaires de flottes : un partenaire unique pour toutes vos clés.'])
<section class="section">
    <div class="container order-layout">
        <div>
            <div class="grid grid-2">
                <div class="card"><div class="icon">🚐</div><h3>Flottes d’entreprise</h3><p class="muted">Gestion centralisée des clés de vos véhicules de service et utilitaires.</p></div>
                <div class="card"><div class="icon">🏢</div><h3>Garages & concessions</h3><p class="muted">Sous-traitance de la programmation de clés et des perte totales.</p></div>
                <div class="card"><div class="icon">📄</div><h3>Facturation mensuelle</h3><p class="muted">Contrats cadre, tarifs dégressifs et facture récapitulative.</p></div>
                <div class="card"><div class="icon">🤝</div><h3>Interlocuteur dédié</h3><p class="muted">Un chargé de compte pour suivre vos demandes.</p></div>
            </div>
        </div>
        <div>
            <h3>Demander un devis professionnel</h3>
            @include('partials.message-form', ['type' => 'professionnel', 'messageLabel' => 'Votre besoin (nombre de véhicules, marques…)', 'button' => 'Envoyer ma demande'])
        </div>
    </div>
</section>
@endsection
