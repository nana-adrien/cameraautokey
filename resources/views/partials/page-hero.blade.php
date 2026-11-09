{{-- Bandeau de page : $title (HTML autorisé via $titleHtml), $subtitle, $cta (bool), $badges (array) --}}
@php $tel = preg_replace('/\s+/', '', $settings['phone']); @endphp
@if ($alert ?? false)
    <div class="alert-bar">🚨 Clé perdue ou véhicule bloqué maintenant ? Appelez en urgence : <a href="tel:{{ $tel }}">{{ $settings['phone'] }}</a> — <a href="{{ route('home') }}#commande">demande en ligne</a> — intervention 24h/24</div>
@endif
@php $heroImg = asset('images/'.($image ?? 'photos/atelier-cour.webp')); @endphp
<section class="svc-hero">
    <img class="svc-hero-bg" src="{{ $heroImg }}" alt="" fetchpriority="high" style="object-position:{{ $position ?? '50% 50%' }}">
    <div class="svc-hero-inner">
    <div class="svc-hero-text">
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
