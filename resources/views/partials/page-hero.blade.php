{{-- Bandeau de page : $title (HTML autorisé via $titleHtml), $subtitle, $cta (bool), $badges (array) --}}
@php $tel = preg_replace('/\s+/', '', $settings['phone']); @endphp
@if ($alert ?? false)
    <div class="alert-bar">🚨 Clé perdue ou véhicule bloqué maintenant ? Appelez en urgence : <a href="tel:{{ $tel }}">{{ $settings['phone'] }}</a> — <a href="{{ route('home') }}#commande">demande en ligne</a> — intervention 24h/24</div>
@endif
<section class="svc-hero">
    <img src="{{ asset('images/placeholders/'.($image ?? 'page-hero.svg')) }}" alt="" fetchpriority="high">
    <div>
        <h1>{!! $titleHtml ?? e($title) !!}</h1>
        @isset($subtitle)<p>{!! $subtitle !!}</p>@endisset
        @if ($cta ?? true)
            <a href="{{ route('home') }}#commande" class="btn-red">Demande en ligne</a>
        @endif
        @if (! empty($badges))
            <div class="hero-badges">
                @foreach ($badges as $badge)<span class="hbadge">{{ $badge }}</span>@endforeach
            </div>
        @endif
    </div>
</section>
@if ($trust ?? true)
    <div class="trust-strip">
        <span><i class="fas fa-bolt"></i> Intervention prioritaire 24h/24</span>
        <span><i class="fas fa-key"></i> Nouvelle clé créée sans l’original</span>
        <span><i class="fas fa-shield-alt"></i> Anciennes clés désactivables</span>
        <span><i class="far fa-credit-card"></i> Paiement après intervention</span>
    </div>
@endif
<div class="breadcrumb"><a href="{{ route('home') }}">Accueil</a> › <b>{{ $crumb ?? strip_tags($title ?? '') }}</b></div>
