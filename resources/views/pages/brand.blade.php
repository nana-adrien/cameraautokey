@extends('layouts.site')
@section('title', 'Reproduction de clé '.$brand->name)
@section('description', $brand->description)

@section('content')
@include('partials.page-hero', ['title' => 'Reproduction de clé '.$brand->name, 'subtitle' => $brand->description, 'crumb' => 'Marques › '.$brand->name])
<section class="section">
    <div class="container order-layout">
        <div>
            <div class="card prose">
                <h2 style="margin-top:0">Votre clé {{ $brand->name }} refaite sur place</h2>
                <p>Double de clé, perte totale, télécommande défaillante ou clé mains libres : nos techniciens {{ $settings['company_name'] }} connaissent les systèmes antidémarrage {{ $brand->name }} ({{ mb_strtolower($brand->categoryLabel()) }}) et disposent des outils de programmation adaptés.</p>
                <p>La clé est taillée et codée directement à côté de votre véhicule. Vous repartez avec une clé fonctionnelle, testée devant vous, et garantie un mois.</p>
            </div>
            <h3 style="margin:30px 0 14px">Prestations disponibles pour {{ $brand->name }}</h3>
            <div class="grid grid-2">
                @foreach ($services as $service)
                    <a class="card" href="{{ route('services.show', $service) }}">
                        <div class="icon">{{ $service->icon }}</div>
                        <h3>{{ $service->title }}</h3>
                        <span class="price">À partir de {{ $service->price_from }} € TTC</span>
                    </a>
                @endforeach
            </div>
        </div>
        <aside class="summary">
            <div class="summary-head">Clé {{ $brand->name }}</div>
            <div class="summary-body">
                <p class="muted">Prix estimé immédiatement, sans engagement.</p>
                <a href="{{ route('home') }}#commande" class="btn btn-red btn-block">Faire une demande</a>
                <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}" class="btn btn-outline btn-block" style="margin-top:10px">📞 {{ $settings['phone'] }}</a>
            </div>
        </aside>
    </div>
</section>
@endsection
