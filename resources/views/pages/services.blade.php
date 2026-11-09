@extends('layouts.site')
@section('title', 'Nos services')

@section('content')
@include('partials.page-hero', ['title' => 'Nos services', 'subtitle' => 'Reproduction, programmation, ouverture et réparation de clés pour tous types de véhicules.'])
<section class="section">
    <div class="container">
        <div class="grid grid-3">
            @foreach ($services as $service)
                <a class="card" href="{{ route('services.show', $service) }}">
                    <div class="icon">{{ $service->icon }}</div>
                    <h3>{{ $service->title }}</h3>
                    <p class="muted">{{ $service->excerpt }}</p>
                    <span class="price">À partir de {{ $service->price_from }} € TTC</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
