<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Connexion · Admin CAMER AUTO KEY</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-64.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-camer-auto-key-160.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@500;600;700;800;900&family=Barlow+Condensed:wght@700;800;900&family=DM+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=4">
</head>
<body>
<div class="login-page">
    <form method="POST" action="{{ route('admin.login.attempt') }}" class="login-card form">
        @csrf
        <div style="text-align:center">
            <img src="{{ asset('images/logo-camer-auto-key-160.png') }}" alt="CAMER AUTO KEY" width="76" height="76" class="login-logo">
            <h1 style="font-size:1.4rem">CAMER AUTO KEY</h1>
            <p class="muted" style="margin:4px 0 0">Espace administrateur</p>
        </div>
        <div class="field">
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')<div class="err">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>
        <label class="switch"><input type="checkbox" name="remember" value="1"> Se souvenir de moi</label>
        <button class="btn btn-primary" style="justify-content:center;padding:12px">Se connecter</button>
        <a href="{{ route('home') }}" class="muted" style="text-align:center;font-size:.88rem">← Retour au site</a>
    </form>
</div>
</body>
</html>
