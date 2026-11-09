@extends('layouts.site')
@section('title', 'Conditions générales d’intervention')

@section('content')
@include('partials.page-hero', ['titleHtml' => 'Conditions générales <em>d’intervention</em>', 'title' => 'Conditions générales d’intervention', 'cta' => false, 'trust' => false])
<div class="content-wrap" style="max-width:900px">
    <x-sc num="1" title="Demande d’intervention"><div class="text-block"><p>La demande en ligne n’entraîne aucun paiement. Le tarif est confirmé par un conseiller avant le départ du technicien.</p></div></x-sc>
    <x-sc num="2" title="Justificatifs"><div class="text-block"><p>Avant toute fabrication de clé, le client présente la carte grise du véhicule et une pièce d’identité au même nom. À défaut, l’intervention ne peut pas être réalisée.</p></div></x-sc>
    <x-sc num="3" title="Annulation"><div class="text-block"><p>L’annulation est gratuite jusqu’au départ du technicien. Après son départ, des frais de déplacement peuvent être facturés.</p></div></x-sc>
    <x-sc num="4" title="Paiement"><div class="text-block"><p>Le règlement intervient à la fin de la prestation, après validation par le client. Une facture est envoyée par e-mail.</p></div></x-sc>
    <x-sc num="5" title="Garantie"><div class="text-block"><p>Chaque clé fabriquée est garantie un mois contre tout dysfonctionnement électronique. Une extension d’un an peut être souscrite lors de la demande.</p><p><em>Document type à faire valider juridiquement avant mise en production.</em></p></div></x-sc>
</div>
@endsection
