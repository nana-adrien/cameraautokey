@extends('layouts.site')
@section('title', $service->title.' à Douala')
@section('description', \Illuminate\Support\Str::limit($service->excerpt.' Boutique à Akwa, prix en FCFA annoncés avant intervention.', 152))
@push('jsonld')
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::service($service->title.' à Douala', $service->excerpt, $service->price_from)) !!}</script>
@endpush


@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-tundra-blanc.webp', 
    'alert' => true,
    'title' => $service->title,
    'titleHtml' => e($service->title).'<br>à <em>Douala</em>',
    'subtitle' => e($service->excerpt).' <strong>Prix confirmé avant de commencer, paiement après essai.</strong>',
    'badges' => ['🔑 Clé neuve sans l’ancienne', '📍 Boutique à Akwa', '📱 Orange Money / MTN MoMo', '🛡️ Garantie 1 mois'],
    'crumb' => $service->title,
    'breadcrumbItems' => [['Services', route('services.index')], [$service->title, null]],
])
@include('partials.service-body', [
    'lead' => '<strong>'.e($service->title).'</strong> — '.nl2br(e(\Illuminate\Support\Str::before($service->content, "\n\n"))).' À partir de <strong>'.\App\Support\Money::fcfa($service->price_from).'</strong> selon le modèle, tarif confirmé avant intervention.',
    'subject' => mb_strtolower($service->title),
    'priceFrom' => $service->price_from,
    'links' => $others->mapWithKeys(fn ($o) => [route('services.show', $o) => $o->title])->all(),
])
@endsection
