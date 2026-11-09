<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Reproduction de clé voiture à domicile 24h/24') | {{ $settings['company_name'] }}</title>
    <meta name="description" content="@yield('description', 'Double de clé, perte totale, ouverture et programmation de clés auto, moto et poids lourd à domicile, 24h/24.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=1">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔑</text></svg>">
    @stack('head')
</head>
<body>
<div class="topbar">
    <div class="container">
        <div class="left">
            <span>🕒 Disponible 24h/24 – 7j/7</span>
            <span>✉️ {{ $settings['email'] }}</span>
            <span>📍 {{ $settings['address'] }}</span>
        </div>
        <div class="right">
            <a class="pill" href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}">📞 {{ $settings['phone'] }}</a>
            <a class="pill dark" href="{{ route('orders.track') }}">Suivre ma demande</a>
        </div>
    </div>
</div>

<header class="header">
    <div class="container">
        <a href="{{ route('home') }}" class="logo" aria-label="{{ $settings['company_name'] }} — accueil">
            <span class="logo-mark">🔑</span>
            <span>CAMER<span class="accent">AUTO</span>KEY</span>
        </a>

        <button class="burger" type="button" aria-label="Ouvrir le menu" onclick="document.getElementById('nav').classList.toggle('open')">☰</button>

        <nav class="nav" id="nav">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Accueil</a>
            <div class="dropdown">
                <button type="button" aria-haspopup="true">Nos services ▾</button>
                <div class="dropdown-menu">
                    <div class="col">
                        <h5>Prestations</h5>
                        @foreach ($navServices as $s)
                            <a href="{{ route('services.show', $s) }}">{{ $s->title }}</a>
                        @endforeach
                    </div>
                    <div class="col brands">
                        <h5>Marques auto</h5>
                        @foreach ($navBrands['auto'] ?? [] as $b)
                            <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                        @endforeach
                    </div>
                    <div class="col brands">
                        <h5>Poids lourds</h5>
                        @foreach ($navBrands['pl'] ?? [] as $b)
                            <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                        @endforeach
                        <h5 style="margin-top:12px">Motos</h5>
                        @foreach ($navBrands['moto'] ?? [] as $b)
                            <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>À propos</a>
            <a href="{{ route('professionals') }}" @class(['active' => request()->routeIs('professionals')])>Professionnels</a>
            <a href="{{ route('become-technician') }}" @class(['active' => request()->routeIs('become-technician')])>Devenir intervenant</a>
            <a href="{{ route('faq') }}" @class(['active' => request()->routeIs('faq')])>FAQ</a>
            <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>Contact</a>
            <a href="{{ route('home') }}#commande" class="btn btn-red" style="margin-left:8px">+ Intervention</a>
        </nav>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="container">
        <div class="cols">
            <div>
                <a href="{{ route('home') }}" class="logo" style="color:#fff;margin-bottom:16px">
                    <span class="logo-mark" style="background:var(--red)">🔑</span>
                    <span>CAMER<span class="accent" style="color:#ff6b70">AUTO</span>KEY</span>
                </a>
                <p>Serrurerie automobile mobile : reproduction, programmation et ouverture de véhicules, à domicile et 24h/24.</p>
                <ul>
                    <li><a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}">📞 {{ $settings['phone'] }}</a></li>
                    @if ($settings['phone_secondary'])
                        <li>📞 {{ $settings['phone_secondary'] }}</li>
                    @endif
                    <li><a href="mailto:{{ $settings['email'] }}">✉️ {{ $settings['email'] }}</a></li>
                </ul>
            </div>
            <div>
                <h4>Liens utiles</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('orders.track') }}">Suivre ma demande</a></li>
                    <li><a href="{{ route('about') }}">Qui sommes-nous ?</a></li>
                    <li><a href="{{ route('terms') }}">Conditions d’intervention</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('become-technician') }}">Devenir intervenant</a></li>
                    <li><a href="{{ route('legal') }}">Mentions légales</a></li>
                </ul>
            </div>
            <div>
                <h4>Marques couvertes</h4>
                <div class="brand-list">
                    @foreach ($navBrands->flatten()->take(30) as $b)
                        <a href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                    @endforeach
                </div>
                <p style="margin-top:12px"><a href="{{ route('brands.index') }}" style="color:#fff;font-weight:700">Toutes les marques →</a></p>
            </div>
            <div>
                <h4>Nos expertises</h4>
                <ul>
                    @foreach ($navServices as $s)
                        <li><a href="{{ route('services.show', $s) }}">{{ $s->title }}</a></li>
                    @endforeach
                    <li><a href="{{ route('professionals') }}">Flottes d’entreprise</a></li>
                </ul>
            </div>
        </div>
        <div class="bottom">
            <span>© {{ date('Y') }} {{ $settings['company_name'] }}. Tous droits réservés.</span>
            <span>Paiement après intervention · Garantie 1 mois sur chaque clé</span>
        </div>
    </div>
</footer>

@if ($settings['whatsapp'])
    <a class="whatsapp" href="https://wa.me/{{ $settings['whatsapp'] }}" target="_blank" rel="noopener" aria-label="Nous écrire sur WhatsApp">💬</a>
@endif

@stack('scripts')
</body>
</html>
