@extends('layouts.site')
@section('title', 'Conditions générales d’intervention')

@section('content')
@include('partials.page-hero', ['title' => 'Conditions générales d’intervention'])
<section class="section">
    <div class="container prose card" style="max-width:860px">
        <h2 style="margin-top:0">1. Demande d’intervention</h2>
        <p>La demande en ligne n’entraîne aucun paiement. Le prix affiché est une estimation confirmée par un conseiller avant le départ du technicien.</p>
        <h2>2. Justificatifs</h2>
        <p>Avant toute fabrication de clé, le client présente la carte grise du véhicule et une pièce d’identité au même nom. À défaut, l’intervention ne peut pas être réalisée.</p>
        <h2>3. Annulation</h2>
        <p>L’annulation est gratuite jusqu’au départ du technicien. Après son départ, des frais de déplacement peuvent être facturés.</p>
        <h2>4. Paiement</h2>
        <p>Le règlement intervient à la fin de la prestation, après validation par le client. Une facture est envoyée par e-mail.</p>
        <h2>5. Garantie</h2>
        <p>Chaque clé fabriquée est garantie un mois contre tout dysfonctionnement électronique. Une extension d’un an peut être souscrite lors de la demande.</p>
        <p><em>Document type à faire valider juridiquement avant mise en production.</em></p>
    </div>
</section>
@endsection
