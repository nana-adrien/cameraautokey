@push('jsonld')
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::breadcrumb($breadcrumbItems ?? [[strip_tags($crumb ?? ($title ?? '')), null]])) !!}</script>
@endpush
{{-- Bandeau de page : $title (HTML autorisé via $titleHtml), $subtitle, $cta (bool), $badges (array) --}}
@php $tel = preg_replace('/\s+/', '', $settings['phone']); @endphp
@if ($alert ?? false)
    <div class="alert-bar">🔑 Bloqué sans clé ? Appelez le <a href="tel:{{ $tel }}">{{ $settings['phone'] }}</a> ou <a href="{{ route('home') }}#commande">décrivez votre clé en ligne</a> — boutique à Akwa, face à l’ex-direction BOCOM</div>
@endif
@php $heroImg = asset('images/'.($image ?? 'photos/atelier-cour.webp')); @endphp
<section class="svc-hero">
    <img class="svc-hero-bg" src="{{ $heroImg }}" alt="" fetchpriority="high" style="object-position:{{ $position ?? '50% 50%' }}">
    <div class="svc-hero-inner">
    <div class="svc-hero-text">
        @isset($logo)
            @if ($logo)<span class="hero-logo"><img src="{{ $logo }}" alt="" width="64" height="64"></span>@endif
        @endisset
        <h1>{!! $titleHtml ?? e($title) !!}</h1>
        @isset($subtitle)<p>{!! $subtitle !!}</p>@endisset
        @if ($cta ?? true)
            <a href="{{ route('home') }}#commande" class="btn-red">Décrire ma clé</a>
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
        <span><i class="fas fa-map-marker-alt"></i> Boutique à Akwa, Douala</span>
        <span><i class="fas fa-key"></i> Clé neuve même sans l’ancienne</span>
        <span><i class="fas fa-ban"></i> Clés perdues effaçables</span>
        <span><i class="fas fa-mobile-alt"></i> Mobile Money accepté</span>
    </div>
@endif
<div class="breadcrumb"><a href="{{ route('home') }}">Accueil</a> › <b>{{ $crumb ?? strip_tags($title ?? '') }}</b></div>
