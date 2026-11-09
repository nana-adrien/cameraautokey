@extends('layouts.site')
@section('title', 'Nos services de serrurerie automobile')

@section('content')
@include('partials.page-hero', ['image' => 'photos/mecanicien-capot-toyota.webp', 'titleHtml' => 'Nos <em>services</em>', 'title' => 'Nos services', 'subtitle' => 'Copie, codage, ouverture et réparation de clés pour voitures, camions et deux-roues, à Akwa ou chez vous.'])
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
