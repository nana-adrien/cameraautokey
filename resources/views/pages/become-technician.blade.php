@extends('layouts.site')
@section('title', 'Devenir intervenant')

@section('content')
@include('partials.page-hero', ['title' => 'Devenir intervenant', 'subtitle' => 'Serrurier automobile ou technicien en électronique embarquée ? Rejoignez notre réseau.'])
<section class="section">
    <div class="container order-layout">
        <div class="prose card">
            <h2 style="margin-top:0">Pourquoi nous rejoindre ?</h2>
            <p>✔ Des demandes qualifiées dans votre zone, sans frais de prospection.</p>
            <p>✔ Une interface pour accepter, suivre et clôturer vos interventions.</p>
            <p>✔ Un paiement rapide après chaque prestation validée.</p>
            <h3>Profil recherché</h3>
            <p>Expérience en taille et programmation de clés, matériel de diagnostic, véhicule équipé, statut professionnel et assurance RC Pro.</p>
        </div>
        <div>
            <h3>Envoyer ma candidature</h3>
            @include('partials.message-form', ['type' => 'intervenant', 'messageLabel' => 'Votre expérience, votre zone et votre matériel', 'button' => 'Postuler'])
        </div>
    </div>
</section>
@endsection
