@extends('layouts.site')
@section('title', 'Reproduction clé '.$brand->name.' à domicile')
@section('description', $brand->description)

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-interieur.webp', 
    'alert' => true,
    'title' => 'Reproduction clé '.$brand->name,
    'titleHtml' => 'Reproduction clé <em>'.e($brand->name).'</em><br>à domicile 24h/24',
    'subtitle' => e($brand->description).' <strong>Double, perte totale, télécommande ou clé mains libres.</strong>',
    'badges' => ['🔑 Double de clé '.$brand->name, '💻 Programmation sur place', '⚡ Urgence 24h/24'],
    'crumb' => 'Marques › '.$brand->name,
])
@include('partials.service-body', [
    'lead' => 'Votre <strong>clé '.e($brand->name).'</strong> perdue, cassée ou à dupliquer ? Nos techniciens connaissent les systèmes antidémarrage '.e($brand->name).' ('.e(mb_strtolower($brand->categoryLabel())).') et fabriquent votre nouvelle clé directement à côté du véhicule.',
    'subject' => 'clé '.$brand->name,
    'priceFrom' => 89,
    'links' => $services->mapWithKeys(fn ($s) => [route('services.show', $s) => $s->title])->all(),
    'linksTitle' => 'Prestations '.$brand->name,
])
@endsection
