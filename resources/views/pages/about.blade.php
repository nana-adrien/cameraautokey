@extends('layouts.site')
@section('title', 'Qui sommes-nous ?')

@section('content')
@include('partials.page-hero', ['title' => 'Qui sommes-nous ?', 'subtitle' => $settings['company_name'].' est un réseau de serruriers automobiles mobiles, joignables jour et nuit.'])
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="align-items:center;gap:40px">
            <div class="prose">
                <h2>Notre mission</h2>
                <p>Rendre la reproduction et la programmation de clés aussi simple qu’une course en VTC : une demande en ligne, un prix clair, un technicien qui vient à vous.</p>
                <p>Nos intervenants sont formés aux technologies des principaux constructeurs et équipés de véhicules-ateliers. Ils interviennent chez les particuliers comme auprès des flottes d’entreprise.</p>
            </div>
            <div class="grid grid-2">
                <div class="card center"><div style="font-size:2rem;font-weight:800;color:var(--red)">24/7</div><span class="muted">Disponibilité</span></div>
                <div class="card center"><div style="font-size:2rem;font-weight:800;color:var(--red)">45 min</div><span class="muted">Délai moyen en urgence</span></div>
                <div class="card center"><div style="font-size:2rem;font-weight:800;color:var(--red)">49+</div><span class="muted">Marques couvertes</span></div>
                <div class="card center"><div style="font-size:2rem;font-weight:800;color:var(--red)">4,8/5</div><span class="muted">Satisfaction client</span></div>
            </div>
        </div>
        <div class="section-head" style="margin-top:70px">
            <div class="eyebrow">Nos engagements</div>
            <h2>Pourquoi nous choisir ?</h2>
        </div>
        <div class="grid grid-3">
            <div class="card"><div class="icon">💶</div><h3>Transparence</h3><p class="muted">Prix annoncé avant toute intervention, sans frais cachés.</p></div>
            <div class="card"><div class="icon">🛡️</div><h3>Sécurité</h3><p class="muted">Contrôle de la carte grise et de l’identité avant chaque fabrication de clé.</p></div>
            <div class="card"><div class="icon">⚡</div><h3>Réactivité</h3><p class="muted">Un technicien proche de chez vous, de jour comme de nuit.</p></div>
        </div>
    </div>
</section>
@endsection
