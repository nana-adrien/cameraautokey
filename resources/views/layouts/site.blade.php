@php
    $tel = preg_replace('/\s+/', '', $settings['phone']);
    $telBe = preg_replace('/\s+/', '', $settings['phone_secondary']);
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Reproduction clé voiture à domicile 24h/24') | {{ $settings['company_name'] }} — Serrurier auto mobile</title>
    <meta name="description" content="@yield('description', 'Double de clé, perte totale, ouverture et programmation de clés auto, moto et poids lourd à domicile, 24h/24 et 7j/7.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @stack('head')
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=6">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%231a2f5e'/><path d='M40 25l25 25-25 25h16l25-25-25-25z' fill='%23d6262e'/></svg>">
</head>
<body>

{{-- ─── TOPBAR ─── --}}
<div class="topbar">
    <div class="tb-left">
        <span class="tb-item"><span class="tb-dot"></span> Disponible 24h/24 — 7j/7</span>
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
    <a href="{{ route('home') }}" aria-label="{{ $settings['company_name'] }} — accueil">
        <img src="{{ asset('images/logo.svg') }}" alt="{{ $settings['company_name'] }}" class="logo-img" width="190" height="41">
    </a>

    <button type="button" class="mobile-menu-btn" id="menu-btn" aria-label="Menu" aria-expanded="false">
        <span class="hamburger-lines"><span class="line line1"></span><span class="line line2"></span><span class="line line3"></span></span>
    </button>

    <ul class="nav-links" id="nav-links">
        <li class="mobile-menu-header">
            <img src="{{ asset('images/logo.svg') }}" alt="" class="logo-img">
            <button type="button" data-close-menu aria-label="Fermer">&times;</button>
        </li>
        <li><a href="{{ route('home') }}" @class(['nav-active' => request()->routeIs('home')])>Accueil</a></li>
        <li @class(['has-sub', 'has-mega', 'nav-active' => request()->routeIs('services.*', 'brands.*')])>
            <a href="{{ route('services.index') }}">Nos Services</a>
            <div class="sub-menu mega-menu">
                <div class="mega-col">
                    <div class="mega-title">Nos expertises</div>
                    @foreach ($navServices as $s)
                        <a href="{{ route('services.show', $s) }}">{{ $s->title }}</a>
                    @endforeach
                </div>
                <div class="mega-col">
                    <div class="mega-title">Toutes les marques automobiles</div>
                    <div class="mega-brand-grid">
                        @foreach ($navBrands['auto'] ?? [] as $b)
                            <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="mega-col">
                    <div class="mega-title">Poids lourds</div>
                    @foreach ($navBrands['pl'] ?? [] as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                    <div class="mega-title" style="margin-top:14px">Motos</div>
                    @foreach (($navBrands['moto'] ?? collect())->take(5) as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                    <a href="{{ route('brands.index') }}" class="is-highlight-link">Toutes les marques →</a>
                </div>
            </div>
        </li>
        <li><a href="{{ route('about') }}" @class(['nav-active' => request()->routeIs('about')])>À propos</a></li>
        <li><a href="{{ route('professionals') }}" @class(['nav-active' => request()->routeIs('professionals')])>Professionnels</a></li>
        <li><a href="{{ route('become-technician') }}" @class(['nav-active' => request()->routeIs('become-technician')])>Devenir intervenant</a></li>
        <li><a href="{{ route('faq') }}" @class(['nav-active' => request()->routeIs('faq')])>FAQ</a></li>
        <li><a href="{{ route('contact') }}" @class(['nav-active' => request()->routeIs('contact')])>Contact</a></li>
        <li><a href="{{ route('home') }}#commande" class="nav-cta"><i class="fas fa-bolt"></i> Intervention</a></li>
    </ul>
</header>

<main>
    @yield('content')
</main>

{{-- ─── FOOTER ─── --}}
<footer class="site-footer">
    <div class="container">
        <div class="ft-widgets">
            <div>
                <div class="ft-title">Liens importants</div>
                <ul class="ft-links">
                    <li><a href="{{ route('home') }}">– Accueil</a></li>
                    <li><a href="{{ route('orders.track') }}">– Suivre ma demande</a></li>
                    <li><a href="{{ route('about') }}">– Qui sommes-nous ?</a></li>
                    <li><a href="{{ route('terms') }}">– Conditions d’intervention</a></li>
                    <li><a href="{{ route('brands.index') }}">– Clés par marque</a></li>
                    <li><a href="{{ route('services.show', 'cle-voiture-perdue') }}">– Perte totale des clés</a></li>
                    <li><a href="{{ route('faq') }}">– FAQ complète</a></li>
                    <li><a href="{{ route('contact') }}">– Contact</a></li>
                    <li><a href="{{ route('professionals') }}">– Devenir partenaire</a></li>
                    <li><a href="{{ route('become-technician') }}">– Devenir intervenant</a></li>
                    <li><a href="{{ route('legal') }}">– Mentions légales</a></li>
                </ul>
            </div>
            <div>
                <div class="ft-title">Nos expertises</div>
                <ul class="ft-links">
                    @foreach ($navServices as $s)
                        <li><a href="{{ route('services.show', $s) }}">– {{ $s->title }}</a></li>
                    @endforeach
                    <li><a href="{{ route('professionals') }}">– Flottes entreprises</a></li>
                </ul>
            </div>
            <div>
                <div class="ft-title">Marques couvertes</div>
                <div class="ft-sub">Automobiles &amp; prestige</div>
                <div class="bt-pills">
                    @foreach ($navBrands['auto'] ?? [] as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                </div>
                <div class="ft-sub">Poids lourds &amp; motos</div>
                <div class="bt-pills">
                    @foreach (collect($navBrands['pl'] ?? [])->merge($navBrands['moto'] ?? []) as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="ft-title">Contact &amp; newsletter</div>
                <ul class="ft-address">
                    <li><i class="fas fa-phone-alt"></i><a href="tel:{{ $tel }}">France : {{ $settings['phone'] }}</a></li>
                    @if ($settings['phone_secondary'])
                        <li><i class="fas fa-phone-alt"></i><a href="tel:{{ $telBe }}">Belgique : {{ $settings['phone_secondary'] }}</a></li>
                    @endif
                    <li><i class="fas fa-envelope"></i><a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></li>
                </ul>
                <p style="margin:16px 0 0">Restez informé(e) de nos actualités :</p>
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
                    <h4>Accès intervenants</h4>
                    <p>Espace dédié aux techniciens et partenaires {{ $settings['company_name'] }} : recevez des demandes près de chez vous et développez votre activité avec notre réseau.</p>
                </div>
                <a href="{{ route('become-technician') }}" class="btn-ft">Voir plus</a>
            </div>
            <div class="ft-box">
                <div>
                    <h4>Espace entreprises</h4>
                    <p>Pour les flottes et les professionnels : demandes groupées, contrats, suivi et accompagnement personnalisé.</p>
                </div>
                <a href="{{ route('professionals') }}" class="btn-ft">Voir plus</a>
            </div>
        </div>
    </div>
    <div class="ft-trust">
        <span><i class="fas fa-shield-alt"></i>RC Pro — techniciens assurés</span>
        <span><i class="fas fa-calendar-alt"></i>Service 24h/24 – 7j/7</span>
        <span><i class="fas fa-star"></i>4,9/5 avis clients</span>
    </div>
    <div class="ft-copy">© Copyright {{ date('Y') }} {{ $settings['company_name'] }} — Tous droits réservés · <a href="{{ route('legal') }}">Mentions légales</a></div>
</footer>

@if ($settings['whatsapp'])
    <a class="wa-float" href="https://wa.me/{{ $settings['whatsapp'] }}" target="_blank" rel="noopener" aria-label="Besoin d’aide ? Écrivez-nous sur WhatsApp">
        <i class="fab fa-whatsapp"></i><span class="wa-label">Besoin d’aide ?</span>
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
