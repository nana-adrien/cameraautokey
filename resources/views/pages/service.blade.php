@extends('layouts.site')
@section('title', $service->title.' — intervention 24h/24')
@section('description', $service->excerpt)

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-tundra-blanc.webp', 
    'alert' => true,
    'title' => $service->title,
    'titleHtml' => e($service->title).'<br>à <em>Douala</em>',
    'subtitle' => e($service->excerpt).' <strong>Prix confirmé avant de commencer, paiement après essai.</strong>',
    'badges' => ['🔑 Clé neuve sans l’ancienne', '📍 Boutique à Douala', '📱 Orange Money / MTN MoMo', '🛡️ Garantie 1 mois'],
    'crumb' => $service->title,
])
@include('partials.service-body', [
    'lead' => '<strong>'.e($service->title).'</strong> — '.nl2br(e(\Illuminate\Support\Str::before($service->content, "\n\n"))).' À partir de <strong>'.\App\Support\Money::fcfa($service->price_from).'</strong> selon le modèle, tarif confirmé avant intervention.',
    'subject' => mb_strtolower($service->title),
    'priceFrom' => $service->price_from,
    'links' => $others->mapWithKeys(fn ($o) => [route('services.show', $o) => $o->title])->all(),
])
@endsection
