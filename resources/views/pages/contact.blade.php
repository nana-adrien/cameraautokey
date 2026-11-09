@extends('layouts.site')
@section('title', 'Contact')

@section('content')
@php $tel = preg_replace('/\s+/', '', $settings['phone']); @endphp
@include('partials.page-hero', ['image' => 'photos/facade-camer-auto-key.webp', 'position' => '50% 40%', 'titleHtml' => '<em>Contactez</em>-nous', 'title' => 'Contact', 'subtitle' => 'Une question, un devis ? Nous répondons rapidement, 7j/7.', 'cta' => false, 'trust' => false])
<div class="content-wrap grid">
    <div>
        <x-sc icon="far fa-envelope" title="Envoyer un message">
            @include('partials.message-form', ['type' => 'contact'])
        </x-sc>
    </div>
    <aside>
        <div class="sidebar-sticky">
            <x-sc icon="fas fa-address-card" title="Nos coordonnées">
                <div class="success-receipt" style="margin:0;max-width:none">
                    <div class="receipt-row"><span class="receipt-lbl">Téléphone</span><a class="receipt-val" href="tel:{{ $tel }}">{{ $settings['phone'] }}</a></div>
                    @if ($settings['phone_secondary'])<div class="receipt-row"><span class="receipt-lbl">Autre ligne</span><span class="receipt-val">{{ $settings['phone_secondary'] }}</span></div>@endif
                    <div class="receipt-row"><span class="receipt-lbl">E-mail</span><span class="receipt-val">{{ $settings['email'] }}</span></div>
                    <div class="receipt-row"><span class="receipt-lbl">Boutique</span><span class="receipt-val">{{ $settings['address'] }}</span></div>
                    @if ($settings['whatsapp'])<div class="receipt-row"><span class="receipt-lbl">WhatsApp</span><a class="receipt-val" href="https://wa.me/{{ $settings['whatsapp'] }}" target="_blank" rel="noopener">Écrire sur WhatsApp</a></div>@endif
                    <div class="receipt-row"><span class="receipt-lbl">Horaires</span><span class="receipt-val">{{ $settings['opening_hours'] }}</span></div>
                </div>
            </x-sc>
        </div>
    </aside>
</div>

<section class="locations-section" id="nous-trouver">
    <div class="container">
        <div class="section-title" style="margin-bottom:28px">
            <span class="sub-title">Nous trouver</span>
            <h2 class="main-title">Nos deux adresses à Douala</h2>
        </div>
        @php
            $sameSite = $settings['shop_lat'] !== '' && $settings['shop_lat'] === $settings['garage_lat'] && $settings['shop_lng'] === $settings['garage_lng'];
        @endphp
        @if ($sameSite)
            <div class="locations-grid single">
                @include('partials.location-card', [
                    'name' => $settings['company_name'].' & '.$settings['garage_name'],
                    'logos' => [asset('images/logo-camer-auto-key-160.png'), asset('images/logo-3k-auto-services.webp')],
                    'address' => $settings['address'],
                    'hours' => 'Clés : '.$settings['opening_hours'].' · Garage : '.$settings['garage_hours'],
                    'phone' => $settings['phone'],
                    'lat' => $settings['shop_lat'],
                    'lng' => $settings['shop_lng'],
                    'mapsUrl' => $settings['garage_maps_url'],
                ])
            </div>
        @else
            <div class="locations-grid">
                @include('partials.location-card', [
                    'name' => $settings['company_name'],
                    'logo' => asset('images/logo-camer-auto-key-160.png'),
                    'address' => $settings['address'],
                    'hours' => $settings['opening_hours'],
                    'phone' => $settings['phone'],
                    'lat' => $settings['shop_lat'],
                    'lng' => $settings['shop_lng'],
                    'mapsUrl' => $settings['shop_maps_url'],
                ])
                @include('partials.location-card', [
                    'name' => $settings['garage_name'],
                    'logo' => asset('images/logo-3k-auto-services.webp'),
                    'address' => $settings['garage_address'],
                    'hours' => $settings['garage_hours'],
                    'phone' => $settings['garage_phone'],
                    'lat' => $settings['garage_lat'],
                    'lng' => $settings['garage_lng'],
                    'mapsUrl' => $settings['garage_maps_url'],
                ])
            </div>
        @endif
    </div>
</section>
@endsection
