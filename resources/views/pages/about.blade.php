@extends('layouts.site')
@section('title', 'Qui sommes-nous ?')

@section('content')
@include('partials.page-hero', ['image' => 'photos/equipe-camer-auto-key.webp', 'position' => '50% 30%', 'titleHtml' => 'Qui <em>sommes-nous</em> ?', 'title' => 'À propos', 'subtitle' => e($settings['company_name']).' est un réseau de <strong>serruriers automobiles mobiles</strong>, joignables jour et nuit en France et en Belgique.'])
<div class="content-wrap grid">
    <div>
        <x-sc icon="far fa-chart-bar" title="{{ $settings['company_name'] }} en chiffres">
            <div class="stats-grid">
                <div><div class="stat-val">24/7</div><div class="stat-lbl">Disponibilité</div></div>
                <div><div class="stat-val">45<em>min</em></div><div class="stat-lbl">Délai moyen en urgence</div></div>
                <div><div class="stat-val">100+</div><div class="stat-lbl">Marques couvertes</div></div>
                <div><div class="stat-val">4,9<em>★</em></div><div class="stat-lbl">Satisfaction client</div></div>
            </div>
        </x-sc>
        <x-sc num="1" title="Notre mission">
            <figure style="margin:0 0 16px"><img src="{{ asset('images/photos/equipe-camer-auto-key.webp') }}" alt="L’équipe {{ $settings['company_name'] }}" width="2000" height="1500" loading="lazy" style="width:100%;border-radius:var(--r)"><figcaption style="font-size:12.5px;color:var(--muted);margin-top:6px">L’équipe {{ $settings['company_name'] }} dans notre atelier.</figcaption></figure>
            <div class="text-block">
                <p>Rendre la reproduction et la programmation de clés aussi simples qu’une course de VTC : une demande en ligne, un prix clair, un technicien qui vient à vous.</p>
                <p>Nos intervenants sont formés aux technologies des principaux constructeurs et équipés de véhicules-ateliers. Ils interviennent chez les particuliers comme auprès des flottes d’entreprise.</p>
            </div>
        </x-sc>
        <x-sc num="2" title="Nos engagements">
            <div class="steps-grid">
                <div class="step-card"><span class="n"><i class="fas fa-euro-sign"></i></span><b>Transparence</b><p>Prix annoncé avant toute intervention, sans frais cachés.</p></div>
                <div class="step-card"><span class="n"><i class="fas fa-shield-alt"></i></span><b>Sécurité</b><p>Contrôle de la carte grise et de l’identité avant chaque fabrication de clé.</p></div>
                <div class="step-card"><span class="n"><i class="fas fa-bolt"></i></span><b>Réactivité</b><p>Un technicien proche de chez vous, de jour comme de nuit.</p></div>
                <div class="step-card"><span class="n"><i class="fas fa-award"></i></span><b>Garantie</b><p>Chaque clé fabriquée est garantie un mois, extensible à un an.</p></div>
            </div>
        </x-sc>
    </div>
    <aside>@include('partials.side-cta')</aside>
</div>
@endsection
