@extends('layouts.site')
@section('title', $service->title)
@section('description', $service->excerpt)

@section('content')
@include('partials.page-hero', ['title' => $service->icon.' '.$service->title, 'subtitle' => $service->excerpt, 'crumb' => 'Services › '.$service->title])
<section class="section">
    <div class="container order-layout">
        <div class="card prose">
            @foreach (preg_split("/\n\s*\n/", $service->content) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
            <h3>Comment ça se passe ?</h3>
            <div class="steps-strip" style="grid-template-columns:repeat(2,1fr);margin-top:30px">
                <div class="card"><span class="num">1</span><b>Votre demande</b><p class="muted" style="margin:0">En ligne ou par téléphone, 24h/24.</p></div>
                <div class="card"><span class="num">2</span><b>Prix confirmé</b><p class="muted" style="margin:0">Avant le départ du technicien.</p></div>
                <div class="card"><span class="num">3</span><b>Intervention</b><p class="muted" style="margin:0">Sur place, avec un atelier mobile.</p></div>
                <div class="card"><span class="num">4</span><b>Règlement</b><p class="muted" style="margin:0">Uniquement après validation.</p></div>
            </div>
        </div>
        <aside class="summary">
            <div class="summary-head">À partir de {{ $service->price_from }} € TTC</div>
            <div class="summary-body">
                <a href="{{ route('home') }}#commande" class="btn btn-red btn-block">Faire une demande</a>
                <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}" class="btn btn-outline btn-block" style="margin-top:10px">📞 {{ $settings['phone'] }}</a>
            </div>
            <div class="summary-foot">
                <b style="color:var(--ink)">Autres services</b>
                @foreach ($others as $o)
                    <a href="{{ route('services.show', $o) }}">{{ $o->icon }} {{ $o->title }}</a>
                @endforeach
            </div>
        </aside>
    </div>
</section>
@endsection
