@extends('layouts.site')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css">
@endpush

@php
    $tel = preg_replace('/\s+/', '', $settings['phone']);
    $step2Fields = ['customer_name', 'customer_phone', 'customer_email', 'address', 'district', 'city', 'notes'];
    $startStep = $errors->hasAny($step2Fields) ? 2 : 1;
    $v = fn ($name, $default = null) => old($name, $default);
    $keyIcons = [
        'simple' => '<svg viewBox="0 0 64 64" fill="none"><g transform="rotate(-26 32 32)" filter="url(#soft)"><path d="M26 27.5h30a4 4 0 0 1 4 4v2.5H51v5.5h-4v-5h-4v6.5h-4v-6.5h-4v5h-4v-5H26Z" fill="url(#metal)" stroke="#4a5a76" stroke-width="1.5" stroke-opacity=".55" stroke-linejoin="round"/><path d="M27 30.2h29" stroke="#fff" stroke-width="1.5" stroke-opacity=".8" stroke-linecap="round"/><path d="M17 21.5a11 11 0 1 0 0 22a11 11 0 0 0 0-22Zm0 6.4a4.6 4.6 0 1 1 0 9.2a4.6 4.6 0 0 1 0-9.2Z" fill="url(#metal)" stroke="#4a5a76" stroke-width="1.5" stroke-opacity=".55" fill-rule="evenodd"/><path d="M9.8 27.2a10 10 0 0 1 8.2-4" stroke="#fff" stroke-width="2.2" stroke-opacity=".8" stroke-linecap="round"/></g></svg>',
        'transponder' => '<svg viewBox="0 0 64 64" fill="none"><g filter="url(#soft)"><path d="M29 27.5h26a4 4 0 0 1 4 4v2.5H51.5V38h-4.2v-4H43v4.8h-4.2V38H35v-3.5H29Z" fill="url(#metal)" stroke="#4a5a76" stroke-width="1.5" stroke-opacity=".55" stroke-linejoin="round"/><path d="M30 30h26.5" stroke="#fff" stroke-opacity=".75" stroke-width="1.4" stroke-linecap="round"/><rect x="5" y="15.5" width="26" height="31" rx="11" fill="url(#plastic)" stroke="#0b1322" stroke-opacity=".6" stroke-width="1.4"/><rect x="8.8" y="19.3" width="18.4" height="23.4" rx="8" fill="none" stroke="#fff" stroke-opacity=".13" stroke-width="1.4"/><circle cx="12.5" cy="22.5" r="3" fill="#0b1322" stroke="url(#chrome)" stroke-width="2"/><rect x="16.5" y="33.5" width="8" height="5.4" rx="1.3" fill="url(#gold)" stroke="#8a6b2c" stroke-opacity=".55" stroke-width=".9"/><path d="M8 34.6h20" stroke="#000" stroke-opacity=".22" stroke-width="1.1"/><path d="M8 22.5c1.7-3 4.2-4.7 7.4-5.1" stroke="#fff" stroke-opacity=".2" stroke-width="2.6" stroke-linecap="round"/></g></svg>',
        'remote' => '<svg viewBox="0 0 64 64" fill="none"><g filter="url(#soft)"><path d="M27.5 41h9.5v12.5h4v4h-4v3h-9.5z" fill="url(#metal)" stroke="#4a5a76" stroke-opacity=".55" stroke-width="1.4" stroke-linejoin="round"/><path d="M29.2 43h7" stroke="#fff" stroke-opacity=".75" stroke-width="1.3" stroke-linecap="round"/><rect x="16.5" y="4.5" width="31" height="37" rx="10" fill="url(#plastic)" stroke="#0b1322" stroke-opacity=".6" stroke-width="1.4"/><rect x="20" y="8" width="24" height="30" rx="7" fill="none" stroke="#fff" stroke-opacity=".12" stroke-width="1.4"/><circle cx="32" cy="10.5" r="2.6" fill="#0b1322" stroke="url(#chrome)" stroke-width="1.8"/><circle cx="23" cy="13" r="1.4" fill="#e2493f"/><rect x="22.5" y="17.5" width="19" height="7.2" rx="3.6" fill="#0b1526" stroke="#fff" stroke-opacity=".09"/><rect x="22.5" y="26.4" width="19" height="7.2" rx="3.6" fill="#0b1526" stroke="#fff" stroke-opacity=".09"/><rect x="22.5" y="35.3" width="19" height="7.2" rx="3.6" fill="url(#red)" stroke="#7d0f15" stroke-opacity=".6"/><path d="M29.5 21.6v-1.6a2.5 2.5 0 0 1 5 0v1.6M28.8 21.6h6.4v3.6h-6.4z" stroke="#e8eef7" stroke-width="1.4" stroke-linejoin="round"/><path d="M28.8 30.5h6.4v3.6h-6.4zM29.5 30.7v-1.5a2.4 2.4 0 0 1 4.3-1.2" stroke="#e8eef7" stroke-width="1.4" stroke-linejoin="round"/><path d="M30 39.5h4l-2-2.4z" fill="#fff" fill-opacity=".9"/></g></svg>',
        'smart' => '<svg viewBox="0 0 64 64" fill="none"><g filter="url(#soft)"><path d="M32 4.5c13 0 22 8.4 22 20.2 0 11.4-6.6 17.4-11.3 22.1l-2.4 2.4H23.7l-2.4-2.4C16.6 42.1 10 36.1 10 24.7 10 12.9 19 4.5 32 4.5Z" fill="url(#plastic2)" stroke="#0b1322" stroke-opacity=".55" stroke-width="1.4"/><path d="M20 15c3-4.6 7-7 12-7.4" stroke="#fff" stroke-opacity=".2" stroke-width="3.8" stroke-linecap="round"/><path d="M16.5 31.5c1.8 5.2 5 9 9.2 12.2" stroke="#fff" stroke-opacity=".08" stroke-width="2.8" stroke-linecap="round"/><rect x="20" y="21.5" width="7.4" height="10" rx="2.4" fill="#152238" stroke="#fff" stroke-opacity=".22"/><rect x="28.3" y="21.5" width="7.4" height="10" rx="2.4" fill="#152238" stroke="#fff" stroke-opacity=".22"/><rect x="36.6" y="21.5" width="7.4" height="10" rx="2.4" fill="url(#red)" stroke="#7d0f15" stroke-opacity=".6"/><path d="M22.3 26.3v-1.5a1.9 1.9 0 0 1 3.8 0v1.5M21.6 26.3h5.2v3.2h-5.2z" stroke="#e8eef7" stroke-width="1.3" stroke-linejoin="round"/><path d="M30.6 26.3v-1.5a1.9 1.9 0 0 1 3.8 0v1.5M29.9 26.3H35v3.2h-5.1z" stroke="#e8eef7" stroke-width="1.3" stroke-linejoin="round"/><circle cx="40.3" cy="26.5" r="1.4" fill="#fff" fill-opacity=".9"/><circle cx="32" cy="51" r="5.2" fill="none" stroke="url(#chrome)" stroke-width="3.2"/><circle cx="32" cy="51" r="1.8" fill="#0b1322"/></g></svg>',
        'card' => '<svg viewBox="0 0 64 64" fill="none"><g filter="url(#soft)"><rect x="4" y="15" width="56" height="34" rx="7" fill="url(#card)" stroke="#8fa0b7" stroke-width="1.3"/><path d="M4 23.5V22a7 7 0 0 1 7-7h42a7 7 0 0 1 7 7v1.5Z" fill="url(#plastic)"/><rect x="4" y="23.5" width="56" height="2.6" fill="url(#red)"/><rect x="8.5" y="29" width="18" height="12" rx="3" fill="#1a2f5e" stroke="#0b1322" stroke-opacity=".4"/><rect x="8.5" y="30.7" width="18" height="1.8" fill="#fff" fill-opacity=".14"/><path d="M11.8 35.5v-1.6a2.4 2.4 0 0 1 4.8 0v1.6M11 35.5h6.4v3.8H11z" stroke="#e8eef7" stroke-width="1.4" stroke-linejoin="round"/><path d="M20 35.5v-1.6a2.4 2.4 0 0 1 4.8 0v1.6M19.2 35.5h6.4v3.8h-6.4z" stroke="#e8eef7" stroke-width="1.4" stroke-linejoin="round"/><circle cx="52" cy="19.5" r="2.5" fill="#0b1322" stroke="url(#chrome)" stroke-width="1.7"/><rect x="31" y="30.5" width="21" height="3.2" rx="1.6" fill="#c7d2e0" opacity=".9"/><rect x="31" y="36" width="15" height="3.2" rx="1.6" fill="#d8e0ec" opacity=".85"/><path d="M8.5 43.5h47" stroke="#9aa8bd" stroke-opacity=".35" stroke-width=".9"/></g></svg>',
    ];
    $keyGallery = [
        ['cles-toyota-tacoma.webp', 'Clés Toyota Tacoma'],
        ['cles-acura-rdx.webp', 'Clés Acura RDX'],
        ['cles-nissan-juke.webp', 'Clés Nissan Juke'],
        ['cles-toyota-tundra-blanc.webp', 'Clés Toyota Tundra'],
        ['cles-toyota-interieur.webp', 'Clés mains libres Toyota'],
        ['cle-toyota-tundra.webp', 'Double de clé Toyota'],
        ['atelier-camer-auto-key.webp', 'Clés RAM 1500'],
        ['cle-lexus.webp', 'Nouvelle clé Lexus'],
    ];
    $gallery = [
        ['facade-camer-auto-key.webp', 'Notre boutique à Douala'],
        ['equipe-camer-auto-key.webp', 'L’équipe CAMER AUTO KEY'],
        ['taille-cle-condor.webp', 'Taille de clé sur machine'],
        ['diagnostic-tablette.webp', 'Programmation par diagnostic'],
        ['mecanicien-capot-toyota.webp', 'Diagnostic sous le capot'],
        ['installation-camera-recul.webp', 'Installation caméra de recul 360°'],
        ['f150-pont-elevateur.webp', 'Ford F-150 sur pont élévateur'],
        ['atelier-cour.webp', 'Notre atelier'],
    ];
    $vehicleImages = ['auto' => 'voiture.svg', 'pl' => 'camion.svg', 'moto' => 'moto.svg'];
@endphp

@push('jsonld')
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::website()) !!}</script>
@endpush
@push('jsonld')
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::shop()) !!}</script>
@endpush

@section('content')
{{-- Dégradés et ombre partagés par les icônes de clés (SVG inline, viewBox 64) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="metal" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#f4f7fb"/><stop offset=".45" stop-color="#c9d4e2"/><stop offset="1" stop-color="#8d9bb0"/>
        </linearGradient>
        <linearGradient id="plastic" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#3a4a68"/><stop offset="1" stop-color="#141d30"/>
        </linearGradient>
        <linearGradient id="plastic2" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#61779b"/><stop offset="1" stop-color="#2b3a55"/>
        </linearGradient>
        <linearGradient id="chrome" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#aeb9c9"/><stop offset="1" stop-color="#eef2f8"/>
        </linearGradient>
        <linearGradient id="red" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#f25159"/><stop offset="1" stop-color="#b0171f"/>
        </linearGradient>
        <linearGradient id="card" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#ffffff"/><stop offset="1" stop-color="#dde5f0"/>
        </linearGradient>
        <linearGradient id="gold" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#e6c97a"/><stop offset="1" stop-color="#b98f3a"/>
        </linearGradient>
        <filter id="soft" x="-25%" y="-25%" width="150%" height="150%">
            <feDropShadow dx="0" dy="1.3" stdDeviation="1.3" flood-color="#0f1c3a" flood-opacity=".3"/>
        </filter>
    </defs>
</svg>
{{-- ─── HERO ─── --}}
<section class="hero-section">
    <div class="hero-slider" aria-hidden="true">
        @foreach ([
            ['cles-toyota-tacoma.webp', '62% 58%'],
            ['taille-cle-atelier.webp?v=2', '50% 45%'],
            ['cles-toyota-interieur.webp', '55% 50%'],
            ['taille-cle-condor.webp', '60% 40%'],
            ['cles-toyota-tundra-blanc.webp', '50% 55%'],
        ] as $i => [$photo, $pos])
            <div @class(['hero-slide', 'active' => $i === 0])>
                <img class="hero-slide-img" src="{{ asset('images/photos/'.$photo) }}" alt="" style="object-position:{{ $pos }}" @if ($i) loading="lazy" @else fetchpriority="high" @endif>
            </div>
        @endforeach
    </div>
    <div class="hero-bg"></div>
    <div class="hero-content">
        <h1>{{ $settings['hero_title'] }}<br>Une nouvelle clé en <span class="hl">30 minutes</span></h1>
        <p><strong>Taille, codage et remplacement de clés</strong> pour voitures, camions et deux-roues — boutique à Douala, interventions à Yaoundé et dans tout le pays.<br>{{ $settings['hero_subtitle'] }}</p>
        <div class="hero-actions">
            <a href="#form-area" class="hero-cta"><i class="fas fa-key"></i> Refaire ma clé</a>
            @if ($settings['whatsapp'])
                <a href="https://wa.me/{{ $settings['whatsapp'] }}" class="hero-cta hero-cta-ghost" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Photo de ma clé sur WhatsApp</a>
            @endif
        </div>
        <div class="hero-badges">
            <span class="hbadge"><i class="fas fa-key"></i> Clé refaite sans l’originale</span>
            <span class="hbadge"><i class="fas fa-cut"></i> Taille &amp; programmation sur place</span>
            <span class="hbadge"><i class="fas fa-car"></i> Plus de 100 marques</span>
            <span class="hbadge"><i class="fas fa-shield-alt"></i> Garantie 1 mois</span>
            <span class="hbadge"><i class="fas fa-mobile-alt"></i> Orange Money &amp; MTN MoMo</span>
        </div>
    </div>
</section>

{{-- ─── TYPES DE CLÉS ─── --}}
<section class="keys-band" aria-labelledby="keys-band-title">
    <div class="container">
        <div class="keys-band-head">
            <h2 id="keys-band-title">Quelle clé <em>avez-vous ?</em></h2>
            <p>Touchez votre modèle pour préparer votre demande — même si vous n’avez plus la clé.</p>
        </div>
        <div class="keys-band-grid">
            @foreach ($pricing['key_types'] as $key => $opt)
                <a href="#form-area" class="key-type-card" data-preselect-key="{{ $key }}">
                    <span class="key-type-icon">{!! $keyIcons[$key] !!}</span>
                    <b>{{ $opt['label'] }}</b>
                    <small>{{ $opt['hint'] }}</small>
                    <span class="key-type-price">Copie dès {{ \App\Support\Money::fcfa($pricing['needs']['double']['base'] + $opt['extra']) }}</span>
                </a>
            @endforeach
            <a href="{{ route('services.show', 'reparation-telecommande-voiture') }}" class="key-type-card">
                <span class="key-type-icon"><svg viewBox="0 0 64 64" fill="none"><g filter="url(#soft)"><g transform="translate(3.5 0) rotate(32 50 48)"><rect x="47.5" y="33" width="5" height="15" rx="1.8" fill="url(#metal)" stroke="#4a5a76" stroke-opacity=".55" stroke-width="1.2"/><rect x="45" y="46" width="10" height="11" rx="3.2" fill="url(#red)" stroke="#7d0f15" stroke-opacity=".6" stroke-width="1.2"/><path d="M48 49.5h4" stroke="#fff" stroke-opacity=".5" stroke-width="1.2" stroke-linecap="round"/></g><rect x="14.5" y="5" width="34" height="41" rx="10" fill="url(#plastic)" stroke="#0b1322" stroke-opacity=".6" stroke-width="1.4"/><rect x="18" y="8.5" width="27" height="34" rx="7" fill="none" stroke="#fff" stroke-opacity=".12" stroke-width="1.4"/><circle cx="32" cy="10.5" r="2.6" fill="#0b1322" stroke="url(#chrome)" stroke-width="1.8"/><circle cx="23" cy="13" r="1.4" fill="#e2493f"/><rect x="21" y="18" width="17" height="6.6" rx="3.3" fill="#0b1526" stroke="#fff" stroke-opacity=".14"/><rect x="21" y="26.5" width="17" height="6.6" rx="3.3" fill="#0b1526" stroke="#fff" stroke-opacity=".14"/><path d="M45.5 8l-5.5 9 4.5 6.5-6 8 3 10" stroke="#e2493f" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M46.3 8.4l-5.5 9 4.5 6.5-6 8 3 10" stroke="#fff" stroke-opacity=".22" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/></g></svg></span>
                <b>Réparation de coque</b>
                <small>Coque cassée, boutons usés, pile</small>
                <span class="key-type-price">Dès {{ \App\Support\Money::fcfa(15000) }}</span>
            </a>
        </div>
    </div>
</section>

{{-- ─── BARRE D'ÉTAPES ─── --}}
<nav class="step-bar" id="commande" aria-label="Étapes de la demande">
    <div class="step-tab active" data-tab="1"><span class="sn">1</span><span class="sl">Votre clé</span></div>
    <div class="step-tab step-future" data-tab="2"><span class="sn">2</span><span class="sl">Adresse</span></div>
    <div class="step-tab step-future" data-tab="3"><span class="sn">3</span><span class="sl">Vérification</span></div>
    <div class="step-tab step-future" data-tab="4"><span class="sn">4</span><span class="sl">Envoyé</span></div>
</nav>

<form method="POST" action="{{ route('orders.store') }}" id="order-form" data-start-step="{{ $startStep }}" data-track-form="order" novalidate>
    @csrf
    <div class="page-wrap" id="form-area">
        <div class="form-col">
            @if ($errors->any())
                <div class="flash-error">
                    <b>Merci de corriger :</b>
                    <ul style="margin:6px 0 0 18px">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- ═════════ ÉTAPE 1 : CONFIGURATION ═════════ --}}
            <div class="step-panel" data-step="1">
                <div class="sc">
                    <div class="sc-head"><span class="sc-num ico">ℹ️</span><div><h2>Comment ça se passe ?</h2><p>Quatre étapes, et rien à payer d’avance</p></div></div>
                    <div class="sc-body">
                        <div class="how-it-works-grid">
                            <div class="hiw-card"><span class="hiw-icon"><i class="far fa-calendar-check"></i></span><div><div class="hiw-title">1. Vous décrivez votre clé</div><div class="hiw-desc">Quelques questions sur le véhicule et la clé, <strong>sans paiement</strong> ni engagement.</div></div></div>
                            <div class="hiw-card"><span class="hiw-icon"><i class="fas fa-user-friends"></i></span><div><div class="hiw-title">2. On vous rappelle</div><div class="hiw-desc">Un conseiller confirme le prix et fixe le passage : <strong>tout de suite</strong> ou au moment qui vous arrange.</div></div></div>
                            <div class="hiw-card"><span class="hiw-icon"><i class="fas fa-tools"></i></span><div><div class="hiw-title">3. On fabrique la clé</div><div class="hiw-desc">En boutique ou devant votre véhicule : taille de la lame, codage, essais.</div></div></div>
                            <div class="hiw-card"><span class="hiw-icon"><i class="far fa-check-circle"></i></span><div><div class="hiw-title">4. Vous essayez, puis vous payez</div><div class="hiw-desc">La clé ouvre et démarre ? Vous réglez (Mobile Money, espèces, carte) et recevez <strong>votre facture</strong>.</div></div></div>
                        </div>
                    </div>
                </div>

                <div class="sc" data-section="vehicle_type">
                    <div class="sc-head"><span class="sc-num">1</span><div><h3>Quel véhicule ? *</h3><p>Sélectionnez sa catégorie.</p></div></div>
                    <div class="sc-body">
                        <div class="veh-cards">
                            @foreach ($pricing['vehicle_types'] as $key => $opt)
                                <label class="veh-card" data-group="vehicle_type">
                                    <input class="dkp-hidden-input" type="radio" name="vehicle_type" value="{{ $key }}" @checked($v('vehicle_type') === $key)>
                                    <div class="veh-icon-wrap"><img src="{{ asset('images/vehicles/'.$vehicleImages[$key]) }}" alt="" width="140" height="70"></div>
                                    <div class="veh-sel-dot"></div>
                                    <div class="veh-name">{{ $opt['label'] }}</div>
                                    <div class="veh-sub">{{ $opt['hint'] }}</div>
                                </label>
                            @endforeach
                        </div>
                        <div class="section-error"></div>
                    </div>
                </div>

                <div class="sc" data-section="need">
                    <div class="sc-head"><span class="sc-num">2</span><div><h3>Votre situation *</h3></div></div>
                    <div class="sc-body">
                        <div class="opt-list">
                            @foreach ($pricing['needs'] as $key => $opt)
                                <label class="opt-row" data-group="need">
                                    <input class="dkp-hidden-input" type="radio" name="need" value="{{ $key }}" @checked($v('need') === $key)>
                                    <span class="opt-radio"></span>
                                    <span class="opt-content"><span class="opt-name">{{ $opt['label'] }}</span><span class="opt-desc" style="display:block">{{ $opt['hint'] }}</span></span>
                                </label>
                            @endforeach
                        </div>
                        <div class="section-error"></div>
                    </div>
                </div>

                <div class="sc" data-section="key_type">
                    <div class="sc-head"><span class="sc-num">3</span><div><h3>Votre clé actuelle *</h3><p>Repérez le modèle le plus proche de la clé que vous avez — ou que vous aviez.</p></div></div>
                    <div class="sc-body">
                        <div class="key-grid">
                            @foreach ($pricing['key_types'] as $key => $opt)
                                <label class="key-card" data-group="key_type">
                                    <input class="dkp-hidden-input" type="radio" name="key_type" value="{{ $key }}" @checked($v('key_type') === $key)>
                                    <span class="kchk">✓</span>
                                    <span class="key-svg-wrap">{!! $keyIcons[$key] !!}</span>
                                    <span class="key-name-row">
                                        <span class="key-name">{{ $opt['label'] }}</span>
                                        <span class="key-help" tabindex="0">?<span class="key-tooltip">{{ $opt['tooltip'] ?? $opt['hint'] }}</span></span>
                                    </span>
                                    <span class="key-desc">({{ $opt['hint'] }})</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="section-error"></div>
                    </div>
                </div>

                <div class="sc" data-section="desired_key">
                    <div class="sc-head"><span class="sc-num">4</span><div><h3>Gamme de la nouvelle clé *</h3><p>Compatible : qualité pro au juste prix. Origine : pièce du constructeur.</p></div></div>
                    <div class="sc-body">
                        <div class="opt-list">
                            @foreach ($pricing['desired_keys'] as $key => $opt)
                                <label class="opt-row" data-group="desired_key">
                                    <input class="dkp-hidden-input" type="radio" name="desired_key" value="{{ $key }}" @checked($v('desired_key', 'generic') === $key)>
                                    <span class="opt-radio"></span>
                                    <span class="opt-content"><span class="opt-name">{{ $opt['label'] }}</span>@if ($key === 'generic')<span class="opt-rec">Le plus choisi</span>@endif<span class="opt-desc" style="display:block">({{ $opt['hint'] }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="sc" data-section="quantity">
                    <div class="sc-head"><span class="sc-num">5</span><div><h3>Combien de clés ? *</h3><p>Conseil : gardez toujours une clé de secours — la deuxième coûte {{ $pricing['second_key_discount'] * 100 }} % de moins.</p></div></div>
                    <div class="sc-body">
                        <div class="count-grid">
                            <label class="count-card" data-group="quantity">
                                <input class="dkp-hidden-input" type="radio" name="quantity" value="1" @checked((string) $v('quantity', '1') === '1')>
                                <span class="kchk">✓</span><div class="count-big">1</div><div class="count-lbl">Clé</div>
                            </label>
                            <label class="count-card" data-group="quantity">
                                <input class="dkp-hidden-input" type="radio" name="quantity" value="2" @checked((string) $v('quantity') === '2')>
                                <span class="kchk">✓</span><div class="count-big">2</div><div class="count-lbl">Clés</div>
                                <span class="count-badge">−{{ $pricing['second_key_discount'] * 100 }} % sur la 2ᵉ</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="sc">
                    <div class="sc-head"><span class="sc-num">6</span><div><h3>Garantie <span class="sc-tag">Au choix</span></h3><p>Chaque clé est couverte 1 mois. Passez à 12 mois si vous le souhaitez.</p></div></div>
                    <div class="sc-body">
                        <div class="gar-incl"><i class="fas fa-shield-alt"></i> Inclus : 1 mois de garantie sur chaque clé</div>
                        <label class="tog-row" data-toggle>
                            <input class="dkp-hidden-input" type="checkbox" name="warranty_extension" value="1" @checked($v('warranty_extension'))>
                            <span class="tog-sw"><span class="tog-knob"></span></span>
                            <span class="tog-txt"><span class="tog-name" style="display:block">Garantie 12 mois</span><span class="tog-desc" style="display:block">Pannes électroniques prises en charge pendant un an</span></span>
                        </label>
                    </div>
                </div>

                <div class="sc">
                    <div class="sc-head"><span class="sc-num">7</span><div><h3>Délai <span class="sc-tag">Au choix</span></h3></div></div>
                    <div class="sc-body">
                        <label class="tog-row" data-toggle>
                            <input class="dkp-hidden-input" type="checkbox" name="emergency" value="1" @checked($v('emergency'))>
                            <span class="tog-sw"><span class="tog-knob"></span></span>
                            <span class="tog-txt"><span class="tog-name" style="display:block">Passage express</span><span class="tog-desc" style="display:block">Le soir, le dimanche ou dès que possible</span></span>
                        </label>
                    </div>
                </div>

                <div class="sc" data-section="vehicle_info">
                    <div class="sc-head"><span class="sc-num">8</span><div><h3>Votre véhicule</h3></div></div>
                    <div class="sc-body">
                        <div class="inp-row">
                            <div class="inp-wrap"><label class="inp-label" for="plate">Immatriculation <span class="req">*</span></label><input class="dkp-input" id="plate" name="plate" value="{{ $v('plate') }}" placeholder="LT 123 AB" autocomplete="off"></div>
                            <div class="inp-wrap"><label class="inp-label" for="brand_model">Marque / modèle <span class="req">*</span></label><input class="dkp-input" id="brand_model" name="brand_model" value="{{ $v('brand_model') }}" placeholder="Toyota Corolla" list="brand-list"></div>
                            <div class="inp-wrap"><label class="inp-label" for="year">Année <span class="req">*</span></label><input class="dkp-input" id="year" name="year" type="number" inputmode="numeric" min="1970" max="{{ now()->year + 1 }}" value="{{ $v('year') }}" placeholder="2022"></div>
                        </div>
                        <datalist id="city-list">@foreach (['Douala', 'Yaoundé', 'Bafoussam', 'Kribi', 'Limbé', 'Buea', 'Edéa', 'Bamenda', 'Garoua', 'Ngaoundéré', 'Bertoua', 'Ebolowa'] as $c)<option value="{{ $c }}">@endforeach</datalist>
                        <datalist id="district-list">@foreach (['Akwa', 'Bonanjo', 'Bonapriso', 'Bali', 'Deïdo', 'Bonabéri', 'Bonamoussadi', 'Makepe', 'Kotto', 'Logpom', 'Ndokoti', 'Bassa', 'PK14', 'Logbaba', 'New-Bell', 'Bastos', 'Mvan', 'Biyem-Assi', 'Omnisport', 'Nlongkak'] as $d)<option value="{{ $d }}">@endforeach</datalist>
                        <datalist id="brand-list">@foreach ($brands->flatten() as $b)<option value="{{ $b->name }}">@endforeach</datalist>
                        <div class="section-error"></div>
                    </div>
                </div>
            </div>

            {{-- ═════════ ÉTAPE 2 : LOCALISATION ═════════ --}}
            <div class="step-panel" data-step="2">
                <div class="sc" data-section="address">
                    <div class="sc-head"><span class="sc-num ico"><i class="fas fa-map-marker-alt"></i></span><div><h3>Où êtes-vous ?</h3><p>L’endroit où se trouve le véhicule.</p></div></div>
                    <div class="sc-body">
                        <button type="button" class="geo-btn" id="geo-btn">
                            <span class="geo-icon">📍</span>
                            <span class="geo-title" style="display:block">Utiliser ma position actuelle</span>
                            <span class="geo-sub">Remplit automatiquement l’adresse d’intervention</span>
                        </button>
                        <div class="gps-status-msg" id="gps-msg" role="status"></div>
                        <div class="or-sep">ou saisissez l’adresse</div>
                        <div class="inp-row two">
                            <div class="inp-wrap full ac-wrap">
                                <label class="inp-label" for="address">Adresse <span class="req">*</span></label>
                                <input class="dkp-input" id="address" name="address" value="{{ $v('address') }}" placeholder="Rue ou repère (ex. face pharmacie du Centre)" autocomplete="street-address">
                                <div class="ac-list" id="ac-list"></div>
                            </div>
                            <div class="inp-wrap"><label class="inp-label" for="district">Quartier <span class="req">*</span></label><input class="dkp-input" id="district" name="district" value="{{ $v('district') }}" placeholder="Akwa" list="district-list" autocomplete="address-level3"></div>
                            <div class="inp-wrap"><label class="inp-label" for="city">Ville <span class="req">*</span></label><input class="dkp-input" id="city" name="city" value="{{ $v('city') }}" placeholder="Douala" list="city-list" autocomplete="address-level2"></div>
                        </div>
                        <div class="section-error"></div>
                    </div>
                </div>
                <div class="sc" data-section="contact">
                    <div class="sc-head"><span class="sc-num ico"><i class="far fa-user"></i></span><div><h3>Vos coordonnées</h3><p>Pour vous rappeler et confirmer le prix.</p></div></div>
                    <div class="sc-body">
                        <div class="inp-row two">
                            <div class="inp-wrap"><label class="inp-label" for="customer_name">Nom complet <span class="req">*</span></label><input class="dkp-input" id="customer_name" name="customer_name" value="{{ $v('customer_name') }}" autocomplete="name"></div>
                            <div class="inp-wrap"><label class="inp-label" for="customer_phone">Téléphone <span class="req">*</span></label><input class="dkp-input" id="customer_phone" name="customer_phone" type="tel" value="{{ $v('customer_phone') }}" autocomplete="tel" placeholder="+237 6XX XX XX XX"></div>
                            <div class="inp-wrap full"><label class="inp-label" for="customer_email">E-mail <span class="req">*</span></label><input class="dkp-input" id="customer_email" name="customer_email" type="email" value="{{ $v('customer_email') }}" autocomplete="email" placeholder="vous@exemple.com"></div>
                            <div class="inp-wrap full"><label class="inp-label" for="notes">Précisions (facultatif)</label><textarea class="dkp-textarea" id="notes" name="notes" rows="3" placeholder="Parking, étage, disponibilités…">{{ $v('notes') }}</textarea></div>
                        </div>
                        <div class="section-error"></div>
                    </div>
                </div>
            </div>

            {{-- ═════════ ÉTAPE 3 : VALIDATION ═════════ --}}
            <div class="step-panel" data-step="3">
                <div class="sc">
                    <div class="sc-head"><span class="sc-num ico"><i class="fas fa-car"></i></span><div><h3>Véhicule &amp; prestation</h3></div></div>
                    <div class="sc-body"><div class="vinfo-grid" id="vinfo-vehicle"></div></div>
                </div>
                <div class="sc">
                    <div class="sc-head"><span class="sc-num ico"><i class="fas fa-map-marker-alt"></i></span><div><h3>Adresse &amp; contact</h3></div></div>
                    <div class="sc-body"><div class="vinfo-grid" id="vinfo-contact"></div></div>
                </div>
                <div class="sc">
                    <div class="sc-head"><span class="sc-num ico"><i class="fas fa-file-invoice"></i></span><div><h3>Tarif &amp; règlement</h3></div></div>
                    <div class="sc-body">
                        <div class="gar-incl" style="margin:0"><i class="fas fa-check-circle"></i> Rien à payer maintenant : le prix vous est confirmé par téléphone, et vous réglez une fois la clé essayée.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── COLONNE RÉCAPITULATIF ─── --}}
        <aside>
            <div class="sidebar-sticky">
                <div class="recap-wrap">
                    <div class="rh"><h3>Récapitulatif</h3><span class="rc-badge" id="rc-count">0</span></div>
                    <div class="rb" id="recap">
                        <div class="recap-empty"><span class="recap-empty-icon">🔑</span>Vos choix<br>s’afficheront ici</div>
                    </div>
                </div>
                <label class="terms-box" id="terms-box">
                    <input type="checkbox" name="accept_terms" value="1" id="accept_terms" @checked($v('accept_terms'))>
                    <span>J’accepte les <a href="{{ route('terms') }}" target="_blank">conditions générales d’intervention</a>.</span>
                </label>
                <button type="button" class="btn-cta" id="btn-next">Étape suivante : l’adresse →</button>
                <button type="button" class="btn-back" id="btn-back" hidden>← Retour</button>
                <button type="button" class="btn-clear-draft" id="btn-clear">Tout recommencer</button>
                <div class="pay-row" aria-label="Moyens de paiement acceptés après intervention : Orange Money, MTN Mobile Money, espèces, carte">
                    <span class="pay-chip pay-om">Orange<br>Money</span>
                    <span class="pay-chip pay-momo">MTN<br>MoMo</span>
                    <span class="pay-chip">ESPÈCES</span>
                    <span class="pay-chip"><i class="fab fa-cc-visa"></i></span>
                    <span class="pay-chip"><i class="fab fa-cc-mastercard"></i></span>
                </div>
                <div class="trust-list">
                    <span class="trust-item"><span class="tdot"></span>Vous payez seulement une fois la clé essayée</span>
                    <span class="trust-item"><span class="tdot"></span>1 mois de garantie sur chaque clé</span>
                    <span class="trust-item"><span class="tdot"></span>Prix confirmé par téléphone avant le déplacement</span>
                    <span class="trust-item"><span class="tdot"></span>Changement d’avis ? Annulation sans frais avant le départ</span>
                    <span class="trust-item"><span class="tdot"></span>Techniciens formés, assurés en responsabilité civile</span>
                </div>
            </div>
        </aside>
    </div>
</form>

{{-- ─── REFONTE DE CLÉS : NOTRE SPÉCIALITÉ ─── --}}
<section class="refonte-section" id="refonte-cles">
    <div class="container refonte-grid">
        <div class="refonte-media">
            <img src="{{ asset('images/photos/taille-cle-atelier.webp') }}?v=2" alt="Taille d’une clé sur machine dans l’atelier CAMER AUTO KEY" width="1024" height="506" loading="lazy">
            <div class="refonte-badge"><b>30 min</b><span>en moyenne pour<br>une clé complète</span></div>
        </div>
        <div class="refonte-content">
            <span class="eyebrow"><i class="fas fa-key"></i> Notre spécialité</span>
            <h2>Refonte et reproduction de clés auto</h2>
            <p>Clé perdue, cassée, usée ou qui ne démarre plus le véhicule : nous fabriquons une <strong>clé neuve identique</strong> — lame taillée sur machine, puce antidémarrage et télécommande programmées — <strong>même sans la clé d’origine</strong>.</p>
            <ol class="refonte-steps">
                <li><span>1</span><div><b>Identification</b>Lecture du véhicule et de la clé (carte grise demandée).</div></li>
                <li><span>2</span><div><b>Taille de la lame</b>Découpe à l’identique sur machine électronique.</div></li>
                <li><span>3</span><div><b>Programmation</b>Puce antidémarrage, télécommande ou clé mains libres.</div></li>
                <li><span>4</span><div><b>Test &amp; remise</b>Ouverture et démarrage testés devant vous, garantie 1 mois.</div></li>
            </ol>
            <div class="refonte-stats">
                <div><b>100+</b><span>marques</span></div>
                <div><b>Toutes</b><span>clés : simple, puce, télécommande, smart</span></div>
                <div><b>1 mois</b><span>de garantie</span></div>
            </div>
            <div class="hero-actions">
                <a href="#form-area" class="btn-red"><i class="fas fa-key"></i> Refaire ma clé</a>
                <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}" class="btn-outline"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</a>
            </div>
        </div>
    </div>
</section>

<section class="keys-gallery">
    <div class="container">
        <div class="section-title" style="margin-bottom:30px">
            <span class="sub-title">Clés réalisées</span>
            <h2 class="main-title">Ils ont refait leurs clés chez nous</h2>
        </div>
        <div class="gallery-grid">
            @foreach ($keyGallery as [$file, $caption])
                <button type="button" class="gallery-item" data-full="{{ asset('images/photos/'.$file) }}" data-caption="{{ $caption }}">
                    <img src="{{ asset('images/photos/thumbs/'.$file) }}" alt="{{ $caption }}" loading="lazy" width="800" height="600">
                    <span class="gallery-cap"><i class="fas fa-key"></i> {{ $caption }}</span>
                </button>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── LOGOS DES MARQUES ─── --}}
<section class="brands-strip">
    <div class="container">
        <div class="brand-grid">
            @foreach ($brands->flatten()->sortByDesc(fn ($b) => (bool) $b->logoUrl()) as $brand)
                <x-brand-logo :brand="$brand" />
            @endforeach
        </div>
    </div>
</section>

{{-- ─── RÉALISATIONS ─── --}}
<section class="gallery-section">
    <div class="container">
        <div class="section-title">
            <span class="sub-title">Notre atelier</span>
            <h2 class="main-title">Nos techniciens au travail</h2>
        </div>
        <div class="gallery-grid">
            @foreach ($gallery as [$file, $caption])
                <button type="button" class="gallery-item" data-full="{{ asset('images/photos/'.$file) }}" data-caption="{{ $caption }}">
                    <img src="{{ asset('images/photos/thumbs/'.$file) }}" alt="{{ $caption }}" loading="lazy" width="800" height="600">
                    <span class="gallery-cap"><i class="fas fa-search-plus"></i> {{ $caption }}</span>
                </button>
            @endforeach
        </div>
    </div>
</section>
<div class="lightbox" id="lightbox" hidden>
    <button type="button" class="lightbox-close" aria-label="Fermer">&times;</button>
    <figure><img src="" alt=""><figcaption></figcaption></figure>
</div>

{{-- ─── GARAGE AUTO : 3K AUTO SERVICES (le logo de l'en-tête bascule sur 3K dans cette section) ─── --}}
@php
    $gTel = preg_replace('/\s+/', '', $settings['garage_phone']);
    $garageServices = array_slice(config('garage.services'), 0, 6, true);
@endphp
<section class="garage-auto" id="garage-auto" data-brand-switch="garage" aria-labelledby="garage-auto-title">
    <div class="container">
        <div class="garage-auto-head">
            <img src="{{ asset('images/logo-3k-auto-services.webp') }}" alt="Logo {{ $settings['garage_name'] }}" width="96" height="90" loading="lazy">
            <div>
                <span class="garage-auto-eyebrow"><i class="fas fa-tools"></i> Garage auto</span>
                <h2 id="garage-auto-title">{{ $settings['garage_name'] }}</h2>
                <p>Le garage de l’équipe, à <strong>Ndogsimbi (derrière Overline Voyages)</strong> : mécanique, entretien, diagnostic et équipements pour voitures, 4x4 et pick-up.</p>
            </div>
        </div>

        <div class="garage-auto-grid">
            <div class="garage-auto-services">
                @foreach ($garageServices as $gslug => $gs)
                    <a class="ga-service" href="{{ route('garage.service', $gslug) }}"><span><i class="{{ $gs['icon'] }}"></i></span><div><b>{{ $gs['title'] }}</b><small>{{ \Illuminate\Support\Str::limit($gs['excerpt'], 70) }}</small></div></a>
                @endforeach
            </div>
            <div class="garage-auto-photos">
                <img src="{{ asset('images/photos/thumbs/f150-pont-elevateur.webp') }}" alt="Ford F-150 sur pont élévateur au garage 3K" width="800" height="600" loading="lazy" class="ga-photo-main">
                <img src="{{ asset('images/photos/thumbs/mecanicien-capot-toyota.webp') }}" alt="Diagnostic sous le capot d’un Toyota" width="800" height="600" loading="lazy">
                <img src="{{ asset('images/photos/thumbs/vidange-moteur.webp') }}" alt="Vidange moteur" width="800" height="600" loading="lazy">
            </div>
        </div>

        <div class="garage-auto-bar">
            <span><i class="fas fa-map-marker-alt"></i> {{ $settings['garage_address'] }}</span>
            <span><i class="far fa-clock"></i> {{ $settings['garage_hours'] }}</span>
            <span><i class="fas fa-phone-alt"></i> <a href="tel:{{ $gTel }}">{{ $settings['garage_phone'] }}</a></span>
        </div>
        <div class="garage-auto-actions">
            <a href="{{ route('garage') }}" class="ga-btn ga-btn-primary">Découvrir le garage 3K →</a>
            @if ($settings['garage_whatsapp'])
                <a href="https://wa.me/{{ $settings['garage_whatsapp'] }}" class="ga-btn" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Rendez-vous</a>
            @endif
            @if (is_numeric($settings['garage_lat']) && is_numeric($settings['garage_lng']))
                <a href="https://www.google.com/maps/dir/?api=1&amp;destination={{ $settings['garage_lat'] }},{{ $settings['garage_lng'] }}" class="ga-btn" target="_blank" rel="noopener"><i class="fas fa-route"></i> Itinéraire</a>
            @endif
        </div>
    </div>
</section>

{{-- ─── AVIS CLIENTS ─── --}}
<section class="testimonials-section">
    <div class="container">
        <div class="section-title">
            <span class="sub-title">Avis clients</span>
            <h2 class="main-title">Ce que disent nos clients</h2>
        </div>
        <div class="swiper testimonial-swiper">
            <div class="swiper-wrapper">
                @foreach ($testimonials as $t)
                    <div class="swiper-slide">
                        <div class="testimonial-block">
                            <div class="testimonial-avtar">
                                <div class="initials">{{ $t->initials() }}</div>
                                <div class="testimonial-name">{{ $t->name }}</div>
                                <span>Client | {{ $t->city }}</span>
                            </div>
                            <div class="testimonial-content">
                                <p>{{ $t->content }}</p>
                                <i class="fas fa-quote-right"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

{{-- ─── SECTION SEO ─── --}}
<section class="seo-section">
    <div class="seo-inner">
        <div class="seo-head">
            <h2>{{ $settings['company_name'] }}, l’atelier de la clé automobile à Douala</h2>
            <p>Depuis notre boutique de Douala, nous <strong>taillons, codons et remplaçons</strong> les clés de voitures, de camions et de deux-roues pour les particuliers, les garages et les entreprises de Douala et de tout le Cameroun.</p>
        </div>
        <div class="seo-grid">
            <div class="seo-card">
                <h3>Plus aucune clé ? On repart de zéro</h3>
                <p>Clés perdues au marché, volées ou tombées en panne en même temps : nos techniciens lisent les données du véhicule et <strong>fabriquent une clé neuve</strong>, puis effacent les anciennes de la mémoire du véhicule si vous le demandez.</p>
                <p class="note">Une urgence ? Appelez le {{ $settings['phone'] }}.</p>
            </div>
            <div class="seo-card">
                <h3>Une clé de secours, avant la panne</h3>
                <p>Une seule clé pour le véhicule familial ou de service ? Faites-en une <strong>deuxième pendant qu’elle fonctionne</strong> : c’est plus rapide, et bien moins cher qu’après une perte.</p>
                <p class="note">−30 % sur la deuxième clé.</p>
            </div>
            <div class="seo-card">
                <h3>Codage et antidémarrage</h3>
                <p>Clé qui ouvre mais ne démarre plus, voyant d’antidémarrage allumé, télécommande désynchronisée : nous relisons et <strong>recodons</strong> la clé avec nos outils de diagnostic.</p>
                <p class="note">Souvent réglé en moins d’une heure.</p>
            </div>
            <div class="seo-card">
                <h3>Clés enfermées dans le véhicule</h3>
                <p>Portières verrouillées avec les clés à l’intérieur ? Nous ouvrons <strong>sans forcer ni abîmer</strong> la serrure, puis nous pouvons fabriquer une clé de secours sur place.</p>
                <p class="note">Le soir et le dimanche aussi, sur demande.</p>
            </div>
            <div class="seo-card">
                <h3>Toyota, Hyundai, Mercedes… et camions</h3>
                <p>Corolla, RAV4, Hilux, Prado, Land Cruiser, mais aussi Hyundai, Kia, Nissan, Mercedes, Ford, ainsi que <strong>camions et motos</strong> : nous avons les outils pour la grande majorité des véhicules qui roulent au Cameroun.</p>
                <p class="note"><a href="{{ route('brands.index') }}" style="color:var(--navy);font-weight:700">Voir les marques →</a></p>
            </div>
            <div class="seo-card">
                <h3>Des prix en FCFA, connus d’avance</h3>
                <p>Le prix dépend du véhicule, de la clé et de la gamme choisie. Envoyez-nous la carte grise ou une photo de la clé : <strong>vous connaissez le montant avant</strong> que l’on commence.</p>
                <p class="note">Orange Money, MTN Mobile Money, espèces ou carte.</p>
            </div>
        </div>
        <div class="seo-cta">
            <h3>Vous avez perdu votre seule clé ?</h3>
            <p>Ce n’est pas une raison pour remorquer le véhicule chez le concessionnaire : nous créons une <strong>clé neuve sans l’ancienne</strong>, directement en boutique ou là où se trouve votre véhicule.</p>
            <a href="tel:{{ $tel }}" class="btn-white"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</a>
        </div>

        @if ($faqs->isNotEmpty())
            <div class="seo-sub">
                <h3>Vos questions</h3>
                <div class="faq-list">
                    @foreach ($faqs as $faq)
                        <details class="faq-item"><summary>{{ $faq->question }}</summary><div class="faq-a">{{ $faq->answer }}</div></details>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="seo-sub">
            <h3>Pour aller plus loin</h3>
            <div class="res-grid">
                <a class="res-card" href="{{ route('services.index') }}"><b>Toutes nos prestations</b><span>Perte totale, double, ouverture, programmation, réparation.</span></a>
                <a class="res-card" href="{{ route('brands.index') }}"><b>Clés par marque</b><span>Retrouvez votre constructeur et nos solutions dédiées.</span></a>
                <a class="res-card" href="{{ route('faq') }}"><b>FAQ complète</b><span>Tarifs, délais, documents à fournir, garantie.</span></a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js"></script>
    <script>
        new Swiper('.testimonial-swiper', {
            loop: true, speed: 700, spaceBetween: 24, slidesPerView: 1,
            autoplay: { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true },
            pagination: { el: '.swiper-pagination', clickable: true },
            breakpoints: { 700: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
        });

        document.querySelectorAll('[data-preselect-key]').forEach((card) => card.addEventListener('click', () => {
            const input = document.querySelector(`[name="key_type"][value="${card.dataset.preselectKey}"]`);
            if (input) { input.checked = true; input.dispatchEvent(new Event('change', { bubbles: true })); }
        }));
        window.PRICING = @json($pricing);
    </script>
    <script src="{{ asset('js/lightbox.js') }}?v=1"></script>
    <script src="{{ asset('js/configurator.js') }}?v=4"></script>
@endpush
