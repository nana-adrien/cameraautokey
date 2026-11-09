@extends('layouts.site')
@section('title', 'Mentions légales')

@section('content')
@include('partials.page-hero', ['title' => 'Mentions légales'])
<section class="section">
    <div class="container prose card" style="max-width:860px">
        <h2 style="margin-top:0">Éditeur du site</h2>
        <p>{{ $settings['company_name'] }} — {{ $settings['address'] }}<br>E-mail : {{ $settings['email'] }} — Téléphone : {{ $settings['phone'] }}</p>
        <p><em>À compléter : forme juridique, capital, SIRET, RCS, numéro de TVA, directeur de la publication.</em></p>
        <h2>Hébergement</h2>
        <p><em>À compléter : nom, adresse et téléphone de l’hébergeur.</em></p>
        <h2>Données personnelles</h2>
        <p>Les informations saisies dans les formulaires sont utilisées uniquement pour traiter votre demande d’intervention ou de contact. Vous pouvez exercer vos droits d’accès, de rectification et de suppression en écrivant à {{ $settings['email'] }}.</p>
    </div>
</section>
@endsection
