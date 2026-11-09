@extends('layouts.site')
@section('title', 'Devenir intervenant')

@section('content')
@include('partials.page-hero', ['image' => 'photos/diagnostic-tablette.webp', 'titleHtml' => 'Devenir <em>intervenant</em>', 'title' => 'Devenir intervenant', 'subtitle' => 'Serrurier automobile ou technicien en électronique embarquée ? <strong>Rejoignez notre réseau</strong> et recevez des demandes près de chez vous.', 'cta' => false, 'trust' => false])
<div class="content-wrap grid">
    <div>
        <x-sc num="1" title="Pourquoi nous rejoindre ?">
            <div class="steps-grid">
                <div class="step-card"><span class="n">1</span><b>Demandes qualifiées</b><p>Des interventions dans votre zone, sans frais de prospection.</p></div>
                <div class="step-card"><span class="n">2</span><b>Outil de suivi</b><p>Acceptez, suivez et clôturez vos interventions simplement.</p></div>
                <div class="step-card"><span class="n">3</span><b>Paiement rapide</b><p>Versement après chaque prestation validée.</p></div>
                <div class="step-card"><span class="n">4</span><b>Profil recherché</b><p>Expérience en taille et programmation, matériel de diagnostic, RC Pro.</p></div>
            </div>
        </x-sc>
        <x-sc num="2" title="Envoyer ma candidature">
            @include('partials.message-form', ['type' => 'intervenant', 'messageLabel' => 'Votre expérience, votre zone et votre matériel', 'button' => 'Postuler'])
        </x-sc>
    </div>
    <aside>@include('partials.side-cta', ['sideTitle' => 'Une question ?'])</aside>
</div>
@endsection
