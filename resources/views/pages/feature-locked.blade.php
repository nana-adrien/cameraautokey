@extends('layouts.site')
@section('title', $featureLabel)
@section('noindex', '1')

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-tacoma.webp', 'titleHtml' => 'En cours de <em>développement</em>', 'title' => $featureLabel, 'subtitle' => 'Cette fonctionnalité sera disponible à la livraison du site.', 'cta' => false, 'trust' => false])
<div class="content-wrap" style="max-width:760px">
    <x-sc icon="fas fa-tools" title="{{ $featureLabel }}">
        <p>Cette fonctionnalité est en cours de développement et sera activée lors de la livraison du site.</p>
        <p style="margin-top:16px"><a class="btn-cta" href="{{ route('home') }}">Retour à l’accueil</a></p>
    </x-sc>
</div>
@endsection
