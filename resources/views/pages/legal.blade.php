@extends('layouts.site')
@section('title', 'Mentions légales')

@section('content')
@include('partials.page-hero', ['image' => 'photos/atelier-cour.webp', 'title' => 'Mentions légales', 'cta' => false, 'trust' => false])
<div class="content-wrap" style="max-width:900px">
    <x-sc num="1" title="Éditeur du site">
        <div class="text-block"><p>{{ $settings['company_name'] }} — {{ $settings['address'] }}<br>E-mail : {{ $settings['email'] }} — Téléphone : {{ $settings['phone'] }}</p><p><em>À compléter : forme juridique, capital, SIRET, RCS, numéro de TVA, directeur de la publication.</em></p></div>
    </x-sc>
    <x-sc num="2" title="Hébergement"><div class="text-block"><p><em>À compléter : nom, adresse et téléphone de l’hébergeur.</em></p></div></x-sc>
    <x-sc num="3" title="Données personnelles">
        <div class="text-block"><p>Les informations saisies dans les formulaires servent uniquement à traiter votre demande d’intervention ou de contact. Vous pouvez exercer vos droits d’accès, de rectification et de suppression en écrivant à {{ $settings['email'] }}.</p></div>
    </x-sc>
</div>
@endsection
