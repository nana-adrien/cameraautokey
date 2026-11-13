@extends('layouts.site')
@section('title', 'Recrutement : offres d’emploi à Douala')
@section('description', 'Offres d’emploi chez CAMER AUTO KEY et au garage 3K Auto Services à Douala : techniciens clés auto, mécaniciens, électriciens auto. Postulez en ligne avec votre CV.')

@section('content')
@include('partials.page-hero', ['image' => 'photos/installation-camera-recul.webp', 'titleHtml' => 'Nous <em>recrutons</em>', 'title' => 'Recrutement', 'subtitle' => 'Rejoignez <strong>CAMER AUTO KEY</strong> ou le garage <strong>3K Auto Services</strong> : consultez nos offres et envoyez votre CV en ligne.', 'cta' => false, 'trust' => false])
<div class="content-wrap">
    <nav class="job-filter" aria-label="Filtrer par entreprise">
        <a href="{{ route('careers.index') }}" @class(['active' => $company === ''])>Toutes les offres</a>
        @foreach (\App\Models\JobOffer::COMPANIES as $key => $label)
            <a href="{{ route('careers.index', ['entreprise' => $key]) }}" @class(['active' => $company === $key])>{{ $label }}</a>
        @endforeach
    </nav>

    @if ($offers->isEmpty())
        <x-sc icon="fas fa-briefcase" title="Aucune offre pour le moment">
            <p>Aucune offre n’est ouverte actuellement. Revenez bientôt ou envoyez une candidature spontanée depuis la page <a href="{{ route('contact') }}" style="text-decoration:underline">Contact</a>.</p>
        </x-sc>
    @else
        <div class="card-grid">
            @foreach ($offers as $offer)
                <a class="svc-card job-card" href="{{ route('careers.show', $offer) }}">
                    <span class="job-company">{{ $offer->companyLabel() }}</span>
                    <b>{{ $offer->title }}</b>
                    <div class="job-meta">
                        <span><i class="fas fa-file-signature"></i> {{ $offer->contract_type }}</span>
                        <span><i class="fas fa-map-marker-alt"></i> {{ $offer->location }}</span>
                        @if ($offer->closes_at)<span><i class="far fa-calendar"></i> Jusqu’au {{ $offer->closes_at->format('d/m/Y') }}</span>@endif
                    </div>
                    <p>{{ $offer->summary }}</p>
                    <span>Voir l’offre et postuler →</span>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
