@extends('layouts.site')
@section('title', 'Contact')

@section('content')
@include('partials.page-hero', ['title' => 'Contactez-nous', 'subtitle' => 'Une question, un devis ? Nous répondons rapidement, 7j/7.'])
<section class="section">
    <div class="container order-layout">
        <div>@include('partials.message-form', ['type' => 'contact'])</div>
        <aside class="summary">
            <div class="summary-head">Nos coordonnées</div>
            <div class="summary-body">
                <div class="summary-row"><span>Téléphone</span><span><a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}">{{ $settings['phone'] }}</a></span></div>
                @if ($settings['phone_secondary'])
                    <div class="summary-row"><span>Belgique</span><span>{{ $settings['phone_secondary'] }}</span></div>
                @endif
                <div class="summary-row"><span>E-mail</span><span>{{ $settings['email'] }}</span></div>
                <div class="summary-row"><span>Zone</span><span>{{ $settings['address'] }}</span></div>
                <div class="summary-row"><span>Horaires</span><span>24h/24 – 7j/7</span></div>
            </div>
        </aside>
    </div>
</section>
@endsection
