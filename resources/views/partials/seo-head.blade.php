{{--
    En-tête commun (site des clés et site du garage) : SEO, aperçus, polices, styles.
    $seoDefaults : title, suffix, description, og_image, geo ("lat;lng"), site_name, theme, icon, touch_icon
    Chaque page peut remplacer : @section('title'), ('title_suffix'), ('description'), ('og_image'), ('geo'), ('noindex').
--}}
@php
    $seoTitle = trim($__env->yieldContent('title', $seoDefaults['title'])).' | '.trim($__env->yieldContent('title_suffix', $seoDefaults['suffix']));
    $seoDescription = trim($__env->yieldContent('description', $seoDefaults['description']));
    $seoImage = asset(trim($__env->yieldContent('og_image', $seoDefaults['og_image'])));
    $seoIndex = config('seo.indexing') && ! trim($__env->yieldContent('noindex'));
    $seoGeo = trim($__env->yieldContent('geo', $seoDefaults['geo']));
@endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoIndex ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="{{ $seoDefaults['theme'] }}">
    {{-- Aperçus WhatsApp / Facebook / X --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_CM">
    <meta property="og:site_name" content="{{ $seoDefaults['site_name'] }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    {{-- Référencement local --}}
    <meta name="geo.region" content="{{ config('seo.region') }}">
    <meta name="geo.placename" content="Douala">
    @if (str_contains($seoGeo, ';') && $seoGeo !== ';')
        <meta name="geo.position" content="{{ $seoGeo }}">
        <meta name="ICBM" content="{{ str_replace(';', ', ', $seoGeo) }}">
    @endif
    @stack('jsonld')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @stack('head')
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=22">
    <link rel="icon" type="image/png" href="{{ asset($seoDefaults['icon']) }}">
    <link rel="apple-touch-icon" href="{{ asset($seoDefaults['touch_icon']) }}">
