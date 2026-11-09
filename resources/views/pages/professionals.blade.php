@extends('layouts.site')
@section('title', 'Clés pour flottes et professionnels à Douala')
@section('description', 'Taxis, transporteurs, loueurs, entreprises : clés de secours pour toute votre flotte, codage et dépannage, avec un interlocuteur unique à Douala.')

@section('content')
@include('partials.page-hero', ['image' => 'photos/atelier-cour.webp', 'titleHtml' => 'Espace <em>professionnels</em>', 'title' => 'Professionnels', 'subtitle' => 'Garages, loueurs, concessions et gestionnaires de flottes : <strong>un partenaire unique</strong> pour toutes vos clés.'])
<div class="content-wrap grid">
    <div>
        <x-sc num="1" title="Nos solutions pour les entreprises">
            <div class="steps-grid">
                <div class="step-card"><span class="n"><i class="fas fa-truck"></i></span><b>Flottes d’entreprise</b><p>Gestion centralisée des clés de vos véhicules de service et utilitaires.</p></div>
                <div class="step-card"><span class="n"><i class="fas fa-warehouse"></i></span><b>Garages &amp; concessions</b><p>Sous-traitance de la programmation et des pertes totales.</p></div>
                <div class="step-card"><span class="n"><i class="fas fa-file-invoice"></i></span><b>Facturation mensuelle</b><p>Contrats cadre, tarifs dégressifs, facture récapitulative.</p></div>
                <div class="step-card"><span class="n"><i class="fas fa-headset"></i></span><b>Interlocuteur dédié</b><p>Un chargé de compte pour suivre toutes vos demandes.</p></div>
            </div>
        </x-sc>
        <x-sc num="2" title="Demander un devis professionnel" sub="Réponse sous 24 heures ouvrées">
            @include('partials.message-form', ['type' => 'professionnel', 'messageLabel' => 'Votre besoin (nombre de véhicules, marques…)', 'button' => 'Envoyer ma demande'])
        </x-sc>
    </div>
    <aside>@include('partials.side-cta')</aside>
</div>
@endsection
