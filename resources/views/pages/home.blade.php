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
        'simple' => '<svg viewBox="0 0 48 48" fill="none" stroke="#1a2f5e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="15" cy="24" r="8"/><path d="M23 24h20M37 24v6M42 24v4"/></svg>',
        'transponder' => '<svg viewBox="0 0 48 48" fill="none" stroke="#1a2f5e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect x="14" y="4" width="20" height="22" rx="6"/><rect x="20" y="10" width="8" height="8" rx="1.5" fill="#d6262e" stroke="#d6262e"/><path d="M24 26v18M24 34h5M24 40h4"/></svg>',
        'remote' => '<svg viewBox="0 0 48 48" fill="none" stroke="#1a2f5e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect x="13" y="3" width="22" height="26" rx="7"/><circle cx="24" cy="11" r="2.5" fill="#d6262e" stroke="#d6262e"/><circle cx="24" cy="20" r="2.5"/><path d="M24 29v16M24 37h5M24 42h4"/></svg>',
        'smart' => '<svg viewBox="0 0 48 48" fill="none" stroke="#1a2f5e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="11" width="36" height="26" rx="5"/><path d="M12 19h10M12 25h6"/><path d="M31 20a6 6 0 0 1 0 8M35 17a10 10 0 0 1 0 14" stroke="#d6262e"/></svg>',
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
        ['facade-camer-auto-key.webp', 'Notre boutique à Akwa, Douala'],
        ['equipe-camer-auto-key.webp', 'L’équipe CAMER AUTO KEY'],
        ['taille-cle-condor.webp', 'Taille de clé sur machine'],
        ['diagnostic-tablette.webp', 'Programmation par diagnostic'],
        ['mecanicien-capot-toyota.webp', 'Diagnostic sous le capot'],
        ['installation-camera-recul.webp', 'Installation caméra de recul 360°'],
        ['f150-pont-elevateur.webp', 'Ford F-150 sur pont élévateur'],
        ['atelier-cour.webp', 'Notre atelier'],
    ];
    $vehicleImages = ['auto' => 'vehicle-car.svg', 'pl' => 'vehicle-truck.svg', 'moto' => 'vehicle-moto.svg'];
@endphp

@section('content')
{{-- ─── HERO ─── --}}
<section class="hero-section">
    <div class="hero-slider" aria-hidden="true">
        @foreach ([
            ['cles-toyota-tacoma.webp', '62% 58%'],
            ['decoupe-cle-machine.webp', '70% 45%'],
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
        <span class="hero-kicker"><i class="fas fa-key"></i> Spécialiste de la clé automobile au Cameroun</span>
        <h1>{{ $settings['hero_title'] }}<br>On refait votre clé en <span class="hl">30 minutes</span></h1>
        <p><strong>Reproduction, refonte et programmation de clés auto</strong> · Voitures · Camions · Motos · Douala, Yaoundé et tout le Cameroun<br>{{ $settings['hero_subtitle'] }}</p>
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
            <span class="hbadge"><i class="fas fa-star"></i> 4,9/5 avis clients</span>
        </div>
    </div>
</section>

{{-- ─── TYPES DE CLÉS ─── --}}
<section class="keys-band" aria-labelledby="keys-band-title">
    <div class="container">
        <div class="keys-band-head">
            <h2 id="keys-band-title">Toutes vos clés, <em>refaites et programmées</em></h2>
            <p>Choisissez votre type de clé : nous la reproduisons à l’identique, même sans l’originale.</p>
        </div>
        <div class="keys-band-grid">
            @foreach ($pricing['key_types'] as $key => $opt)
                <a href="#form-area" class="key-type-card" data-preselect-key="{{ $key }}">
                    <span class="key-type-icon">{!! $keyIcons[$key] !!}</span>
                    <b>{{ $opt['label'] }}</b>
                    <small>{{ $opt['hint'] }}</small>
                    <span class="key-type-price">Double dès {{ \App\Support\Money::fcfa($pricing['needs']['double']['base'] + $opt['extra']) }}</span>
                </a>
            @endforeach
            <a href="{{ route('services.show', 'reparation-telecommande-voiture') }}" class="key-type-card">
                <span class="key-type-icon"><svg viewBox="0 0 48 48" fill="none" stroke="#1a2f5e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect x="13" y="3" width="22" height="26" rx="7"/><path d="M18 36l6-6 6 6M24 30v15" stroke="#d6262e"/><circle cx="24" cy="12" r="2.5"/></svg></span>
                <b>Réparation de coque</b>
                <small>Coque cassée, boutons usés, pile</small>
                <span class="key-type-price">Dès {{ \App\Support\Money::fcfa(15000) }}</span>
            </a>
        </div>
    </div>
</section>

{{-- ─── BARRE D'ÉTAPES ─── --}}
<nav class="step-bar" id="commande" aria-label="Étapes de la demande">
    <div class="step-tab active" data-tab="1"><span class="sn">1</span><span class="sl">Configuration</span></div>
    <div class="step-tab step-future" data-tab="2"><span class="sn">2</span><span class="sl">Localisation</span></div>
    <div class="step-tab step-future" data-tab="3"><span class="sn">3</span><span class="sl">Validation</span></div>
    <div class="step-tab step-future" data-tab="4"><span class="sn">4</span><span class="sl">Confirmation</span></div>
</nav>

<form method="POST" action="{{ route('orders.store') }}" id="order-form" data-start-step="{{ $startStep }}" novalidate>
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
                    <div class="sc-head"><span class="sc-num ico">ℹ️</span><div><h2>Comment fonctionne votre intervention ?</h2><p>De votre demande à la facture — simple, sécurisé, transparent</p></div></div>
                    <div class="sc-body">
                        <div class="how-it-works-grid">
                            <div class="hiw-card"><span class="hiw-icon"><i class="far fa-calendar-check"></i></span><div><div class="hiw-title">1. Demande en ligne</div><div class="hiw-desc">Configurez votre besoin dans le formulaire. <strong>Aucun paiement</strong> n’est demandé à cette étape.</div></div></div>
                            <div class="hiw-card"><span class="hiw-icon"><i class="fas fa-user-friends"></i></span><div><div class="hiw-title">2. Traitement &amp; attribution</div><div class="hiw-desc">Notre équipe 24/7 vous attribue un technicien. <strong>Urgence :</strong> départ immédiat. <strong>Standard :</strong> prise de rendez-vous.</div></div></div>
                            <div class="hiw-card"><span class="hiw-icon"><i class="fas fa-tools"></i></span><div><div class="hiw-title">3. Intervention sur site</div><div class="hiw-desc">Le technicien certifié se déplace et réalise la prestation : fabrication, programmation ou ouverture.</div></div></div>
                            <div class="hiw-card"><span class="hiw-icon"><i class="far fa-check-circle"></i></span><div><div class="hiw-title">4. Validation &amp; règlement</div><div class="hiw-desc">Vous validez la fin de la prestation → règlement → <strong>facture envoyée par e-mail</strong>.</div></div></div>
                        </div>
                    </div>
                </div>

                <div class="sc" data-section="vehicle_type">
                    <div class="sc-head"><span class="sc-num">1</span><div><h3>Type de véhicule *</h3><p>Choisissez le type de véhicule concerné par la demande.</p></div></div>
                    <div class="sc-body">
                        <div class="veh-cards">
                            @foreach ($pricing['vehicle_types'] as $key => $opt)
                                <label class="veh-card" data-group="vehicle_type">
                                    <input class="dkp-hidden-input" type="radio" name="vehicle_type" value="{{ $key }}" @checked($v('vehicle_type') === $key)>
                                    <div class="veh-icon-wrap"><img src="{{ asset('images/placeholders/'.$vehicleImages[$key]) }}" alt="" width="140" height="70"></div>
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
                    <div class="sc-head"><span class="sc-num">2</span><div><h3>Quel est votre besoin ? *</h3></div></div>
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
                    <div class="sc-head"><span class="sc-num">3</span><div><h3>Type de clé du véhicule *</h3><p>Choisissez ce qui ressemble le plus à votre clé actuelle.</p></div></div>
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
                    <div class="sc-head"><span class="sc-num">4</span><div><h3>Type de clé souhaitée *</h3><p>Les clés génériques sont des modèles compatibles de qualité professionnelle. Les clés d’origine sont des modèles de marque constructeur.</p></div></div>
                    <div class="sc-body">
                        <div class="opt-list">
                            @foreach ($pricing['desired_keys'] as $key => $opt)
                                <label class="opt-row" data-group="desired_key">
                                    <input class="dkp-hidden-input" type="radio" name="desired_key" value="{{ $key }}" @checked($v('desired_key', 'generic') === $key)>
                                    <span class="opt-radio"></span>
                                    <span class="opt-content"><span class="opt-name">{{ $opt['label'] }}</span>@if ($key === 'generic')<span class="opt-rec">Recommandé</span>@endif<span class="opt-desc" style="display:block">({{ $opt['hint'] }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="sc" data-section="quantity">
                    <div class="sc-head"><span class="sc-num">5</span><div><h3>Nombre de clés souhaitées *</h3><p>Il est fortement recommandé d’avoir au moins deux clés. La 2ᵉ clé bénéficie automatiquement d’une réduction de {{ $pricing['second_key_discount'] * 100 }} %.</p></div></div>
                    <div class="sc-body">
                        <div class="count-grid">
                            <label class="count-card" data-group="quantity">
                                <input class="dkp-hidden-input" type="radio" name="quantity" value="1" @checked((string) $v('quantity', '1') === '1')>
                                <span class="kchk">✓</span><div class="count-big">1</div><div class="count-lbl">Clé</div>
                            </label>
                            <label class="count-card" data-group="quantity">
                                <input class="dkp-hidden-input" type="radio" name="quantity" value="2" @checked((string) $v('quantity') === '2')>
                                <span class="kchk">✓</span><div class="count-big">2</div><div class="count-lbl">Clés</div>
                                <span class="count-badge">2ᵉ clé −{{ $pricing['second_key_discount'] * 100 }} %</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="sc">
                    <div class="sc-head"><span class="sc-num">6</span><div><h3>Protection <span class="sc-tag">Optionnel</span></h3><p>Un mois de garantie est inclus pour chaque clé fabriquée. Vous pouvez prolonger la garantie d’un an.</p></div></div>
                    <div class="sc-body">
                        <div class="gar-incl"><i class="fas fa-shield-alt"></i> Une garantie d’un mois est incluse dans le prix pour chaque clé fabriquée</div>
                        <label class="tog-row" data-toggle>
                            <input class="dkp-hidden-input" type="checkbox" name="warranty_extension" value="1" @checked($v('warranty_extension'))>
                            <span class="tog-sw"><span class="tog-knob"></span></span>
                            <span class="tog-txt"><span class="tog-name" style="display:block">Extension de garantie de 1 an</span><span class="tog-desc" style="display:block">(couvre chaque clé pendant 1 an au total — dysfonctionnement électronique)</span></span>
                        </label>
                    </div>
                </div>

                <div class="sc">
                    <div class="sc-head"><span class="sc-num">7</span><div><h3>Options d’intervention <span class="sc-tag">Optionnel</span></h3></div></div>
                    <div class="sc-body">
                        <label class="tog-row" data-toggle>
                            <input class="dkp-hidden-input" type="checkbox" name="emergency" value="1" @checked($v('emergency'))>
                            <span class="tog-sw"><span class="tog-knob"></span></span>
                            <span class="tog-txt"><span class="tog-name" style="display:block">Intervention d’urgence</span><span class="tog-desc" style="display:block">Week-end — soir de 20h à 8h — ou besoin de passage rapide</span></span>
                        </label>
                    </div>
                </div>

                <div class="sc" data-section="vehicle_info">
                    <div class="sc-head"><span class="sc-num">8</span><div><h3>Informations du véhicule</h3></div></div>
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
                    <div class="sc-head"><span class="sc-num ico"><i class="fas fa-map-marker-alt"></i></span><div><h3>Adresse d’intervention</h3><p>Où se trouve le véhicule ?</p></div></div>
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
                    <div class="sc-head"><span class="sc-num ico"><i class="far fa-user"></i></span><div><h3>Vos coordonnées</h3><p>Pour que le technicien puisse vous joindre.</p></div></div>
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
                        <div class="gar-incl" style="margin:0"><i class="fas fa-check-circle"></i> Aucun paiement maintenant : un conseiller vous confirme le prix avant le départ du technicien, et vous réglez uniquement après l’intervention.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── COLONNE RÉCAPITULATIF ─── --}}
        <aside>
            <div class="sidebar-sticky">
                <div class="recap-wrap">
                    <div class="rh"><h3>Vos sélections</h3><span class="rc-badge" id="rc-count">0</span></div>
                    <div class="rb" id="recap">
                        <div class="recap-empty"><span class="recap-empty-icon">🔑</span>Sélectionnez vos options<br>pour voir votre récapitulatif</div>
                    </div>
                </div>
                <label class="terms-box" id="terms-box">
                    <input type="checkbox" name="accept_terms" value="1" id="accept_terms" @checked($v('accept_terms'))>
                    <span>J’ai lu et j’accepte les <a href="{{ route('terms') }}" target="_blank">Conditions générales d’intervention</a> — obligatoire pour valider votre demande.</span>
                </label>
                <button type="button" class="btn-cta" id="btn-next">Continuer vers la localisation →</button>
                <button type="button" class="btn-back" id="btn-back" hidden>← Retour</button>
                <button type="button" class="btn-clear-draft" id="btn-clear">Effacer mes informations</button>
                <div class="pay-row" aria-label="Moyens de paiement acceptés après intervention : Orange Money, MTN Mobile Money, espèces, carte">
                    <span class="pay-chip pay-om">Orange<br>Money</span>
                    <span class="pay-chip pay-momo">MTN<br>MoMo</span>
                    <span class="pay-chip">ESPÈCES</span>
                    <span class="pay-chip"><i class="fab fa-cc-visa"></i></span>
                    <span class="pay-chip"><i class="fab fa-cc-mastercard"></i></span>
                </div>
                <div class="trust-list">
                    <span class="trust-item"><span class="tdot"></span>Paiement uniquement après l’intervention</span>
                    <span class="trust-item"><span class="tdot"></span>Garantie 1 mois incluse sur chaque clé</span>
                    <span class="trust-item"><span class="tdot"></span>Prix annoncé avant toute intervention</span>
                    <span class="trust-item"><span class="tdot"></span>Annulation gratuite avant départ du technicien</span>
                    <span class="trust-item"><span class="tdot"></span>Technicien certifié — RC Pro assurée</span>
                </div>
            </div>
        </aside>
    </div>
</form>

{{-- ─── REFONTE DE CLÉS : NOTRE SPÉCIALITÉ ─── --}}
<section class="refonte-section" id="refonte-cles">
    <div class="container refonte-grid">
        <div class="refonte-media">
            <img src="{{ asset('images/photos/decoupe-cle-machine.webp') }}" alt="Taille d’une clé sur machine dans l’atelier CAMER AUTO KEY" width="2000" height="1801" loading="lazy">
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

{{-- ─── GARAGE PARTENAIRE ─── --}}
<section class="garage-band">
    <div class="container"><div class="garage-band-inner">
        <img src="{{ asset('images/logo-3k-auto-services.webp') }}" alt="Logo {{ $settings['garage_name'] }}" width="120" height="112" loading="lazy">
        <div class="garage-band-text">
            <span class="eyebrow">Notre garage partenaire</span>
            <h2>{{ $settings['garage_name'] }}</h2>
            <p>Mécanique, entretien, vidange, diagnostic électronique, freinage, alarme, GPS et caméras : la même équipe s’occupe aussi de tout votre véhicule.</p>
        </div>
        <div class="garage-band-actions">
            <a href="{{ route('garage') }}" class="btn-red">Découvrir le garage</a>
            <a href="tel:{{ preg_replace('/\s+/', '', $settings['garage_phone']) }}" class="btn-outline"><i class="fas fa-phone-alt"></i> {{ $settings['garage_phone'] }}</a>
        </div>
    </div></div>
</section>

{{-- ─── AVIS CLIENTS ─── --}}
<section class="testimonials-section">
    <div class="container">
        <div class="section-title">
            <span class="sub-title">Consultez les avis des clients</span>
            <h2 class="main-title">Les meilleures recommandations sont celles de nos clients</h2>
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
            <h2>{{ $settings['company_name'] }} — votre serrurier automobile à domicile</h2>
            <p>Spécialiste de la <strong>clé automobile</strong> à Douala et partout au Cameroun, {{ $settings['company_name'] }} réalise la <strong>reproduction de clé de voiture</strong>, le <strong>double de clé auto</strong> et la <strong>programmation de clé</strong> directement chez vous, 24h/24.</p>
        </div>
        <div class="seo-grid">
            <div class="seo-card">
                <h3>Clé de voiture perdue ? Dépannage 24h/24</h3>
                <p>Plus aucune clé ? Pas d’inquiétude. Un technicien {{ $settings['company_name'] }} se déplace en urgence avec tout son matériel pour <strong>créer une nouvelle clé sur place</strong>, sans l’originale et sans passer par le concessionnaire.</p>
                <p class="note">Besoin d’aide immédiate ? Appelez le {{ $settings['phone'] }} : un serrurier auto part vers vous.</p>
            </div>
            <div class="seo-card">
                <h3>Double de clé voiture &amp; reproduction rapide</h3>
                <p>Vous avez encore une clé qui fonctionne ? Nous réalisons un <strong>double de clé</strong> à domicile : clé à transpondeur, télécommande, clé électronique ou mains libres, sur plus de 100 marques.</p>
                <p class="note">Prix communiqué à l’avance, sans surprise.</p>
            </div>
            <div class="seo-card">
                <h3>Programmer &amp; reprogrammer une clé</h3>
                <p>Codage par prise OBD, synchronisation de transpondeur, clé qui ne démarre plus : nos techniciens utilisent du <strong>matériel professionnel</strong> pour programmer votre clé en quelques minutes.</p>
                <p class="note">Intervention rapide, sur place.</p>
            </div>
            <div class="seo-card">
                <h3>Ouverture de véhicule sans clé</h3>
                <p>Clé enfermée dans l’habitacle ou dans le coffre ? Nous ouvrons votre véhicule <strong>sans aucun dégât</strong>, de jour comme de nuit, week-ends et jours fériés compris.</p>
                <p class="note">Une nouvelle clé peut être fabriquée dans la foulée.</p>
            </div>
            <div class="seo-card">
                <h3>Toutes marques, auto, moto et poids lourd</h3>
                <p>Peugeot, Renault, Citroën, Volkswagen, BMW, Mercedes, Audi, Toyota… mais aussi <strong>camions et motos</strong> : nos techniciens connaissent les systèmes antidémarrage des principaux constructeurs.</p>
                <p class="note"><a href="{{ route('brands.index') }}" style="color:var(--navy);font-weight:700">Voir toutes les marques →</a></p>
            </div>
            <div class="seo-card">
                <h3>Tarifs transparents</h3>
                <p>Le tarif dépend du véhicule, du type de clé et des options. Il vous est <strong>communiqué avant le départ</strong> du technicien, sur la base de votre carte grise ou de votre plaque et de l’adresse d’intervention.</p>
                <p class="note">Règlement après l’intervention, par carte ou virement.</p>
            </div>
        </div>
        <div class="seo-cta">
            <h3>Comment refaire une clé de voiture sans double ?</h3>
            <p>C’est simple : même <strong>sans clé d’origine</strong>, nos techniciens fabriquent une nouvelle clé à partir des données du véhicule, la programment sur place et peuvent <strong>désactiver les anciennes clés</strong> pour votre sécurité.</p>
            <a href="tel:{{ $tel }}" class="btn-white"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</a>
        </div>

        @if ($faqs->isNotEmpty())
            <div class="seo-sub">
                <h3>Questions fréquentes — clé de voiture</h3>
                <div class="faq-list">
                    @foreach ($faqs as $faq)
                        <details class="faq-item"><summary>{{ $faq->question }}</summary><div class="faq-a">{{ $faq->answer }}</div></details>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="seo-sub">
            <h3>Ressources utiles</h3>
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

        (function () {
            const slides = document.querySelectorAll('.hero-slide');
            if (slides.length < 2) return;
            let i = 0;
            setInterval(() => {
                slides[i].classList.remove('active');
                i = (i + 1) % slides.length;
                slides[i].classList.add('active');
            }, 6000);
        })();
        document.querySelectorAll('[data-preselect-key]').forEach((card) => card.addEventListener('click', () => {
            const input = document.querySelector(`[name="key_type"][value="${card.dataset.preselectKey}"]`);
            if (input) { input.checked = true; input.dispatchEvent(new Event('change', { bubbles: true })); }
        }));
        window.PRICING = @json($pricing);
    </script>
    <script src="{{ asset('js/lightbox.js') }}?v=1"></script>
    <script src="{{ asset('js/configurator.js') }}?v=3"></script>
@endpush
