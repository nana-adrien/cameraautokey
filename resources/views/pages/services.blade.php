@extends('layouts.site')
@section('title', 'Services clés auto à Douala : copie, codage, ouverture')
@section('description', 'Remplacement de clés perdues, copie, codage antidémarrage, ouverture sans clé, réparation de télécommande et diagnostic : tous nos services de clés automobiles à Douala.')

@section('content')
@include('partials.page-hero', ['image' => 'photos/mecanicien-capot-toyota.webp', 'titleHtml' => 'Nos <em>services</em>', 'title' => 'Nos services', 'subtitle' => 'Copie, codage, ouverture et réparation de clés pour voitures, camions et deux-roues, en boutique ou chez vous.'])
<div class="content-wrap">
    <div class="card-grid">
        @foreach ($services as $service)
            <a class="svc-card" href="{{ route('services.show', $service) }}">
                <span class="ic">{{ $service->icon }}</span>
                <b>{{ $service->title }}</b>
                <p>{{ $service->excerpt }}</p>
                <span>Découvrir →</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
