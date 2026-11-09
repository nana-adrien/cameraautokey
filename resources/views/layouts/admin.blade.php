<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'Tableau de bord') · Admin CAMER AUTO KEY</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=1">
</head>
<body>
<div class="app">
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="logo"><span class="mark">🔑</span>CAMER AUTO KEY</a>
        <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>📊 Tableau de bord</a>

        <div class="group">Activité</div>
        <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>🧾 Demandes @if ($pendingOrders)<span class="count">{{ $pendingOrders }}</span>@endif</a>
        <a href="{{ route('admin.messages.index') }}" @class(['active' => request()->routeIs('admin.messages.*')])>✉️ Messages @if ($unreadMessages)<span class="count">{{ $unreadMessages }}</span>@endif</a>
        <a href="{{ route('admin.technicians.index') }}" @class(['active' => request()->routeIs('admin.technicians.*')])>👷 Techniciens</a>

        <div class="group">Contenu du site</div>
        <a href="{{ route('admin.services.index') }}" @class(['active' => request()->routeIs('admin.services.*')])>🛠️ Services</a>
        <a href="{{ route('admin.brands.index') }}" @class(['active' => request()->routeIs('admin.brands.*')])>🚗 Marques</a>
        <a href="{{ route('admin.testimonials.index') }}" @class(['active' => request()->routeIs('admin.testimonials.*')])>⭐ Avis clients</a>
        <a href="{{ route('admin.faqs.index') }}" @class(['active' => request()->routeIs('admin.faqs.*')])>❓ FAQ</a>

        <div class="group">Configuration</div>
        <a href="{{ route('admin.settings.edit') }}" @class(['active' => request()->routeIs('admin.settings.*')])>⚙️ Paramètres</a>
        <a href="{{ route('home') }}" target="_blank">🌐 Voir le site</a>
    </aside>

    <div class="main">
        <header class="topbar">
            <button class="burger" type="button" aria-label="Menu" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <span class="muted">@yield('title', 'Tableau de bord')</span>
            <div class="user">
                <span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-light btn-sm">Déconnexion</button></form>
            </div>
        </header>
        <div class="content">
            @if (session('success'))
                <div class="alert alert-success">✔ {{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">Merci de corriger les erreurs du formulaire.</div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
