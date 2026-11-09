@extends('layouts.site')
@section('title', 'Reproduction clé '.$brand->name.' à domicile')
@section('description', $brand->description)

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-interieur.webp', 
    'alert' => true,
    'logo' => $brand->logoUrl(),
    'title' => 'Reproduction clé '.$brand->name,
    'titleHtml' => 'Votre clé <em>'.e($brand->name).'</em><br>refaite à Douala',
    'subtitle' => e($brand->description).' <strong>Copie, remplacement, télécommande ou clé mains libres.</strong>',
    'badges' => ['🔑 Copie de clé '.$brand->name, '💻 Codage sur place', '📍 Atelier à Akwa'],
    'crumb' => 'Marques › '.$brand->name,
])
@include('partials.service-body', [
    'lead' => 'Votre <strong>clé '.e($brand->name).'</strong> perdue, cassée ou à dupliquer ? Nos techniciens connaissent les systèmes antidémarrage '.e($brand->name).' ('.e(mb_strtolower($brand->categoryLabel())).') et fabriquent votre nouvelle clé directement à côté du véhicule.',
    'subject' => 'clé '.$brand->name,
    'priceFrom' => 30000,
    'links' => $services->mapWithKeys(fn ($s) => [route('services.show', $s) => $s->title])->all(),
    'linksTitle' => 'Prestations '.$brand->name,
])
@endsection
