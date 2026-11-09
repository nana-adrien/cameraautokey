@php
    $tel = preg_replace('/\s+/', '', $settings['phone']);
    $telBe = preg_replace('/\s+/', '', $settings['phone_secondary']);
@endphp
<!DOCTYPE html>
<html lang="fr-CM">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Reproduction et refonte de clé de voiture à Douala') | {{ $settings['company_name'] }} — clés auto à Douala</title>
    <meta name="description" content="@yield('description', 'Reproduction, refonte et programmation de clés auto, moto et poids lourd à Douala, Yaoundé et partout au Cameroun.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @stack('head')
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=17">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-64.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-camer-auto-key-160.png') }}">
</head>
<body>

{{-- ─── TOPBAR ─── --}}
<div class="topbar">
    <div class="tb-left">
        <span class="tb-item"><span class="tb-dot"></span> Ouvert : {{ $settings['opening_hours'] }}</span>
        <span class="tb-item"><i class="fas fa-map-marker-alt"></i> {{ $settings['address'] }}</span>
        <a class="tb-item" href="mailto:{{ $settings['email'] }}"><i class="far fa-envelope"></i> {{ $settings['email'] }}</a>
    </div>
    <div class="tb-right">
        <a class="tb-phone" href="tel:{{ $tel }}"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</a>
        <div class="lang-pick">
            <button type="button" class="lang-btn">🇫🇷 FR <span class="lang-arr">▾</span></button>
            <div class="lang-drop">
                <span class="lang-opt active">🇫🇷 Français</span>
                <span class="lang-opt" style="opacity:.5">🇬🇧 English (bientôt)</span>
            </div>
        </div>
        <a class="tb-login" href="{{ route('orders.track') }}"><i class="far fa-user"></i> Suivre ma demande</a>
    </div>
</div>

{{-- ─── NAVIGATION ─── --}}
<header class="site-nav">
    <a href="{{ route('home') }}" class="brand-lockup" aria-label="{{ $settings['company_name'] }} — accueil">
        <img src="{{ asset('images/logo-camer-auto-key-160.png') }}" alt="" class="brand-lockup-logo" width="52" height="52">
        <span class="brand-lockup-text">CAMER <b>AUTO KEY</b><small>Clés auto · Programmation</small></span>
    </a>

    <button type="button" class="mobile-menu-btn" id="menu-btn" aria-label="Menu" aria-expanded="false">
        <span class="hamburger-lines"><span class="line line1"></span><span class="line line2"></span><span class="line line3"></span></span>
    </button>

    <ul class="nav-links" id="nav-links">
        <li class="mobile-menu-header">
            <span class="brand-lockup"><img src="{{ asset('images/logo-camer-auto-key-160.png') }}" alt="" class="brand-lockup-logo" width="44" height="44"><span class="brand-lockup-text">CAMER <b>AUTO KEY</b></span></span>
            <button type="button" data-close-menu aria-label="Fermer">&times;</button>
        </li>
        <li><a href="{{ route('home') }}" @class(['nav-active' => request()->routeIs('home')]) @if (request()->routeIs('home')) aria-current="page" @endif>Accueil</a></li>
        <li @class(['has-sub', 'has-mega', 'nav-active' => request()->routeIs('services.*', 'brands.*')])>
            <a href="{{ route('services.index') }}">Services</a>
            <div class="sub-menu mega-menu">
                <div class="mega-col">
                    <div class="mega-title">Prestations</div>
                    @foreach ($navServices as $s)
                        <a href="{{ route('services.show', $s) }}" @class(['current' => request()->is('service/'.$s->slug)])>{{ $s->title }}</a>
                    @endforeach
                </div>
                <div class="mega-col">
                    <div class="mega-title">Marques auto</div>
                    <div class="mega-brand-grid">
                        @foreach ($navBrands['auto'] ?? [] as $b)
                            <a href="{{ route('brands.show', $b) }}" @class(['current' => request()->is('service/reproduction-cle-'.$b->slug)])>{{ $b->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="mega-col">
                    <div class="mega-title">Camions</div>
                    @foreach ($navBrands['pl'] ?? [] as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                    <div class="mega-title" style="margin-top:14px">Deux-roues</div>
                    @foreach (($navBrands['moto'] ?? collect())->take(5) as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                    <a href="{{ route('brands.index') }}" class="is-highlight-link">Voir les {{ $navBrands->flatten()->count() }} marques →</a>
                </div>
            </div>
        </li>
        <li><a href="{{ route('garage') }}" @class(['nav-active' => request()->routeIs('garage')]) @if (request()->routeIs('garage')) aria-current="page" @endif><i class="fas fa-tools" style="font-size:11px;opacity:.6"></i> Garage 3K</a></li>
        <li><a href="{{ route('about') }}" @class(['nav-active' => request()->routeIs('about')]) @if (request()->routeIs('about')) aria-current="page" @endif>À propos</a></li>
        <li><a href="{{ route('professionals') }}" @class(['nav-active' => request()->routeIs('professionals')]) @if (request()->routeIs('professionals')) aria-current="page" @endif>Professionnels</a></li>
        <li><a href="{{ route('become-technician') }}" @class(['nav-active' => request()->routeIs('become-technician')]) @if (request()->routeIs('become-technician')) aria-current="page" @endif>Devenir intervenant</a></li>
        <li><a href="{{ route('faq') }}" @class(['nav-active' => request()->routeIs('faq')]) @if (request()->routeIs('faq')) aria-current="page" @endif>FAQ</a></li>
        <li><a href="{{ route('contact') }}" @class(['nav-active' => request()->routeIs('contact')]) @if (request()->routeIs('contact')) aria-current="page" @endif>Contact</a></li>
        <li><a href="{{ route('home') }}#commande" class="nav-cta"><i class="fas fa-key"></i> Ma clé</a></li>
    </ul>
</header>

<main>
    @yield('content')
</main>

{{-- ─── FOOTER ─── --}}
<footer class="site-footer">
    <div class="container">
        <div class="ft-brands">
            <a href="{{ route('home') }}" class="ft-brand">
                <img src="{{ asset('images/logo-camer-auto-key-160.png') }}" alt="Logo {{ $settings['company_name'] }}" width="64" height="64" loading="lazy">
                <span><b>{{ $settings['company_name'] }}</b>Vente, reproduction et programmation de clés auto · {{ $settings['phone'] }}</span>
            </a>
            <a href="{{ route('garage') }}" class="ft-brand">
                <img src="{{ asset('images/logo-3k-auto-services.webp') }}" alt="Logo {{ $settings['garage_name'] }}" width="64" height="64" loading="lazy">
                <span><b>{{ $settings['garage_name'] }}</b>Garage : mécanique, entretien, diagnostic · {{ $settings['garage_phone'] }}</span>
            </a>
        </div>
        <div class="ft-widgets">
            <div>
                <div class="ft-title">Navigation</div>
                <ul class="ft-links">
                    <li><a href="{{ route('home') }}">– Accueil</a></li>
                    <li><a href="{{ route('orders.track') }}">– Suivre ma demande</a></li>
                    <li><a href="{{ route('about') }}">– Qui sommes-nous ?</a></li>
                    <li><a href="{{ route('terms') }}">– Conditions d’intervention</a></li>
                    <li><a href="{{ route('brands.index') }}">– Clés par marque</a></li>
                                        <li><a href="{{ route('faq') }}">– Questions fréquentes</a></li>
                    <li><a href="{{ route('contact') }}">– Contact</a></li>
                                        <li><a href="{{ route('become-technician') }}">– Devenir intervenant</a></li>
                    <li><a href="{{ route('legal') }}">– Mentions légales</a></li>
                </ul>
            </div>
            <div>
                <div class="ft-title">Prestations</div>
                <ul class="ft-links">
                    @foreach ($navServices as $s)
                        <li><a href="{{ route('services.show', $s) }}">– {{ $s->title }}</a></li>
                    @endforeach
                    <li><a href="{{ route('garage') }}">– Garage 3K Auto Services</a></li>
                    <li><a href="{{ route('professionals') }}">– Flottes entreprises</a></li>
                </ul>
            </div>
            <div>
                <div class="ft-title">Marques</div>
                <div class="ft-sub">Voitures</div>
                <div class="bt-pills">
                    @foreach ($navBrands['auto'] ?? [] as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                </div>
                <div class="ft-sub">Camions &amp; deux-roues</div>
                <div class="bt-pills">
                    @foreach (collect($navBrands['pl'] ?? [])->merge($navBrands['moto'] ?? []) as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="ft-title">Nous joindre</div>
                <ul class="ft-address">
                    <li><i class="fas fa-phone-alt"></i><a href="tel:{{ $tel }}">{{ $settings['phone'] }}</a></li>
                    @if ($settings['phone_secondary'])
                        <li><i class="fas fa-phone-alt"></i><a href="tel:{{ $telBe }}">{{ $settings['phone_secondary'] }}</a></li>
                    @endif
                    <li><i class="fas fa-envelope"></i><a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></li>
                    <li><i class="fas fa-map-marker-alt"></i><span>{{ $settings['address'] }}</span></li>
                </ul>
                <p style="margin:16px 0 0">Recevez nos offres par e-mail :</p>
                <form class="ft-news" method="POST" action="{{ route('newsletter') }}">
                    @csrf
                    <input type="email" name="email" placeholder="Votre e-mail" required aria-label="Votre e-mail">
                    <button class="btn-ft">OK</button>
                </form>
                @if (session('newsletter'))
                    <p style="color:#4ade80;margin:0">{{ session('newsletter') }}</p>
                @endif
                <div class="ft-social">
                    <a href="{{ $settings['social_facebook'] ?: '#' }}" aria-label="Facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ $settings['social_tiktok'] ?: '#' }}" aria-label="TikTok" target="_blank" rel="noopener"><i class="fab fa-tiktok"></i></a>
                    <a href="{{ $settings['social_youtube'] ?: '#' }}" aria-label="YouTube" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a>
                    <a href="{{ $settings['social_instagram'] ?: '#' }}" aria-label="Instagram" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <div class="ft-boxes">
            <div class="ft-box">
                <div>
                    <h4>Vous êtes technicien ?</h4>
                    <p>Serrurier auto ou spécialiste de l’électronique embarquée ? Travaillez avec {{ $settings['company_name'] }} sur des demandes proches de chez vous.</p>
                </div>
                <a href="{{ route('become-technician') }}" class="btn-ft">Nous rejoindre</a>
            </div>
            <div class="ft-box">
                <div>
                    <h4>Vous gérez des véhicules ?</h4>
                    <p>Taxis, transporteurs, loueurs, sociétés : clés de secours pour toute la flotte et un interlocuteur unique.</p>
                </div>
                <a href="{{ route('professionals') }}" class="btn-ft">Offre pro</a>
            </div>
        </div>
    </div>
    <div class="ft-trust">
        <span><i class="fas fa-shield-alt"></i>Techniciens assurés</span>
        <span><i class="fas fa-map-marker-alt"></i>Boutique et garage à Douala — interventions dans tout le Cameroun</span>
        <span><i class="fas fa-star"></i>Clients satisfaits à Douala</span>
    </div>
    <div class="ft-copy">© Copyright {{ date('Y') }} {{ $settings['company_name'] }} — Tous droits réservés · <a href="{{ route('legal') }}">Mentions légales</a></div>
</footer>

@if ($settings['whatsapp'])
    <a class="wa-float" href="https://wa.me/{{ $settings['whatsapp'] }}" target="_blank" rel="noopener" aria-label="Écrivez-nous sur WhatsApp">
        <i class="fab fa-whatsapp"></i><span class="wa-label">Écrivez-nous</span>
    </a>
@endif

<script>
(function () {
    const btn = document.getElementById('menu-btn'), nav = document.getElementById('nav-links');
    const toggle = (open) => {
        nav.classList.toggle('show', open); btn.classList.toggle('active', open);
        document.body.classList.toggle('nav-open', open); btn.setAttribute('aria-expanded', open);
    };
    btn.addEventListener('click', () => toggle(!nav.classList.contains('show')));
    nav.querySelector('[data-close-menu]').addEventListener('click', () => toggle(false));
    nav.querySelectorAll('.has-sub > a').forEach((a) => a.addEventListener('click', (e) => {
        if (window.innerWidth > 900) return;
        e.preventDefault(); a.parentElement.classList.toggle('active-mobile');
    }));
    nav.querySelectorAll('a[href*="#"]').forEach((a) => a.addEventListener('click', () => toggle(false)));
})();
</script>
@stack('scripts')
</body>
</html>
