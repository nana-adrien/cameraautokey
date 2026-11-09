@extends('layouts.site')
@section('title', 'Garage auto à Douala (Ndogsimbi)')
@section('title_suffix', '3K Auto Services')
@section('description', 'Garage 3K Auto Services à Ndogsimbi, Douala : mécanique, vidange, diagnostic électronique, freins, climatisation, caméras. Devis en FCFA avant travaux.')
@section('og_image', config('seo.og_image_garage'))
@section('geo', $settings['garage_lat'].';'.$settings['garage_lng'])

@php
    $gTel = preg_replace('/\s+/', '', $settings['garage_phone']);
    $gWa = $settings['garage_whatsapp'];
    $directions = is_numeric($settings['garage_lat']) ? 'https://www.google.com/maps/dir/?api=1&destination='.$settings['garage_lat'].','.$settings['garage_lng'] : null;
    $atelier = [
        ['f150-pont-elevateur.webp', 'Ford F-150 sur pont élévateur'],
        ['mecanicien-capot-toyota.webp', 'Diagnostic sous le capot d’un Toyota'],
        ['atelier-cour.webp', 'La cour de l’atelier'],
        ['vidange-moteur.webp', 'Vidange moteur'],
        ['installation-camera-recul.webp', 'Installation d’une caméra 360°'],
        ['pont-elevateur-toyota.webp', 'Toyota Hilux sur pont'],
        ['diagnostic-tablette.webp', 'Diagnostic sur tablette'],
        ['equipe-camer-auto-key.webp', 'L’équipe du garage'],
    ];
@endphp

@push('jsonld')
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::garage()) !!}</script>
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::faqFromArray(config('garage.faqs'))) !!}</script>
@endpush

@section('content')
{{-- ─── CARROUSEL DU GARAGE ─── --}}
<section class="hero-section g-hero">
    <div class="hero-slider" aria-hidden="true">
        @foreach (config('garage.hero_slides') as $i => [$photo, $pos])
            <div @class(['hero-slide', 'active' => $i === 0])>
                <img class="hero-slide-img" src="{{ asset('images/photos/'.$photo) }}" alt="" style="object-position:{{ $pos }}" @if ($i) loading="lazy" @else fetchpriority="high" @endif>
            </div>
        @endforeach
    </div>
    <div class="hero-content">
        <span class="garage-auto-eyebrow"><i class="fas fa-tools"></i> Garage automobile · Douala 5e</span>
        <h1>Votre garage auto à Douala<br><span class="hl">3K Auto Services</span></h1>
        <p><strong>Mécanique, vidange, diagnostic électronique, freins, climatisation</strong> — à Ndogsimbi, derrière Overline Voyages. Devis annoncé avant chaque intervention.</p>
        <div class="hero-actions">
            @if ($gWa)<a href="https://wa.me/{{ $gWa }}" class="hero-cta g-cta" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Prendre rendez-vous</a>@endif
            <a href="#prestations" class="hero-cta hero-cta-ghost g-ghost"><i class="fas fa-list"></i> Nos prestations</a>
        </div>
        <div class="hero-badges">
            <span class="hbadge"><i class="fas fa-laptop-medical"></i> Valises de diagnostic</span>
            <span class="hbadge"><i class="fas fa-car"></i> Pont élévateur 4,5 t</span>
            <span class="hbadge"><i class="fas fa-file-invoice"></i> Devis avant travaux</span>
            <span class="hbadge"><i class="far fa-clock"></i> {{ $settings['garage_hours'] }}</span>
        </div>
    </div>
</section>

{{-- ─── PRESTATIONS ─── --}}
<section class="g-section" id="prestations">
    <div class="container">
        <div class="section-title" style="margin-bottom:34px">
            <span class="sub-title">Prestations</span>
            <h2 class="main-title">Tout l’entretien et la réparation de votre véhicule</h2>
        </div>
        <div class="g-services">
            @foreach ($garageServices as $slug => $gs)
                <a class="g-service-card" href="{{ route('garage.service', $slug) }}">
                    <span class="g-service-icon"><i class="{{ $gs['icon'] }}"></i></span>
                    <b>{{ $gs['title'] }}</b>
                    <p>{{ $gs['excerpt'] }}</p>
                    <span class="g-more">En savoir plus →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── POURQUOI 3K ─── --}}
<section class="garage-auto g-why">
    <div class="container">
        <div class="garage-auto-head">
            <img src="{{ asset('images/logo-3k-auto-services.webp') }}" alt="" width="96" height="90" loading="lazy">
            <div>
                <span class="garage-auto-eyebrow"><i class="fas fa-check"></i> Pourquoi 3K Auto Services</span>
                <h2>Un garage qui explique avant de réparer</h2>
                <p>Nous cherchons la vraie panne, nous vous montrons ce qui doit être changé, et vous décidez. Pas de pièce remplacée sans votre accord.</p>
            </div>
        </div>
        <div class="garage-auto-services g-why-grid">
            <div class="ga-service"><span><i class="fas fa-search"></i></span><div><b>Diagnostic d’abord</b><small>Valise électronique et contrôle visuel avant tout devis.</small></div></div>
            <div class="ga-service"><span><i class="fas fa-file-invoice-dollar"></i></span><div><b>Prix en FCFA annoncé</b><small>Le devis est validé avec vous avant de commencer.</small></div></div>
            <div class="ga-service"><span><i class="fas fa-truck-pickup"></i></span><div><b>Gros gabarits bienvenus</b><small>Pick-up, 4x4 et SUV sur pont élévateur 4,5 tonnes.</small></div></div>
            <div class="ga-service"><span><i class="fas fa-key"></i></span><div><b>Clés sur place</b><small>Notre partenaire {{ $settings['company_name'] }} s’occupe aussi de vos clés.</small></div></div>
        </div>
    </div>
</section>

{{-- ─── L'ATELIER ─── --}}
<section class="g-section g-alt" id="atelier">
    <div class="container">
        <div class="section-title" style="margin-bottom:30px">
            <span class="sub-title">L’atelier</span>
            <h2 class="main-title">Le garage en images</h2>
        </div>
        <div class="gallery-grid">
            @foreach ($atelier as [$file, $caption])
                <button type="button" class="gallery-item" data-full="{{ asset('images/photos/'.$file) }}" data-caption="{{ $caption }}">
                    <img src="{{ asset('images/photos/thumbs/'.$file) }}" alt="{{ $caption }}" loading="lazy" width="800" height="600">
                    <span class="gallery-cap"><i class="fas fa-search-plus"></i> {{ $caption }}</span>
                </button>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── PARTENAIRE : CAMER AUTO KEY ─── --}}
<section class="g-partner" aria-labelledby="partner-title">
    <div class="container g-partner-inner">
        <div class="g-partner-media">
            <img src="{{ asset('images/photos/thumbs/cles-toyota-tacoma.webp') }}" alt="Clés Toyota refaites par {{ $settings['company_name'] }}" width="800" height="600" loading="lazy">
            <img src="{{ asset('images/logo-camer-auto-key-160.png') }}" alt="Logo {{ $settings['company_name'] }}" class="g-partner-logo" width="96" height="96" loading="lazy">
        </div>
        <div class="g-partner-text">
            <span class="eyebrow"><i class="fas fa-key"></i> Notre partenaire clés automobiles</span>
            <h2 id="partner-title">Besoin d’une clé ? Pensez à {{ $settings['company_name'] }}</h2>
            <p>Clé perdue, cassée, télécommande qui ne répond plus ou besoin d’un double : notre boutique partenaire, <strong>Boulevard de la République à Akwa</strong>, taille et code les clés de voitures, camions et motos — même sans l’ancienne.</p>
            <ul class="g-partner-list">
                <li><i class="fas fa-check"></i> Copie de clé et clé de secours</li>
                <li><i class="fas fa-check"></i> Remplacement de toutes les clés perdues</li>
                <li><i class="fas fa-check"></i> Codage antidémarrage et télécommandes</li>
                <li><i class="fas fa-check"></i> Clés mains libres et cartes de démarrage</li>
            </ul>
            <div class="hero-actions" style="margin:0">
                <a href="{{ route('home') }}" class="btn-red"><i class="fas fa-key"></i> Découvrir {{ $settings['company_name'] }}</a>
                <a href="{{ route('home') }}#form-area" class="btn-outline g-outline-light">Refaire ma clé →</a>
            </div>
        </div>
    </div>
</section>

{{-- ─── RECRUTEMENT ─── --}}
<section class="g-section" id="recrutement">
    <div class="container">
        <div class="recruit g-recruit">
            <button type="button" class="gallery-item recruit-flyer" data-full="{{ asset('images/photos/recrutement-mecanicien-3k.webp') }}" data-caption="Recrutement — mécanicien automobile">
                <img src="{{ asset('images/photos/recrutement-mecanicien-3k.webp') }}" alt="Affiche de recrutement d’un mécanicien automobile chez {{ $settings['garage_name'] }}" width="1600" height="1600" loading="lazy">
            </button>
            <div>
                <span class="garage-auto-eyebrow"><i class="fas fa-briefcase"></i> Nous recrutons</span>
                <h2 class="g-h2">Mécanicien automobile</h2>
                <ul class="recruit-list">
                    <li><i class="fas fa-check-circle"></i> Permis de conduire</li>
                    <li><i class="fas fa-check-circle"></i> 4 années d’expérience</li>
                    <li><i class="fas fa-check-circle"></i> Formation reconnue en mécanique automobile</li>
                </ul>
                <p style="font-size:14px;margin:16px 0 10px"><b>Envoyez votre CV :</b></p>
                <div class="hero-actions" style="margin:0">
                    @if ($gWa)<a href="https://wa.me/{{ $gWa }}" class="btn-red" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> {{ $settings['garage_phone'] }}</a>@endif
                    <a href="mailto:{{ $settings['email'] }}?subject={{ rawurlencode('Candidature mécanicien automobile') }}" class="btn-outline"><i class="far fa-envelope"></i> {{ $settings['email'] }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── FAQ + ACCÈS ─── --}}
<section class="g-section g-alt">
    <div class="container g-two">
        <div>
            <div class="section-title" style="text-align:left;margin-bottom:20px">
                <span class="sub-title">Questions fréquentes</span>
                <h2 class="main-title" style="font-size:28px">Avant de venir au garage</h2>
            </div>
            <div class="faq-list wide">
                @foreach (config('garage.faqs') as $i => [$q, $a])
                    <details class="faq-item" @if ($i === 0) open @endif><summary>{{ $q }}</summary><div class="faq-a">{{ $a }}</div></details>
                @endforeach
            </div>
        </div>
        <div>
            @include('partials.location-card', [
                'name' => $settings['garage_name'],
                'logo' => asset('images/logo-3k-auto-services.webp'),
                'address' => $settings['garage_address'],
                'hours' => $settings['garage_hours'],
                'phone' => $settings['garage_phone'],
                'lat' => $settings['garage_lat'],
                'lng' => $settings['garage_lng'],
                'mapsUrl' => $settings['garage_maps_url'],
                'streetView' => config('streetview.garage'),
            ])
        </div>
    </div>
</section>

<div class="lightbox" id="lightbox" hidden>
    <button type="button" class="lightbox-close" aria-label="Fermer">&times;</button>
    <figure><img src="" alt=""><figcaption></figcaption></figure>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/lightbox.js') }}?v=1"></script>
@endpush
